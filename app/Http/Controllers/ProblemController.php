<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProblemController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'body_message' => 'required'
        ]);

        Mail::send(
            'emails.report_problem',
            ['email' => $request->get('email'),
                'body_message' => $request->get('body_message')
            ],
            function ($message) {
                $message->from(config('app.email'));
                $message->to(config('app.email'), config('app.name'))->subject('Website Error');
            }
        );

        alert()->success('Thanks for reporting that error!', 'Error Reported')->persistent('Close');
        return redirect()->route('home');
    }
}
