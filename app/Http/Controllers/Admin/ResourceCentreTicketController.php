<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\DatatableRepository\DatatableRepository;
use App\SupportTicket;
use App\Thread;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AgentGroup;
use App\Users\User;
use App\Handesk;
use App\HandeskApi;
use App\Blog\Category;

class ResourceCentreTicketController extends Controller
{
    private $datatableRepository;
    public function __construct(DatatableRepository $datatableRepository)
    {
        $this->datatableRepository = $datatableRepository;
    }

    public function index(Request $request)
    {
        $searchString = $request->getQueryString();
        $agentGroups = AgentGroup::all()->pluck('name', 'id')->toArray();
        $agents = User::whereHas('roles', function($q) {})
            ->orderBy('first_name', 'asc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $categories = Category::where('parent_id', 0)
            ->where('faq_category_id','!=', '0')
            ->orderBy('title', 'asc')
            ->get()
            ->pluck('title', 'id')
            ->toArray();

        return view('admin.resource_centre.tickets.index', compact('ticketStatuses', 'searchString', 'agentGroups','agents', 'categories'));
    }

    public function get_threads()
    {
        return $this->datatableRepository->support_tickets();
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $thread = Thread::find($id);
        $data = $thread->tickets();
        $tickets = $data->orderBy('created_at', 'desc')->paginate(5);

        $agentGroups = AgentGroup::all()->pluck('name', 'id')->toArray();
        $agents = User::whereHas('roles', function($q) {})
            ->orderBy('first_name', 'asc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $categories = Category::where('parent_id', 0)
            ->where('faq_category_id','!=', '0')
            ->orderBy('title', 'asc')
            ->get()
            ->pluck('title', 'id')
            ->toArray();

        return view('admin.resource_centre.threads.show', compact('thread', 'tickets', 'agentGroups', 'agents', 'categories'));
    }

    public function assign(Request $request, $id)
    {
        $response = [
            'status'=>false
        ];
        $thread = Thread::find($id);
        $thread->agent_group_id = $request->agent_group_id;
        $thread->agent_id = $request->agent_id;
        $thread->save();
        $response['status'] = true;
        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response = [
            'status'=>false
        ];
        if($request->thread_id) {

            // Update in current system
            $old_category_id = '';
            $old_category_title = "";
            $handeskApi = new HandeskApi();

            $thread = Thread::find($request->thread_id);
            $thread->priority = $request->priority;
            $thread->status = $request->status;
            $thread->agent_group_id = $request->agent_group_id;
            $thread->agent_id = $request->agent_id;

            // Note old category id
            $old_category_id = $thread->category;

            $thread->category = $request->category;
            $thread->save();

            // Update in handesk
            if($thread->handesk_ticket_id) {

                // Find handesk team id
                $handesk_user_id = 0;
                $handesk_team_id = 0;
                $category_title = '';

                
                if($old_category_id != $request->category) {

                    if($request->category) {
                        $category = Category::find($request->category);
                        if($category) {
                            $category_title = $category->title;
                        }
                    }

                    if($old_category_id) {
                        $category = Category::find($old_category_id);
                        if($category) {
                            $old_category_title = $category->title;
                        }
                    }
                }

                if($request->agent_group_id) {
                    $agentGroup = AgentGroup::find($request->agent_group_id);
                    if($agentGroup) {
                        $handesk_team_id = $agentGroup->handesk_team_id;
                    }
                }

                if($request->agent_id) {
                    $agent = User::find($request->agent_id);
                    if($agent) {
                        // Find or create handesk user
                        $user = Handesk\User::findOrCreate([
                            'name' => ucwords($agent->first_name.' '.$agent->last_name),
                            'email' => $agent->email
                        ]);

                        $handesk_user_id = $user->id;
                    }
                }

                if(0) {
                    $handesk_ticket = Handesk\Ticket::find($thread->handesk_ticket_id); 
                    if($handesk_ticket) {
                        $handesk_ticket->fill([
                            'priority' => $request->priority,
                            'status' => convertToHandeskThreadStatus($request->status)
                        ]);
                        $handesk_ticket->update();
                    }
                }
                else {

                    $data = [
                        'priority' => $request->priority,
                        'status' => convertToHandeskThreadStatus($request->status),
                        'agent_id' => $handesk_user_id,
                        'team_id' => $handesk_team_id
                    ];

                    if($category_title) {
                        $data['add_tags'] = [$category_title];
                    }

                    if($old_category_title) {
                        $data['remove_tags'] = [$old_category_title];
                    }

                    $handeskApi->update($thread->handesk_ticket_id, $data);

                }

            }

            $response['status'] = true;
        }
        return response()->json($response);
    }
}
