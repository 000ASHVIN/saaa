<?php

namespace App\Http\Controllers;

use App\Repositories\SmsRepository\SmsRepository;
use App\SponsorFormSubmission;
use App\Users\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class SponsorController extends Controller
{
    private $smsRepository;

    public function __construct(SmsRepository $smsRepository)
    {
        $this->smsRepository = $smsRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sponsors.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'contact_number' => 'required',
        ]);

        $submission = SponsorFormSubmission::create($request->except('_token'));

        if ($request['product'] == 'Draftworx'){
            $member = User::find(18);
            $data = collect([
                    'Ronel' => [
                        'number' => '0027832867350',
                        'message' => 'New '.ucfirst($request['product']).' application received from '.ucfirst($request['name'])
                    ]
                ]);

            Mail::send('emails.sponsor_submissions.submission', ['submission' => $submission ], function ($m) {
                $m->from(config('app.email'), config('app.name'));
                $m->to(['admin@auxilla.co.za'])->subject('New Sponsor Page Form Submission');
            });

            foreach ($data as $notify){
                $this->smsRepository->sendSms($notify, $member);
            }

        }else{
            $member = User::find(7686);
            $data = collect([
                'Chantal' => [
                    'number' => '27799337749',
                    'message' => 'New '.ucfirst($request['product']).' application received from '.ucfirst($request['name'])
                ],
                'Stiaan' => [
                    'number' => '27827876539',
                    'message' => 'New '.ucfirst($request['product']).' application received from '.ucfirst($request['name'])
                ]
            ]);

            Mail::send('emails.sponsor_submissions.submission', ['submission' => $submission ], function ($m) {
                $m->from(config('app.email'), config('app.name'));
                $m->to(['michael@firstforprofessionals.co.za', 'es.klue@gmail.com'])->subject('New Sponsor Page Form Submission');
            });

            foreach ($data as $notify){
                $this->smsRepository->sendSms($notify, $member);
            }
        }

        alert()->success('Thank you for completing the form', 'Success!');
        return back();
    }
}
