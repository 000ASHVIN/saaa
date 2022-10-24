<?php
namespace App\Repositories\Order;
use App\Services\Orders\SendPdfInvoiceOrder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class SendOrderRepository
{
    public $sendPdforder;
    public function __construct(SendPdfInvoiceOrder $sendPdforder)
    {
        $this->sendPdforder = $sendPdforder;
    }

    public function sendOrder($user, $order) {

        if(! $user){
            $user = auth()->user();
        }

        // Check if the user wants to receive his invoices via email.
        if($user->settings && key_exists('send_invoices_via_email', $user->settings) && sendMailOrNot($user, 'invoices.created')){

            $pdf = App::make('snappy.pdf.wrapper');
            $pdf->loadView('orders.view', ['order' => $order]);
            $pdf->save(public_path('assets/frontend/invoices/invoice-' . $order->reference . '.pdf'));
            $location = public_path('assets/frontend/invoices/invoice-' . $order->reference . '.pdf');

            Mail::send('emails.invoices.created', ['user' => $user], function ($message) use ($user, $location) {
                $message->from(config('app.email'), config('app.name'));

                if ($user->billing_email_address){
                    $message->to($user->email);
                    if(strpos($user->billing_email_address,"@") == true && strpos($user->billing_email_address," ") != true){
                        $message->cc(explode(",", str_replace(';', ',', $user->billing_email_address)), null);
                    }
                }else{
                    $message->to($user->email);
                }

                $message->subject('Your new invoice has arrived');
                $message->attach($location);
            });

            File::delete($location);
        }
    }
}