<?php

namespace App\Http\Controllers\Newsletters;

use App\Http\Requests;
use App\Repositories\Sendinblue\SendingblueRepository;
use Illuminate\Http\Request;
use App\Newsletters\Subscriber;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewsLetterRequest;
use App\CourseProcess;

class NewslettersController extends Controller
{
    private $sendingblueRepository;
    public function __construct(SendingblueRepository $sendingblueRepository)
    {
        $this->sendingblueRepository = $sendingblueRepository;
    }

    public function store(NewsLetterRequest $request)
    {
        $request->merge(['cell'=>$request->full_number]);
        
        $subscriber = Subscriber::create($request->only(['email', 'first_name', 'last_name','cell']));
        if(config('app.theme')=='taxfaculty'){
            $this->sendingblueRepository->createSubscriber($subscriber, $listIds = [47]);
        }else{
            $this->sendingblueRepository->createSubscriber($subscriber, $listIds = [9]);
        }

        // Get or create new lead
        $user = auth()->user();
        $course_process = CourseProcess::createOrUpdate([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'mobile' => $request->cell,
            'user_id' => $user?$user->id:0,
            'course_id' => 0,
            'type'=>'subscribed_newsletter'
        ]);

        // Add activity for the lead
        $courseData = [];
        $course_process->addActivity('subscribed_newsletter', $courseData);
        return $subscriber->email;
}

    public function getSubscribers()
    {
        $subscribers = Subscriber::all();
        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function destroy($id)
    {
        Subscriber::findorFail($id)->delete();
        alert()->success('Subscriber was removed!', 'Success!');
        return redirect()->back();
    }
}
