<?php

namespace App\Console\Commands;

use App\Billing\Invoice;
use App\PerPeriodExportInvoice;
use App\Repositories\Dropbox\DropboxRepository;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Dropbox\Client;
use Illuminate\Mail\Mailer;

class ProcessPerPeriodReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:per-period';
    protected $description = 'Process Per Period Reporting';
    private $dropboxRepository;

    /**
     * Create a new command instance.
     *
     * @param DropboxRepository $dropboxRepository
     */
    public function __construct(DropboxRepository $dropboxRepository)
    {
        parent::__construct();
        $this->dropboxRepository = $dropboxRepository;
    }

    /**
     * Execute the console command.
     *
     * @param Mailer $mailer
     * @return mixed
     */
    public function handle(Mailer $mailer)
    {
       $entry = PerPeriodExportInvoice::where('processed', false)->first();
       if ($entry){
           $user = User::find($entry->user_id);

           list($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $invoices) = $this->getData($entry);
           $outstandingInvoices = $invoices->filter(function ($invoice){
               if ($invoice->balance){
                   return $invoice;
               }
           });

           $file = $this->doMassiveExport($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $outstandingInvoices, $invoices)->store('xls', storage_path('app/public/exports'), true);

           $authorizationToken = env('DROPBOX_ACCESS');
           $client = new CLient($authorizationToken);

           // Email with download link.
           $MainFolder = 'System_Reports_2018_API/';
           $subfolder = 'Outstanding Per Period';

           $link = $this->dropboxRepository->saveToDropbox($subfolder, 'public/exports/'. $file['file'], $client, $MainFolder);

           if(sendMailOrNot($user, 'uploads.report_upload_complete')) {
           $mailer->send('emails.uploads.report_upload_complete', ['link' => $link], function ($m) use($user) {
               $m->from(config('app.email'), config('app.name'));
               $m->to($user->email, $user->first_name.' '.$user->last_name)->subject('Your Report is ready for download');
           });
            }

           Storage::delete('public/exports/'. $file['file']);

           $entry->update(['processed' => true]);
           $entry->save();
       }
        return "Done";
    }

    public function getData($entry)
    {
        $from = Carbon::parse($entry->from)->startOfDay();
        $to = Carbon::parse($entry->to)->endOfDay();
        $invoices = Invoice::whereBetween('created_at', [$from, $to])->get();

        $data = collect();
        $invoices->each(function ($invoice) use ($data) {
            foreach ($invoice->transactions as $transaction) {
                $data->push($transaction);
            }
        });

        $totalInvoices = $data->where('display_type', 'Invoice');
        $payments = $data->where('display_type', 'Payment')->where('type', 'credit');
        $discounts = $data->where('tags', 'Discount');
        $cancellations = $data->where('tags', 'Cancellation')->where('type', 'credit');
        $credits = $data->where('type', 'credit');
        return array($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $invoices);
    }

    public function doMassiveExport($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $outstandingInvoices, $invoices)
    {
        return Excel::create('Invoices between ' . date_format($from, 'Y-m-d') . ' - ' . date_format($to, 'Y-m-d').'-'.Carbon::now()->timestamp, function ($excel) use ($from, $to, $totalInvoices, $payments, $discounts, $cancellations, $credits, $outstandingInvoices, $invoices) {
            $excel->sheet('Total Invoiced', function ($sheet) use ($invoices, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Created At',
                    'Total',
                    'Invoice Type',
                    'Description',
                    'Subscription Starts At',
                    'Sales Agent',
                    'Payment Method'
                ]);

                foreach ($invoices as $invoice) {
                    $sheet->appendRow([
                        $invoice->user->first_name,
                        $invoice->user->last_name,
                        $invoice->user->email,
                        ($invoice->user->subscribed('cpd') ? $invoice->user->subscription('cpd')->plan->name : "None"),
                        $invoice->reference,
                        $invoice->created_at,
                        $invoice->transactions->where('type', 'debit')->sum('amount'),
                        $invoice->type,
                        $invoice->items->first()->name,
                        $invoice->user->subscription('cpd') ? date_format(Carbon::parse(($invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : " - ",
                        ($invoice->note ? $invoice->note->logged_by : "-"),
                        (count($invoice->transactions->where('tags', 'Payment')) ? $invoice->transactions->where('tags', 'Payment')->first()->method : "-")
                    ]);
                }
            });
            $excel->sheet('Total Payments', function ($sheet) use ($payments, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Date',
                    'Amount',
                    'Invoice Type',
                    'Description',
                    'Subscription Starts At'
                ]);

                foreach ($payments as $payment) {
                    $sheet->appendRow([
                        $payment->invoice->user->first_name,
                        $payment->invoice->user->last_name,
                        $payment->invoice->user->email,
                        ($payment->invoice->user->subscribed('cpd') ? $payment->invoice->user->subscription('cpd')->plan->name : "None"),
                        $payment->invoice->reference,
                        date_format($payment->date, 'Y-m-d'),
                        $payment->amount,
                        $payment->invoice->type,
                        $payment->invoice->items->first()->name,
                        $payment->invoice->user->subscription('cpd') ? date_format(Carbon::parse(($payment->invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : " - "
                    ]);
                }
            });
            $excel->sheet('Total Discounts', function ($sheet) use ($discounts, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Date',
                    'Amount',
                    'Invoice Type',
                    'Description',
                    'Subscription Starts At'
                ]);

                foreach ($discounts as $discount) {
                    $sheet->appendRow([
                        $discount->invoice->user->first_name,
                        $discount->invoice->user->last_name,
                        $discount->invoice->user->email,
                        ($discount->invoice->user->subscribed('cpd') ? $discount->invoice->user->subscription('cpd')->plan->name : "None"),
                        $discount->invoice->reference,
                        date_format($discount->date, 'Y-m-d'),
                        $discount->amount,
                        $discount->invoice->type,
                        $discount->invoice->items->first()->name,
                        $discount->invoice->user->subscription('cpd') ? date_format(Carbon::parse(($discount->invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : " - "
                    ]);
                }
            });
            $excel->sheet('Total Cancellations', function ($sheet) use ($cancellations, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Date',
                    'Amount',
                    'Invoice Type',
                    'Description',
                    'Subscription Starts At'
                ]);

                foreach ($cancellations as $cancellation) {
                    $sheet->appendRow([
                        $cancellation->invoice->user->first_name,
                        $cancellation->invoice->user->last_name,
                        $cancellation->invoice->user->email,
                        ($cancellation->invoice->user->subscribed('cpd') ? $cancellation->invoice->user->subscription('cpd')->plan->name : "None"),
                        $cancellation->invoice->reference,
                        date_format($cancellation->date, 'Y-m-d'),
                        $cancellation->amount,
                        $cancellation->invoice->type,
                        $cancellation->invoice->items->first()->name,
                        $cancellation->invoice->user->subscription('cpd') ? date_format(Carbon::parse(($cancellation->invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : " - "
                    ]);
                }
            });
            $excel->sheet('Total Credits', function ($sheet) use ($credits, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Date',
                    'Amount',
                    'Invoice Type',
                    'Description',
                    'Subscription Starts At'
                ]);

                foreach ($credits as $credit) {
                    $sheet->appendRow([
                        $credit->invoice->user->first_name,
                        $credit->invoice->user->last_name,
                        $credit->invoice->user->email,
                        ($credit->invoice->user->subscribed('cpd') ? $credit->invoice->user->subscription('cpd')->plan->name : "None"),
                        $credit->date,
                        date_format($credit->date, 'Y-m-d'),
                        $credit->amount,
                        $credit->invoice->type,
                        $credit->invoice->items->first()->name,
                        $credit->invoice->user->subscription('cpd') ? date_format(Carbon::parse(($credit->invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : " - "
                    ]);
                }
            });
            $excel->sheet('Outstanding', function ($sheet) use ($outstandingInvoices, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Subscription',
                    'Reference',
                    'Created At',
                    'Status',
                    'Total',
                    'Type',
                    'Description',
                    'Subscription Starts At'
                ]);

                foreach ($outstandingInvoices as $invoice) {
                    $sheet->appendRow([
                        $invoice->user->first_name,
                        $invoice->user->last_name,
                        $invoice->user->email,
                        ($invoice->user->subscribed('cpd') ? $invoice->user->subscription('cpd')->plan->name : "None"),
                        $invoice->reference,
                        date_format($invoice->created_at, 'Y-m-d'),
                        $invoice->status,
                        $invoice->balance,
                        $invoice->type,
                        $invoice->items->first()->name,
                        $invoice->user->subscription('cpd') ? date_format(Carbon::parse(($invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : " - "
                    ]);
                }
            });
        });
    }
}
