<?php

namespace App\Http\Controllers\Pages;

use App\Http\Requests\ContactFormRequest;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mail;
use App\CourseProcess;
use App\Blog\Category;

class ContactController extends Controller
{
    public function create()
    {
        $supportCategories = Category::select('id', 'title', 'slug', 'faq_type')
            ->where('parent_id', 0)
            ->where('faq_category_id','!=', '0')
            ->where('faq_type','=', 'general')
            ->orderBy('title', 'asc')
            ->get();

        $user = auth()->user();

        return view('pages.contact', compact('supportCategories', 'user'));
    }

    public function store(ContactFormRequest $request)
    {
        Mail::send('emails.contact',
            array(
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'number' => $request->get('number'),
                'department' => $request->get('department'),
                'subject' => $request->get('subject'),
                'body_message' => $request->get('body_message')
            ), function($message)
            {
                $message->from(config('app.email'));
                $message->to(config('app.to_email'), config('app.name'))->subject('Website Contact Form');
            });

        // Get or create new lead
        $user = auth()->user();
        $course_process = CourseProcess::createOrUpdate([
            'first_name' => $request->get('name'),
            'last_name' => '',
            'email' => $request->get('email'),
            'mobile' => $request->get('number'),
            'user_id' => $user?$user->id:0,
            'type'=>'filled_contact_us'
        ]);

        // Add activity for the lead
        $courseData = [];
        $course_process->addActivity('filled_contact_us', $courseData);
        
        alert()->success('We will contact you shortly.', 'Thank You')->persistent('Close');
        return \Redirect::route('contact');

    }
}
