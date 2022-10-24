<?php

namespace App\Console\Commands;

use App\Billing\Invoice;
use App\Note;
use App\Repositories\CreditMemo\CreditMemoRepository;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Mockery\Exception;

class CancelOverdueSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:subscripions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will cancel all overdue subscriptions';
    /**
     * @var CreditMemoRepository
     */
    private $creditMemoRepository;

    /**
     * Create a new command instance.
     * @param CreditMemoRepository $creditMemoRepository
     */
    public function __construct(CreditMemoRepository $creditMemoRepository)
    {
        parent::__construct();
        $this->creditMemoRepository = $creditMemoRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::with('invoices')->get();
        $overdue = collect();

        $users->each(function ($user) use($overdue){
            if ($user->ageAnalysis()){
                if ($user->ageAnalysis() > '60'){
                    $invoices = $user->overdueInvoices()->reject(function ($invoice){
                        if ($invoice->type != 'subscription'){
                            return $invoice;
                        }
                    });

                    if ($invoices->count() > 2){
                        if ($user->subscribed('cpd')){
                            $overdue->push($user);
                        }
                    };
                }
            }
        });

        $this->info('We have '.count($overdue).' subscriptions that we need to cancel');
        $overdue->each(function ($user){
            $invoices = $user->invoices->reject(function ($invoice){
                if ($invoice->type != 'subscription' || $invoice->paid == true || $invoice->status == 'cancelled'){
                    return $invoice;
                }
            });

            try{
                $invoices->each(function ($invoice){
                    $this->info('cancelling invoice '.$invoice->reference);
                    $this->cancel($invoice->id);
                });

                $this->info('cancelling Subscription for '.$invoices->first()->user->first_name.' with email '.$invoices->first()->user->email);
                if ($invoices->first()->user->subscribed('cpd')){
                    $invoices->first()->user->subscription('cpd')->cancel(true);

                    $note = new Note([
                        'type' => 'general',
                        'description' => 'CPD Subscription was cancelled due to non payment on subscription invoices.',
                        'logged_by' => 'System',
                    ]);

                    $invoices->first()->user->notes()->save($note);
                }
            }catch (Exception $exception){
                return;
            }
        });
    }

    public function cancel($id){
        $invoice = Invoice::with(['transactions', 'items'])->find($id);

        // Create Credit note
        $transaction = $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'credit',
            'display_type' => 'Credit Note',
            'status' => 'Closed',
            'category' => $invoice->type,
            'amount' => $invoice->balance,
            'ref' => $invoice->reference,
            'method' => 'Void',
            'description' => "Invoice #{$invoice->reference} cancellation",
            'tags' => "Cancellation",
            'date' => Carbon::now()
        ]);


        // Set Cancelled on Invoice
        $invoice->cancelled = 1;
        $invoice->status = 'cancelled';
        $invoice->save();

        // Create new entry for credit memo
        $this->creditMemoRepository->store($transaction);
    }
}
