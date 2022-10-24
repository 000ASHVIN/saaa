<?php

namespace App\Http\Controllers\Dashboard;

use App\Certificate;
use App\Users\Cpd;
use Illuminate\Http\Request;
use Knp\Snappy\Pdf;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ActivityLog;
use App\AppEvents\Ticket;
use App\Assessment;

class CertificatesController extends Controller
{
    public function show($cpdId)
    {
        $cpd = Cpd::with(['user', 'certificate'])->findOrFail($cpdId);
        $cpd->certificate->view_path = $this->changeIfNotSystemEvent($cpd);
        return view('certificates.certificate', compact('cpd'));
    }

    public function download($cpdId)
    {
        $video_id = [607,608,609,610,611,612,613,614,667,668];
        $cpd = Cpd::with(['user', 'certificate'])->findOrFail($cpdId);
        if($cpd->hasCertificate()){
            $certificate = $cpd;
            $ActivityLog = ActivityLog::create([
                'user_id'=> (auth()->check())?auth()->user()->id:0,
                'model'=> get_class($certificate),
                'model_id'=>$certificate->id,
                'action_by'=> 'manually',
                'action'=> "download",
                'data'=> json_encode(request()->all()),
                'request_url'=> request()->path()
            ]);
        }
        $cpd->certificate->view_path = $this->changeIfNotSystemEvent($cpd);
        
        $viewpath = $cpd->certificate->view_path;
        if(env('APP_THEME') == 'taxfaculty'){
            if($cpd->certificate->view_path == 'certificates.wob' && $cpd->certificate->source && in_array($cpd->certificate->source->id,$video_id))
            {
             $viewpath= "certificates.saaawod";   
            }
        }

        $snappy = app('snappy.pdf.wrapper');
        $pdf = true;
        $pdf = $snappy->loadView($viewpath, compact('pdf', 'cpd'));
        return $pdf->download('certificate.pdf');
    }

    public function changeIfNotSystemEvent($cpd)
    {
        $view_path = $cpd->certificate->view_path;

        if($view_path == 'certificates.attendance') {
            $ticket = Ticket::find($cpd->certificate->source_id);
            if($ticket) {
                $event = $ticket->event;
                if($event && $event->reference_id) {
                    if(env('APP_THEME') == 'taxfaculty'){
                        $view_path = 'certificates.saaa.attendance';
                    } else {
                        $view_path = 'certificates.ttf.attendance';
                    }
                    
                }
            }
        }

        if($view_path == 'certificates.assessment') {
            $assessment = Assessment::find($cpd->certificate->source_id);
            $event = $assessment->events()->first();
            if($event && $event->reference_id) {
                if(env('APP_THEME') == 'taxfaculty'){
                    $view_path = 'certificates.saaa.assessment';
                } else {
                    $view_path = 'certificates.ttf.assessment';
                }
                
            }
        }

        if($view_path == 'certificates.wob') {
            if($cpd->certificate->source) {
                $video = $cpd->certificate->source;
                
                if($video) {
                    if(count($video->recordings)) {
                        foreach($video->recordings as $recording) {
                            if($recording->pricing && $recording->pricing->event) {
                                $event = $recording->pricing->event;
                                if($event->reference_id) {
                                    if(env('APP_THEME') == 'taxfaculty'){
                                        $view_path = 'certificates.saaa.wob';
                                    } else {
                                        $view_path = 'certificates.ttf.wob';
                                    }
                                } 
                            }
                        }                                                        
                    }
                }
            }
        }

        return $view_path;
    }
}
