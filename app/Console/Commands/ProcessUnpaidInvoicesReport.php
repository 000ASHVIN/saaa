<?php

namespace App\Console\Commands;

use App\Billing\Invoice;
use App\PerPeriodExportInvoice;
use App\Repositories\Dropbox\DropboxRepository;
use App\UnpaidInvoiceExport;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Dropbox\Client;
use Illuminate\Mail\Mailer;

class ProcessUnpaidInvoicesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:unpaidInvoicesExtract';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Outstanding Invoicing Report';
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
        $entry = UnpaidInvoiceExport::where('processed', false)->first();
        if ($entry){
            $user = User::find($entry->user_id);

            $users = User::all()
                ->filter(function($user) {
                if(count($user->overdueInvoices()))
                return $user;
            });

            $authorizationToken = env('DROPBOX_ACCESS');
            $client = new CLient($authorizationToken);

            $file = $this->doMassiveExport($users)->store('xls', storage_path('app/public/exports'), true);

            // Email with download link.
            $MainFolder = 'System_Reports_2018_API/';
            $subfolder = 'Outstanding Invoices Report';

            $link = $this->dropboxRepository->saveToDropbox($subfolder, 'public/exports/'. $file['file'], $client, $MainFolder);

            if(sendMailOrNot($user, 'uploads.unpaid_invoices_report_completed')) {
            $mailer->send('emails.uploads.unpaid_invoices_report_completed', ['link' => $link], function ($m) use($user) {
                $m->from(config('app.email'), config('app.name'));
                $m->to($user->email, $user->first_name.' '.$user->last_name)->subject('Overdue Invoices Report is ready for download');
            });
            }

            Storage::delete('public/exports/'. $file['file']);

            $entry->update(['processed' => true]);
            $entry->save();
        }
    }

    public function doMassiveExport($users)
    {
        return Excel::create('Overdue Invoices Report '.Carbon::now()->timestamp, function($excel) use($users) {
            $excel->sheet('Outstanding Invoices', function($sheet) use($users) {
                $sheet->appendRow(['Date', 'Name','Status','ID Number','Email Address','Cellphone Number','Package','Invoice reference', 'Invoice Description', 'Event', 'Invoice Type', 'Invoice Balance', 'Paid', 'Status', 'Method', 'PTP', 'Sales Agent']);
                foreach ($users as $user) {
                    foreach ($user->overdueInvoices() as $invoice){
                        $sheet->appendRow([
                            $invoice->transactions()->where('type', 'debit')->first()->date,
                            $invoice->user->first_name.' '.$invoice->user->last_name,
                            $invoice->user->status,
                            $invoice->user->profile->id_number,
                            $invoice->user->email,
                            $invoice->user->profile->cell,
                            ($invoice->user->subscribed('cpd')? $invoice->user->subscription('cpd')->plan->name : "No Plan"),
                            $invoice->reference,
                            $invoice->lineItems,
                            ($invoice->ticket ? $invoice->ticket->event->name : "None"),
                            $invoice->type,
                            number_format($invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount'), 2, ".", ""),
                            $invoice->paid,
                            $invoice->status,
                            $user->payment_method,
                            (strtotime($invoice->ptp_date) > 0 ?  $invoice->ptp_date : " -"),
                            ($user->subscription('cpd')) ? ($user->subscription('cpd')->SalesAgent()) ? ucwords($user->subscription('cpd')->SalesAgent()->first_name.' '.$user->subscription('cpd')->SalesAgent()->last_name) : "" : " - "
                        ]);
                    }
                }
            });
        });
    }
}
