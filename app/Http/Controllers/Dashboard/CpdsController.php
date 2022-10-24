<?php

namespace App\Http\Controllers\Dashboard;

use App\Activities\Activity;
use App\Note;
use App\Users\Cpd;
use Carbon\Carbon;
use App\Http\Requests;
use Exception;
use Illuminate\Http\Request;
use App\Users\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddCpdsRequest;
use Knp\Snappy\Pdf;
use App;
use App\AppEvents\Ticket;
use App\Assessment;
use App\Video;
use DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
class CpdsController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('auth');
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the user's cpd records.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $cpds = auth()->user()->GetAllCPD();
        $all_cpds  = clone $cpds;
        $cpdAll = $all_cpds->select(DB::raw('year(date) as Year'))->get()->pluck('Year')->toArray();
        if($request->has('from_year') && $request->from_year){
           $cpds = $cpds->where(DB::raw('year(date)'),'>=',$request->from_year); 
        } 
        if($request->has('to_year') && $request->to_year){
            $cpds = $cpds->where(DB::raw('year(date)'),'<=',$request->to_year); 
        }

        if($request->type == "print"){
           
            return $this->print($cpds);
        }
        if($request->type == "email"){
            return $this->emailCPD($cpds);
        }
        if($request->type == "export"){
            return $this->exportCPD($cpds);
        }
        $cpds_total_hours = $cpds->sum('hours');
        $cpds = $cpds->orderBy('date','desc')->paginate(10);

        foreach($cpds as $cpd) {
            if($cpd->certificate) {
                $this->changeEventCategoryAndServiceProvider($cpd);
            }
        }
        $cpds->appends($request->all());
        $staff = [''=>'Select Employee'];
        if(auth()->user()->company && count(auth()->user()->company->staff)) {
            $staffs = auth()->user()->company->staff->pluck('name','id')->toArray();
            if(count($staffs) > 0) {
                foreach($staffs as $staff_id => $staff_name) {
                    $staff[$staff_id] = $staff_name;
                }
            }
        }

        return view('dashboard.cpd', compact('cpds','cpdAll','cpds_total_hours', 'staff'));
    }

    public function changeEventCategoryAndServiceProvider($cpd) {
        $view_path = $cpd->certificate->view_path;
        $event = null;  
        if($view_path === 'certificates.attendance') {
            $ticket = Ticket::with('event', 'event.categories')->where('id', $cpd->certificate->source_id)->first();
            if($ticket) {
                $event = $ticket->event;
            }
            
        }

        if($view_path == 'certificates.assessment') {
            $assessment = Assessment::with('events', 'events.categories')->where('id', $cpd->certificate->source_id)->first();
            if($assessment) {
                $event = $assessment->events()->first();
            }
        }

        if($view_path == 'certificates.wob') {
            $video = Video::with('recordings', 'recordings.pricing', 'recordings.pricing.event', 'recordings.pricing.event.categories')->where('id', $cpd->certificate->source_id)->first();
            if($video) {
                if(count($video->recordings)) {
                    foreach($video->recordings as $recording) {
                        if($recording->pricing) {
                            $event = $recording->pricing->event;
                        }
                    }                                                        
                }
            }
        }
        $this->updateCpdData($cpd, $event);
    }

    public function updateCpdData($cpd, $event) {
        if($event) {
            $category = $event->categories()->first();
            if($category) {
                $cpd->category = $category->title;
            }

            if($event->reference_id) {
                if(env('APP_THEME') == 'taxfaculty'){
                    $cpd->service_provider = "SA Accounting Academy";
                } else {
                    $cpd->service_provider = "Tax Faculty";
                } 
            }
        }
    }
    /**
     * Store a newly created cpd record in storage.
     *
     * @param AddCpdsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCpdsRequest $request)
    {
        $attachment = $this->CheckforFileUploads($request);

        $user = auth()->user();

        $user->cpds()->create([
            'date' => Carbon::parse($request->dateFormatted),
            'hours' => $request->hours,
            'source' => $request->source,
            'service_provider' => $request->service_provider,
            'attachment' => $attachment,
            'category' => $request->category,
            'verifiable' => $attachment ? $request->verifiable : false,
        ]);

        alert()->success('CPD allocation completed', 'Success');
        return back();
    }

    public function update(AddCpdsRequest $request, $cpdId)
    {
        $cpd = Cpd::find($cpdId);
        $attachment = $this->CheckforFileUploads($request);

        try{
            $cpd->update([
                'date' => Carbon::parse($request->dateFormatted),
                'hours' => $request->hours,
                'source' => $request->source,
                'service_provider' => $request->service_provider,
                'attachment' => $attachment ? $attachment : $cpd->attachment,
                'category' => $request->category,
                'verifiable' => ($cpd->attachment || $attachment) ? $request->verifiable : false,
            ]);
            alert()->success('Entry updated successfully', 'Success');

        }catch (Exception $exception){
            alert()->error('Something went wrong', 'Warning');
            return $exception;
        }
        return back();
    }

    public function destroy(Request $request, $cpdId)
    {
        $cpd = Cpd::find($cpdId);
        $activities = Activity::where('subject_id', $cpdId)->get();

        $note = new Note([
            'type' => 'general',
            'logged_by' => ucwords(strtolower(auth()->user()->first_name.' '.auth()->user()->last_name)),
            'description' => 'I have removed the CPD entry "'.$cpd->source.' - '.date_format($cpd->date, 'd F Y').'"'
        ]);

        $cpd->user->notes()->save($note);

       try{
           foreach ($activities as $activity){
               $cpd->delete();
               if ($activity){
                   $activity->delete();
               }
           }
           alert()->success('Your entry has been deleted', 'Success!');
       }catch (Exception $exception){
           alert()->error('Something went wrong', 'Warning!');
       }
        return back();
    }

    /**
     * @param AddCpdsRequest $request
     * @return null|string
     */
    public function CheckforFileUploads(AddCpdsRequest $request)
    {
        $attachment = null;

        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $filePath = '/uploads/cpds/';
            $fileName = time() . '-' . str_random(20) . '.' . $request->file('attachment')->getClientOriginalExtension();
            $request->file('attachment')->move(public_path() . $filePath, $fileName);
            $attachment = $filePath . $fileName;
        }
        return $attachment;
    }

    public function print($cpds)
    {
        // $cpds = $this->userRepository->find(auth()->user()->id)->cpds();
        // if($request->from_year){
        //     $cpds = $cpds->where(DB::raw('year(date)'),'>=',$request->from_year); 
        // }
        // if($request->to_year){
        //      $cpds = $cpds->where(DB::raw('year(date)'),'<=',$request->to_year); 
        // }
        $cpds = $cpds->get();
        $cpds_total_hours = $cpds->sum('hours');
        // return view('dashboard.cpd_view', compact('cpds','cpds_total_hours')); 
        $pdf = App::make('snappy.pdf.wrapper')->setOrientation('landscape');
        $pdf->loadView('dashboard.cpd_view',compact('cpds','cpds_total_hours'));
        return $pdf->download('CPD logbook -' .auth()->user()->name.'.pdf');
    }
    public function exportCPD($cpds)
    {
        $cpds = $cpds->get();
        Excel::create('cpds from'.request()->from_year.' - '.request()->to_year, function ($excel) use ($cpds) {
            $excel->sheet('sheet', function ($sheet) use ($cpds) {
                $sheet->appendRow([
                    'Employee',
                    'Date',
                    'Service Provider',
                    'Category',
                    'CPD Source',
                    'CPD Hours',
                    'Verifiable',
                ]);

                foreach ($cpds as $cpd) {
                    $sheet->appendRow([
                        $cpd->user->name,
                        ($cpd->date)?$cpd->date->toFormattedDateString():"",
                        ($cpd->service_provider=="")?env('APP_NAME'):$cpd->service_provider,
                        ($cpd->category) ? ucfirst(str_replace('_', ' ', $cpd->category)): "-",
                        $cpd->source,
                        ($cpd->hours > 1) ? $cpd->hours.' Hours' : $cpd->hours.' Hour',
                        ($cpd->certificate)?'Yes':'No'
                    ]);
                }
            });
        })->export('xls');
    }
    
    public function emailCPD($cpds){

        try {
        if(sendMailOrNot(auth()->user(), 'cpd_report')) {
        $cpds = $cpds->get();
        $cpds_total_hours = $cpds->sum('hours');
        $pdf = App::make('snappy.pdf.wrapper')->setOrientation('landscape');
        $pdf->loadView('dashboard.cpd_view',compact('cpds','cpds_total_hours'));
        $pdf->save(public_path('assets/frontend/statements/CPD logbook -' .auth()->user()->name.'.pdf'));
        $location = public_path('assets/frontend/statements/CPD logbook -' .auth()->user()->name.'.pdf');

        // $email_to = 'rohit.shah@mailinator.com';
        $emailData = [];
        $emailData['to']=  auth()->user()->email;
        $emailData['to_name']=auth()->user()->name;
        $emailData['user']=auth()->user()->name;
        $emailData['attachment']=$location;
        // $emailData['template']=config('email_template.cpd_report');
        // send_mail($emailData);
        
        Mail::send('emails.cpd_report', $emailData, function($message)use($emailData) {
            $message->from(env('APP_EMAIL'), config('app.name'));
            $message->to($emailData["to"], $emailData["to_name"])
                    ->subject('CPD Logbook');
            $message->attach($emailData['attachment']);
        });

        File::delete($location);
        }
        } catch (\Exception $e) {
            alert()->error('Something went wrong', 'Warning')->persistent('close');
            return back();
        }

        alert()->success('The Email has been sent to the user successfully!', 'Email Sent!');
        return back();      

    }
}
