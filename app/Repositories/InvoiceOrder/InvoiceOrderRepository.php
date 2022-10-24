<?php

namespace App\Repositories\InvoiceOrder;
use App\Billing\Item;
use App\Billing\Transaction;
use App\InvoiceOrder;
use App\Repositories\Invoice\SendInvoiceRepository;
//use App\Services\Orders\SendPdfInvoiceOrder;
use Carbon\Carbon;

class InvoiceOrderRepository
{
    private $sendInvoiceRepository, $order, $sendPdfInvoiceOrder;
    public function __construct(InvoiceOrder $order, SendInvoiceRepository $sendInvoiceRepository)
    {
        $this->order = $order;
        $this->sendInvoiceRepository = $sendInvoiceRepository;
    }

    /** Process the charge and proceed with converting the order. *
     * @param $order
     * @param $method
     * @param $description
     * @return mixed
     */
    public function processCharge($order, $method, $description)
    {
        $invoice = $order->convert();
        $order->settle($method, $description);

        $this->allocatePayment($invoice->fresh(), $order->total - $order->discount, $method, $description);
        $invoice->fresh()->settle();
        $invoice->fresh()->autoUpdateAndSave();
        $invoice->update([ 'balance' => $invoice->balance ]);
        return $invoice;
    }

    /** Allocate the payment to this invoice.
     * @param $invoice
     * @param $amount
     * @param $method
     * @param $description
     */
    public function allocatePayment($invoice, $amount, $method, $description)
    {
        if ($invoice->fresh()->transactions->where('type', 'debit')->sum('amount') - $invoice->fresh()->transactions->where('type', 'credit')->sum('amount') > 0){
            $invoice->transactions()->create([
                'user_id' => $invoice->user->id,
                'invoice_id' => $invoice->id,
                'type' => 'credit',
                'display_type' => 'Payment',
                'status' => 'Closed',
                'category' => $invoice->type,
                'amount' => $amount,
                'ref' => $invoice->reference,
                'method' => $method,
                'description' => $description,
                'tags' => "Payment",
                'date' => Carbon::now()->addSeconds(30)
            ]);
        }
    }

    public function sendInvoiceOrder($user, $order) {
        if(! $user){
            $user = auth()->user();
        }

        if(env('APP_ENV') === 'local')
            return;

        if(sendMailOrNot($user, 'orders.created')) {
        $this->sendPdfInvoiceOrder->generate($order);

        $location = public_path('assets/frontend/invoices/order-' . $order->reference . '.pdf');
        Mail::send('emails.orders.created', ['user' => $user], function ($message) use ($user, $location) {
            $message->from(config('app.email'), config('app.name'));
            $message->to($user->email);
            $message->subject('Your new purchase order has arrived');
            $message->attach($location);
        });
        File::delete(public_path('assets/frontend/invoices/order-' . $order->reference . '.pdf'));
        }
    }

    public function generateSubscriptionOrder($user, $plan)
    {
        $order = new InvoiceOrder();
        $order->type = 'subscription';
        $order->setUser($user);
        $order->save();
        $item = new Item;
        $item->type = 'subscription';
        $item->name = $plan->name;
        $item->description = $plan->description;
        $item->price = $plan->price;
        $item->item_id = $plan->id;
        $item->item_type = get_class($plan);
        $item->save();
        $order->addItem($item);
        $order->autoUpdateAndSave();
        return $order; 
    }

    public function generateCourseOrder($user, $course,$request)
    {
        $order = new InvoiceOrder();
        $order->type = 'course';
        $order->setUser($user);
        $order->save();
        $item = new Item;
        $item->type = 'course';
        $item->name = $course->title;
        $item->description = $course->title;
        $item->price = $course->order_price;
        $item->item_id = $course->id;
        $item->item_type = get_class($course);
        $item->course_type = $request->enrollmentOption;
        $item->save();
        $order->addItem($item);
        $order->autoUpdateAndSave();
        return $order; 
    }
}