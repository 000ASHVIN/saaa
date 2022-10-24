<?php

namespace App\Console\Commands;

use App\InvoiceOrder;
use App\PerPeriodExportOrder;
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

class ProcessPerPeriodOrdersReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:per-period-orders';
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
        $entry = PerPeriodExportOrder::where('processed', false)->first();

        if ($entry){
            $user = User::find($entry->user_id);

            list($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $orders) = $this->getData($entry);

            $outstandingOrders = $orders->filter(function ($order){
                if ($order->balance){
                    return $order;
                }
            });

            $file = $this->doMassiveExport($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $outstandingOrders, $orders)->store('xls', storage_path('app/public/exports'), true);

            $authorizationToken = env('DROPBOX_ACCESS');
            $client = new CLient($authorizationToken);

            // Email with download link.
            $MainFolder = 'System_Reports_2018_API/';
            $subfolder = 'Outstanding Per Period Orders';

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
        $from = Carbon::parse($entry['from'])->startOfDay();
        $to = Carbon::parse($entry['to'])->endOfDay();
        $orders = InvoiceOrder::whereBetween('created_at', [$from, $to])->get();

        $data = collect();
        $orders->each(function ($order) use ($data) {
            foreach ($order->payments as $payment) {
                $data->push($payment);
            }
        });

        $totalOrders = $orders;
        $payments = $orders->where('status', 'paid');
        $discounts = $data->where('tags', 'discount');
        $cancellations = $orders->where('status', 'cancelled');
        $credits = $orders->where('status', 'paid');

        return array($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $orders);
    }

    public function doMassiveExport($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $outstandingOrders, $orders)
    {
        return Excel::create('Purchase Orders between ' . date_format($from, 'd F Y') . ' - ' . date_format($to, 'd F Y'), function ($excel) use ($from, $to, $totalOrders, $payments, $discounts, $cancellations, $credits, $outstandingOrders, $orders) {
            $excel->sheet('Total Invoiced', function ($sheet) use ($orders, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Status',
                    'Reference',
                    'Created At',
                    'Total',
                    'Order Type',
                    'Description'
                ]);

                foreach ($orders as $order) {
                    $sheet->appendRow([
                        $order->user->first_name,
                        $order->user->last_name,
                        $order->user->email,
                        $order->status,
                        $order->reference,
                        $order->created_at,
                        $order->total,
                        $order->type,
                        $order->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Total Payments', function ($sheet) use ($payments, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Status',
                    'Reference',
                    'Created At',
                    'Total',
                    'Order Type',
                    'Description'
                ]);

                foreach ($payments as $payment) {
                    $sheet->appendRow([
                        $payment->user->first_name,
                        $payment->user->last_name,
                        $payment->user->email,
                        $payment->status,
                        $payment->reference,
                        date_format($payment->created_at, 'd F Y'),
                        $payment->total,
                        $payment->type,
                        $payment->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Total Discounts', function ($sheet) use ($discounts, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Status',
                    'Reference',
                    'Created At',
                    'Total',
                    'Order Type',
                    'Description'
                ]);

                foreach ($discounts as $discount) {
                    $sheet->appendRow([
                        $discount->invoice_order->user->first_name,
                        $discount->invoice_order->user->last_name,
                        $discount->invoice_order->user->email,
                        $discount->invoice_order->status,
                        $discount->invoice_order->reference,
                        date_format($discount->created_at, 'd F Y'),
                        $discount->amount,
                        $discount->invoice_order->type,
                        $discount->invoice_order->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Total Cancellations', function ($sheet) use ($cancellations, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Status',
                    'Reference',
                    'Created At',
                    'Total',
                    'Order Type',
                    'Description'
                ]);

                foreach ($cancellations as $cancellation) {
                    $sheet->appendRow([
                        $cancellation->user->first_name,
                        $cancellation->user->last_name,
                        $cancellation->user->email,
                        $cancellation->status,
                        $cancellation->reference,
                        date_format($cancellation->created_at, 'd F Y'),
                        $cancellation->total,
                        $cancellation->type,
                        $cancellation->items->first()->name
                    ]);
                }
            });
            $excel->sheet('Outstanding', function ($sheet) use ($outstandingOrders, $excel) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Status',
                    'Reference',
                    'Created At',
                    'Total',
                    'Order Type',
                    'Description'
                ]);

                foreach ($outstandingOrders->where('status', 'unpaid') as $order) {
                    $sheet->appendRow([
                        $order->user->first_name,
                        $order->user->last_name,
                        $order->user->email,
                        ($order->user->subscribed('cpd') ? $order->user->subscription('cpd')->plan->name : "None"),
                        $order->reference,
                        date_format($order->created_at, 'd F Y'),
                        $order->status,
                        $order->balance,
                        $order->type,
                        $order->items->first()->name
                    ]);
                }
            });
        });
    }
}
