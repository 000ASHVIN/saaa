<?php

namespace App\Http\Controllers;

use App\Freshdesk;
use App\Jobs\SendGetAnOpinionJobToFreshDesk;
use App\SupportTicket;
use App\Thread;
use Illuminate\Http\Request;
use App\Users\User;
use App\Blog\Category;

use App\AgentGroup;
use App\Handesk;
use App\HandeskApi;

class SupportTicketController extends Controller
{
    public function create()
    {
        $threads = collect();
        auth()->user()->support_tickets->filter(function ($ticket) use ($threads) {
            if (!$threads->contains('id', $ticket->thread->id)) {
                $threads->push($ticket->thread);
            }
        });

        $categories = Category::select('id', 'title', 'slug', 'faq_type')
            ->where('parent_id', 0)
            ->where('faq_category_id','=', '1')
            ->orderBy('title', 'asc')
            ->get();

        return view('tickets.create', compact('threads', 'categories'));
    }

    public function store(Request $request)
    {
        // Validations
        $user = auth()->user();
        $validation = [
            'subject' => 'required',
            'tag' => 'required',
            'description' => 'required',
        ];

        if(!$user) {
            $validation['support_email']= 'required';
            $validation['mobile']= 'required';
        }
        $this->validate($request, $validation);

        // Find user from email if not logged in
        $mobile_number = null;
        $is_login = 1;
        if(!$user) {
            $email = trim($request->support_email);
            $user = User::where('email',$email)->first();

            $is_login = 0;
            $mobile_number = trim($request->full_number);
        }

        // Find agent group from category
        $agent_group_id = 0;
        $handesk_team_id = 0;
        if($request->tag) {

            $agentGroup = AgentGroup::whereHas('categories', function($q) use($request) {
                $q->where('categories.id', $request->tag);
            })->first();

            if($agentGroup) {
                $agent_group_id = $agentGroup->id;
                $handesk_team_id = $agentGroup->handesk_team_id;
            }

        }

        // Create ticket in handesk
        if($user) {

            $requester = [
                "name" => $user->name, 
                "email" => $user->email
            ];

            // Find category from id
            $tags = [];
            $category_title = '';
            $category_id = 0;
            if(!empty($request->tag)) {
                $category = Category::find($request->tag);
                if($category) {
                    $tags[] = $category->title;
                    $category_title = $category->title;
                    $category_id = $category->id;
                }
            }

            $handeskApi = new HandeskApi();
            $handesk_ticket_id = $handeskApi->createAndNotify($requester, $request->subject, $request->description, $tags, $handesk_team_id);
            $handesk_ticket_id = ($handesk_ticket_id)?$handesk_ticket_id:0;

            $thread = Thread::create([
                'title' => $request['subject'], 
                'category' => $category_id, 
                'description' => $request['description'],
                'handesk_ticket_id' => $handesk_ticket_id,
                'user_id' => $user->id,
                'priority' => 2,
                'is_login' => $is_login,
                'mobile_number' => $mobile_number,
                'agent_group_id' => $agent_group_id
            ]);

            alert()->success('Your Ticket has been created', 'Success!');
            if($is_login) {
                return redirect()->route('dashboard.support_tickets');
            }
            else {
                return redirect()->back();
            }
        }
        else {
            alert()->error('Sorry, can not find any user with provided email.', 'Error!');
            return redirect()->back();
        }
    }

    public function update(Request $request)
    {
        // Validations
        $validation = [
            'thread_id' => 'required',
            'subject' => 'required',
            'description' => 'required',
        ];
        $this->validate($request, $validation);

        $user = auth()->user();
        $thread = Thread::where('id', $request->thread_id)
            ->where('user_id', $user->id)
            ->first();

        if($thread) {
            
            if($thread->handesk_ticket_id) {
                $handesk_ticket = Handesk\Ticket::find($thread->handesk_ticket_id);
                if($handesk_ticket) {
                    $handesk_ticket->update([
                        'title' => $request['subject'], 
                        'body' => $request['description']
                    ]);
                }
            }
        
            $thread->update([
                'title' => $request['subject'], 
                'description' => $request['description']
            ]);

            alert()->success('Your Ticket has been updated.', 'Success!');
            return redirect()->back();
        }
        else {
            alert()->error('Thread does not exists.', 'Error!');
            return redirect()->back();
        }

    }

    public function show($id)
    {
        $thread = Thread::where('id',$id)
            ->where('user_id',auth()->user()->id)
            ->with('tickets')->first();
        if($thread) {
            $thread->update(['replies' => '0']);
            return view('tickets.show', compact('thread'));
        }
        else {
            return redirect()->route('dashboard');
        }
    }

    public function reply(Request $request, $id) {

        $this->validate($request, [
            'description' => 'required'
        ]);

        $current_user = auth()->user();
        $thread = Thread::where('id',$id)
            ->where('user_id',$current_user->id)
            ->with('tickets')->first();

        if($thread) {
            
            // Create comment in handesk
            $handeskApi = new HandeskApi();
            $handesk_comment_id = 0;

            // Add comment in handesk
            if($thread->handesk_ticket_id) {

                $handesk_ticket = Handesk\Ticket::find($thread->handesk_ticket_id); 
                if($handesk_ticket) {
                    $handesk_comment_id = $handeskApi->addComment($handesk_ticket->id, $request['description']);
                }
            
            }
                
            // Add comment in website
            $ticket = SupportTicket::create([
                'subject' => $thread->title,
                'description' => $request['description'],
                'handesk_comment_id' => $handesk_comment_id,
                'agent_id' => '0',
                'thread_id' => $thread->id,
                'user_id' => $current_user->id
            ]);
            $ticket->save();
            
            return redirect()->back();
        }
        else {
            return redirect()->route('dashboard');
        }

    }

}
