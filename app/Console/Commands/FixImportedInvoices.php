<?php

namespace App\Console\Commands;

use App\AppEvents\Ticket;
use App\Billing\Invoice;
use App\Billing\Payment;
use App\Store\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Billing\Transaction;

class FixImportedInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix Transactions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $transactions = Transaction::all();

        $this->info("Total Debit " . money_format('%.2n', $transactions->where('type', 'debit')->sum('amount')));
        $this->info("Total Credit " . money_format('%.2n', $transactions->where('type', 'credit')->sum('amount')));
        $this->info("Total Outstanding " . money_format('%.2n', $transactions->where('type', 'debit')->sum('amount') - $transactions->where('type', 'credit')->sum('amount')));
    }

    public function fixFuckups()
    {
        $transactions = Transaction::whereBetween('updated_at', [Carbon::now()->subHours(1), Carbon::now()->endOfDay()])->get();

        foreach ($transactions as $transaction) {
            $transaction->amount /= 100;
            $transaction->save();
        }
    }

    public function fixTransactions()
    {
        $transactions = Transaction::all();

        foreach ($transactions as $transaction) {
            $this->info($transaction->amount . ' = ' . $transaction->amount * 100 . ' = ' . ($transaction->amount * 100) / 100);
            $transaction->amount *= 100;
            $transaction->save();
        }
    }

    private function getTicketInvoices()
    {
        $tickets = Ticket::whereBetween('created_at', [Carbon::now()->firstOfYear(), Carbon::now()->firstOfYear()->endOfMonth()])->with('invoice')->get();

        $invoices = collect([]);

        $tickets->map(function($ticket) use ($invoices)
        {
            if($ticket->invoice)
            {
                if(! $ticket->invoice->cancelled || $ticket->invoice->status != 'cancelled')
                {                    
                    $paid = abs($ticket->invoice->balance - ($ticket->invoice->sub_total - $ticket->invoice->discount));

                    $invoices->push([
                        'type' => 'ticket',
                        'sub_total' => $ticket->invoice->sub_total,
                        'discount' => $ticket->invoice->discount,
                        // 'total' => $ticket->invoice->total,
                        'paid' => $paid,
                        'balance' => $ticket->invoice->balance
                    ]);
                }                
            }
        });
        
        $results = collect([
            'type' => $invoices->first()['type'] . 's',
            'sub_total' => money_format('%.2n', $invoices->sum('sub_total')),
            'discount' => money_format('%.2n', $invoices->sum('discount')),
            // 'total' => money_format('%.2n', $invoices->sum('total')),
            'paid' => money_format('%.2n', $invoices->sum('paid')),
            'balance' => money_format('%.2n', $invoices->sum('balance'))
        ]);

        return $results;
    }

    private function getOrderInvoices()
    {
        $orders = Order::whereBetween('created_at', [Carbon::now()->firstOfYear(), Carbon::now()->firstOfYear()->endOfMonth()])->with(['invoice'])->get()->unique('invoice.id');

        $invoices = collect([]);

        $orders->map(function($order) use ($invoices)
        {
            if($order->invoice)
            {
                if(! $order->invoice->cancelled || $order->invoice->status != 'cancelled')
                {                    
                    $paid = abs($order->invoice->balance - ($order->invoice->sub_total - $order->invoice->discount));

                    $invoices->push([
                        'type' => 'order',
                        'sub_total' => $order->invoice->sub_total,
                        'discount' => $order->invoice->discount,
                        // 'total' => $order->invoice->total,
                        'paid' => $paid,
                        'balance' => $order->invoice->balance
                    ]);
                }                
            }
        });
        
        $results = collect([
            'type' => $invoices->first()['type'] . 's',
            'sub_total' => money_format('%.2n', $invoices->sum('sub_total')),
            'discount' => money_format('%.2n', $invoices->sum('discount')),
            // 'total' => money_format('%.2n', $invoices->sum('total')),
            'paid' => money_format('%.2n', $invoices->sum('paid')),
            'balance' => money_format('%.2n', $invoices->sum('balance'))
        ]);

        return $results;
    }

    private function getSubscriptionInvoices()
    {
        $subs = Invoice::whereBetween('created_at', [Carbon::now()->firstOfYear(), Carbon::now()->firstOfYear()->endOfMonth()])->with('items')->get();

        $invoices = collect([]);
        $imports = collect([]);

        $subs->map(function($sub) use ($invoices, $imports)
        {
            if($sub)
            {
                if(count($sub->items))
                {
                    if(! $sub->cancelled || $sub->status != 'cancelled')
                    {
                        if($sub->items->first()->type == 'subscription')
                        {
                            $paid = abs($sub->balance - ($sub->sub_total - $sub->discount));

                            $invoices->push([
                                'type' => 'subscription',
                                'sub_total' => $sub->sub_total,
                                'discount' => $sub->discount,
                                // 'total' => $sub->total,
                                'paid' => $paid,
                                'balance' => $sub->balance
                            ]);
                        }
                    }
                } else {
                    $imports->push($sub);
                }
            }
        });

        // $this->info(count($imports) . " Subscription invoices has been for imported members and will not be accounted for");
        
        $results = collect([
            'type' => $invoices->first()['type'] . 's',
            'sub_total' => money_format('%.2n', $invoices->sum('sub_total')),
            'discount' => money_format('%.2n', $invoices->sum('discount')),
            // 'total' => money_format('%.2n', $invoices->sum('total')),
            'paid' => money_format('%.2n', $invoices->sum('paid')),
            'balance' => money_format('%.2n', $invoices->sum('balance'))
        ]);

        return $results;
    }

    private function convertSubs()
    {
        $invoices = Invoice::withTrashed()->with('user', 'items')
                                          ->where('total', 0)->where('sub_total', 0)->where('discount', 0)->where('status', 'cancelled')->where('cancelled', 1)
                                          ->get(); 

        $filtered = $invoices->filter(function($invoice)
        {
            if(! count($invoice->items)) {
                return $invoice;
            }
        });

        $subs = collect([]);

        foreach ($filtered as $invoice) {
            if($invoice->user->subscription->plan->price == 0)
            {
                $subs->push($invoice);
            }
        }

        foreach ($subs as $invoice) {
            $subscription = $invoice->user->subscription;

            $subscription->plan_id = 4;
            $subscription->has_installments = 0;
            $subscription->installments_interval = null;
            $subscription->installments_interval_unit = null;
            $subscription->installments_total_number = null;
            $subscription->installments_next_due_date = null;
            $subscription->installments_grace_period_days = null;
            $subscription->installments_item_id = null;
            $subscription->is_overdue = 0;
            $subscription->save();
        }
    }

    private function updateInvoices()
    {
        $invoices = Invoice::withTrashed()->with('user', 'items')
                                          ->where('total', 0)->where('sub_total', 0)->where('discount', 0)->where('status', 'cancelled')->where('cancelled', 1)
                                          ->get(); 

        $filtered = $invoices->filter(function($invoice)
        {
            if(! count($invoice->items)) {
                return $invoice;
            }
        });

        $subs = collect([]);

        foreach ($filtered as $invoice) {
            if($invoice->user->subscription->plan->price > 0)
            {
                $subs->push($invoice);
            }
        }

        foreach ($subs as $invoice) {
            $subscription = $invoice->user->subscription;
            $plan = $subscription->plan;

            // Step 1: Update Subscription
            $subscription->has_installments = 0;
            $subscription->installments_interval = null;
            $subscription->installments_interval_unit = null;
            $subscription->installments_total_number = null;
            $subscription->installments_next_due_date = null;
            $subscription->installments_grace_period_days = null;
            $subscription->installments_item_id = null;
            $subscription->is_overdue = 0;
            $subscription->save();

            // Step 2: Update Invoice
            $invoice->sub_total = $plan->price;
            $invoice->total = $plan->price;
            $invoice->balance = 0;
            $invoice->save();

            // Step 3: Create invoice line items
            $invoice->items()->create([
                'type' => 'subscription',
                'name' => $plan->name,
                'description' => $plan->description,
                'price' => $plan->price
            ]);
        }
    }

    protected function fixSingleInvoice()
    {
        $invoices = Invoice::withTrashed()->with('user', 'items')
                                          ->where('total', 0)->where('sub_total', 0)->where('discount', 0)->where('status', 'cancelled')->where('cancelled', 1)
                                          ->get(); 

        if(count($invoices)) {
            $invoice = $invoices->first();
            $invoice->sub_total = $invoice->items->first()->price;
            $invoice->total = $invoice->items->first()->price;
            $invoice->balance = 0;
            $invoice->save();
        }
    }

    private function checkInvoices()
    {
        $invoices = Invoice::withTrashed()->with('user', 'items')
                                          ->where('total', 0)->where('sub_total', 0)->where('discount', 0)->where('status', 'cancelled')->where('cancelled', 1)
                                          ->get(); 

        if(count($invoices)) {
            return $this->error("We have more fucked up invoices....");
        }

        return $this->info("All invoices has been sorted....");
    }

    private function fixUnmanned()
    {
        $invoices = Invoice::withTrashed()->with('user', 'items')->get();

        $unmanned = $invoices->filter(function($invoice)
        {
            if (! $invoice->user) {
                return $invoice;
            }
        });

        foreach ($unmanned as $invoice) {
            $invoice->user_id = 1;
            $invoice->save();
        }
    }

    private function fixInvoiceDiscounts()
    {
        $invoices = Invoice::withTrashed()->with('user', 'items')
                                      ->where('discount', ">", 0)
                                      ->get();

        if(! count($invoices))
            return;

        foreach ($invoices as $invoice) {
            
            if($invoice->items->first())
            {
                $type = null;

                switch ($invoice->items->first()->type) {
                    case 'subscription':
                        $type = 'subscription';
                        break;
                    case 'product':
                        $type = 'store';
                        break;
                    case 'ticket':
                        $type = 'event';
                        break;
                }

                // Step 1: Add The discount back on to the invoice
                $invoice->total += $invoice->discount;
                $invoice->balance += $invoice->discount;
                $invoice->save();

                // Step 2: Create Discount Credit Note
                $invoice->transactions()->create([
                    'user_id' => $invoice->user->id, 
                    'invoice_id' => $invoice->id, 
                    'type' => 'credit',
                    'display_type' => 'Credit Note', 
                    'status' => 'Closed', 
                    'category' => $type, 
                    'amount' => $invoice->discount, 
                    'ref' => $invoice->reference, 
                    'method' => 'Applied', 
                    'description' => "Invoice #{$invoice->reference} discount", 
                    'tags' => "Discount", 
                    'date' => $invoice->created_at->addHours(1)
                ]);

                // Step 3: Remove Discount from Invoice
                $invoice->discount = 0;
                $invoice->save();
            } else {
                $this->error($invoice->id);
            }
        }
    }

    private function createInvoiceTransactions()
    {
        $invoices = Invoice::withTrashed()->with('user', 'items')->get();
        
        foreach ($invoices as $invoice) {
            
            if($invoice->items->first())
            {
                $type = null;

                switch ($invoice->items->first()->type) {
                    case 'subscription':
                        $type = 'subscription';
                        break;
                    case 'product':
                        $type = 'store';
                        break;
                    case 'ticket':
                        $type = 'event';
                        break;
                }

                $invoice->type = $type;
                $invoice->save();

                $invoice->transactions()->create([
                    'user_id' => $invoice->user->id, 
                    'invoice_id' => $invoice->id, 
                    'type' => 'debit',
                    'display_type' => 'Invoice', 
                    'status' => ($invoice->paid) ? 'Closed' : 'Open',
                    'category' => $type,
                    'amount' => $invoice->total, 
                    'ref' => $invoice->reference, 
                    'method' => 'Void', 
                    'description' => "Invoice #{$invoice->reference}", 
                    'tags' => "Invoice", 
                    'date' => $invoice->created_at
                ]);
            } else {
                $this->error($invoice->id);
            }            
        }
    }

    private function fixPayments()
    {
        $invoices = Invoice::withTrashed()->where('status', 'cancelled')->get();

        $paid = collect([]);

        foreach ($invoices as $invoice) {
            if(count($invoice->payments))
                $paid->push($invoice);
        }

        foreach ($paid as $invoice) {
            if($invoice->balance == 0) {

                $invoice->paid = true;
                $invoice->status = 'paid';
                $invoice->cancelled = false;
                $invoice->save();

            }
        }        
    }

    private function cancelInvoices()
    {
        $invoices = Invoice::withTrashed()->where('status', 'cancelled')->get();

        foreach ($invoices as $invoice) {

            if($invoice->items->first())
            {
                $type = null;

                switch ($invoice->items->first()->type) {
                    case 'subscription':
                        $type = 'subscription';
                        break;
                    case 'product':
                        $type = 'store';
                        break;
                    case 'ticket':
                        $type = 'event';
                        break;
                }

                $invoice->update([
                    'paid' => false,
                    'cancelled' => true,
                    'status' => 'cancelled',
                    'balance' => $invoice->total - $invoice->transactions()->where('type', 'credit')->sum('amount')
                ]);

                $invoice->transactions()->create([
                    'user_id' => $invoice->user->id, 
                    'invoice_id' => $invoice->id, 
                    'type' => 'credit',
                    'display_type' => 'Credit Note', 
                    'status' => 'Closed',
                    'category' => $type,
                    'amount' => $invoice->total - $invoice->transactions()->where('type', 'credit')->sum('amount'), 
                    'ref' => $invoice->reference, 
                    'method' => 'Void', 
                    'description' => "Invoice #{$invoice->reference} cancellation", 
                    'tags' => "Cancellation", 
                    'date' => $invoice->created_at->addHours(1)
                ]);
            }
        }
    }

    private function generatePayments()
    {
        $payments = Payment::with('invoice')->get();

        foreach ($payments as $payment) {

            // Reset Invoice Balance
            $payment->invoice->update([
                'balance' => $payment->invoice->total
            ]);

            $invoice = $payment->invoice;

            if($invoice->items->first())
            {            
                $type = null;

                switch ($invoice->items->first()->type) {
                    case 'subscription':
                        $type = 'subscription';
                        break;
                    case 'product':
                        $type = 'store';
                        break;
                    case 'ticket':
                        $type = 'event';
                        break;
                }            

                // Create payment transaction
                $invoice->transactions()->create([
                    'user_id' => $invoice->user->id, 
                    'invoice_id' => $invoice->id, 
                    'type' => 'credit',
                    'display_type' => 'Payment', 
                    'status' => 'Closed', 
                    'category' => $type, 
                    'amount' => $payment->amount, 
                    'ref' => $invoice->reference,
                    'method' => $payment->method,
                    'description' => $payment->description,
                    'tags' => "Payment", 
                    'date' => $payment->date_of_payment->addHours(1)
                ]);

            } else {
                $this->error($invoice->id);
            }
        }
    }

    public function green($text)
    {
        return "\033[1;32m" . $text . "\033[0m";
    }

    public function red($text)
    {
        return "\033[1;31m" . $text . "\033[0m";
    }
}
