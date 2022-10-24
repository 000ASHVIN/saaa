<?php

namespace App\Http\Controllers;

use App\SupportTicket;
use App\Thread;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Handesk;
use App\HandeskApi;

class SupportTicketReplyController extends Controller
{
    public function store(Request $request, $threadId)
    {
        // Create comment in handesk
        $handeskApi = new HandeskApi();
        $current_user = auth()->user();
        $handesk_comment_id = 0;
        $thread = Thread::find($threadId);

        if($thread->handesk_ticket_id) {

            $handesk_ticket = Handesk\Ticket::find($thread->handesk_ticket_id); 
            if($handesk_ticket) {

                // Find or create handesk user
                $user = Handesk\User::findOrCreate([
                    'name' => ucwords($current_user->first_name.' '.$current_user->last_name),
                    'email' => $current_user->email
                ]);

                // If comment text exists then add comment in handesk
                if(strip_tags($request['description'])) {

                    if($request['chk_notify']) {
                        $handesk_comment_id = $handeskApi->addComment($handesk_ticket->id, $request['description'], $user->id);
                    }
                    else {

                        $handesk_comment = $handesk_ticket->addComment($user, $request['description']);
                        if($handesk_comment) {
                            $handesk_comment_id = $handesk_comment->id;
                        }

                    }
                }

                // Close ticket in handesk
                if($request['close_ticket']) {

                    if($request['chk_notify']) {
                        $handeskApi->updateStatus($handesk_ticket->id, Handesk\Ticket::STATUS_CLOSED);
                    }
                    else {
                        $handesk_ticket->updateStatus(Handesk\Ticket::STATUS_CLOSED);
                    }
                }
            }
        }

        if(strip_tags($request['description'])) {

            $ticket = SupportTicket::create([
                'subject' => $thread->title,
                'description' => $request['description'],
                'handesk_comment_id' => $handesk_comment_id,
                'agent_id' => $current_user->id,
                'thread_id' => $thread->id
            ]);
            $ticket->save();
        }

        // Close ticket in local
        if($request['close_ticket']) {
            $thread->update(['status'=>'closed']);
        }

        if(!$thread->agent_id) {
            $thread->agent_id = $current_user->id;
            $thread->save();
        }
        $thread->addReply();

        alert()->success('You have successfully replied to this thread', 'Success!');
        return back();
    }

    public function destroy($id)
    {
        DB::transaction(function () use($id){
            $ticket = SupportTicket::find($id);
            $ticket->update(['status' => 'closed']);

            $thread = Thread::find($ticket->thread->id);
            if (count($thread->tickets) <= 1){
                $thread->delete();
            }

            $ticket->delete();
        });

        alert()->success('Your comment has been successfully deleted', 'Success');
        return redirect()->route('dashboard.support_tickets');
    }
}
