<?php

namespace App\Repositories\InvoiceOrder;
use App\Billing\Invoice;
use App\Services\Invoicing\SendPdfInvoice;
use App\Users\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Knp\Snappy\Pdf;

class SendInvoiceOrderRepository
{
    private $sendPdfInvoice;

    public function __construct(SendPdfInvoice $sendPdfInvoice)
    {
        $this->sendPdfInvoice = $sendPdfInvoice;
    }

    public function sendInvoice($user, $order) {

        if(! $user){
            $user = auth()->user();
        }

        // Check if the user wants to receive his invoices via email.
        if($user->settings && key_exists('send_invoices_via_email', $user->settings) && sendMailOrNot($user, 'orders.created')){

            $pdf = App::make('snappy.pdf.wrapper');
            $pdf->loadView('orders.view', ['order' => $order]);
            $pdf->save(public_path('assets/frontend/orders/po-' . $order->reference . '.pdf'));
            $location = public_path('assets/frontend/orders/po-' . $order->reference . '.pdf');

            Mail::send('emails.orders.created', ['user' => $user], function ($message) use ($user, $location) {
                $message->from(config('app.email'), config('app.name'));

                if ($user->billing_email_address){
                    $message->to($user->email);
                    if(strpos($user->billing_email_address,"@") == true && strpos($user->billing_email_address," ") != true){
                        $message->cc(explode(",", str_replace(';', ',', $user->billing_email_address)), null);
                    }
                }else{
                    $message->to($user->email);
                }

                $message->subject('Your new purchase order has arrived');
                $message->attach($location);
            });

            File::delete($location);
        }
    }
}