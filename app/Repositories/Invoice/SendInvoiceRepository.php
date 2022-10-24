<?php

namespace App\Repositories\Invoice;

use App\Billing\Invoice;
use App\Services\Invoicing\SendPdfInvoice;
use App\Users\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Knp\Snappy\Pdf;

class SendInvoiceRepository
{
    private $sendPdfInvoice;

    public function __construct(SendPdfInvoice $sendPdfInvoice)
    {
        $this->sendPdfInvoice = $sendPdfInvoice;
    }

    public function sendInvoice($user, $invoice) {

        if(! $user){
            $user = auth()->user();
        }

        try{
            if($invoice && $user->settings && key_exists('send_invoices_via_email', $user->settings) && sendMailOrNot($user, 'invoices.created')){

                $pdf = App::make('snappy.pdf.wrapper');
                $pdf->loadView('invoices.view', ['invoice' => $invoice]);

                $pdf->save(public_path('assets/frontend/invoices/invoice-' . $invoice->reference . '.pdf'));
                $location = public_path('assets/frontend/invoices/invoice-' . $invoice->reference . '.pdf');

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
        }catch (\Exception $exception){
            \Log::info($exception);
        }

    }
}