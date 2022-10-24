<?php

namespace App\Console\Commands;

use App\Note;
use Carbon\Carbon;
use App\Users\User;
use App\ExtractReport;
use App\Billing\Invoice;
use App\SalesReportExport;
use Spatie\Dropbox\Client;
use Illuminate\Mail\Mailer;
use Illuminate\Console\Command;
use App\Billing\InvoiceRepository;
use App\Subscriptions\Models\Plan;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Dropbox\DropboxRepository;
use App\Http\Controllers\Admin\ReportController;

class DropBoxInvoice extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drop:box:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop Box Invoice';
    private $dropboxRepository;

    /**
     * Create a new command instance.
     *
     * @param DropboxRepository $dropboxRepository
     */
    private $invoiceRepository;
    public function __construct(InvoiceRepository $invoiceRepository,DropboxRepository $dropboxRepository)
    {
        parent::__construct();
        $this->invoiceRepository = $invoiceRepository; 
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
        $users = [1680,2651,884,9784,9399,7696,7850,7890,2665,9023,2755,2849,5456,2849,8240,936,8306,5163,45,5275,91,2974,4150,7399,8892,3838,5658,3878,9361,7937,1892,6817,4387,7085,1623,1603,7073,5058,6975,5792,5529,5792,5529,5792,5529,5792,5529,5792,5529,5792,5529,5792,5529,5792,5529,5792,5529,5792,5529,5792,5529,5792,5529,5792,5529,5792,5529,9672,4137,148,6987,5880,9032,6592,8960,7772];
        foreach($users as $user){
            $this->generateInvoice($user);
        }
        dd($user);
    }

    public function generateInvoice($user_id)
    {
        $user = User::find($user_id);
        if($user){
        $invoices = Invoice::where('user_id',$user_id)->get();
        $authorizationToken = env('DROPBOX_ACCESS');
        $client = new CLient($authorizationToken);

        // Email with download link.
        $MainFolder = 'fasset tig/';
        $subfolder = 'Invoices';


        try{
            foreach($invoices as $invoice)
            {
                    $pdf = App::make('snappy.pdf.wrapper');
                    $pdf->loadView('invoices.view', ['invoice' => $invoice]);

                    $fileName = 'app/public/invoice/'.$user->name.' '.$user->id.'/invoice-' . $invoice->id . '.pdf';
                    
                   $pdf->save(storage_path($fileName));
                   // $location = public_path('assets/frontend/invoices/invoice-' . $invoice->id . '.pdf');
                   $fileNameDrop = 'public/invoice/'.$user->name.' '.$user->id.'/invoice-' . $invoice->id . '.pdf';
        
                    $link = $this->dropboxRepository->saveToDropbox($subfolder,  $fileNameDrop, $client, $MainFolder);
                    $this->info('links '.$link);
                    Storage::delete($fileNameDrop);
                    // $pdf->save(storage_path('app/public/exports/invoice-' . $invoice->id . '.pdf'));
                    // $location = storage_path('app/public/exports/invoice-' . $invoice->id . '.pdf');

                    // File::delete($location);
            }
        }catch (\Exception $exception){
            $this->warn('exception '.$exception->getMessage());
        }
        }
    
    }

  
}
