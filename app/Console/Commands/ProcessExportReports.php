<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Dropbox\DropboxRepository;
use Illuminate\Mail\Mailer;
use App\SalesReportExport;
use App\Users\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Dropbox\Client;
use Carbon\Carbon;
use App\Note;
use App\Subscriptions\Models\Plan;
use App\ExtractReport;
use App\Http\Controllers\Admin\ReportController;

class ProcessExportReports extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:export:reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process Export Report';
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
        $extractReport = ExtractReport::where('processed', false)->where('started', false)->get();
        
        foreach($extractReport as $extract){
            $ReportController =New ReportController(); 
            $data = json_decode($extract->request,true);
            $function = $extract->report;
            $request = new \Illuminate\Http\Request($data);
            $user = User::find($extract->user_id);
            $extract->update(['started' => true]);
            $action = $ReportController->$function($request);
            if($action){
                $file = $action->store('xls', storage_path('app/public/exports'), true);
            
            $authorizationToken = env('DROPBOX_ACCESS');
            $client = new CLient($authorizationToken);

            // Email with download link.
            $MainFolder = 'System_Reports_2018_API/';
            $subfolder = 'Export Report';

            $link = $this->dropboxRepository->saveToDropbox($subfolder, 'public/exports/'. $file['file'], $client, $MainFolder);

            if(sendMailOrNot($user, 'uploads.unpaid_invoices_report_completed')) {
            $mailer->send('emails.uploads.unpaid_invoices_report_completed', ['link' => $link], function ($m) use($user) {
                $m->from(config('app.email'), config('app.name'));
                $m->to($user->email, $user->first_name.' '.$user->last_name)->subject('Your Report is ready for download');
            });
            }

            Storage::delete('public/exports/'. $file['file']);
        }
            $extract->update(['processed' => true]);
            $extract->save();
        }
    }

  
}
