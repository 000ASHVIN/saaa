<?php

namespace App\Http\Controllers\ResourceCentre;

use App\Act;
use App\ActList;
use App\Freshdesk;
use App\Jobs\SendTicketToFreshDesk;
use App\SupportTicket;
use App\Thread;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LegislationController extends Controller
{

    public function index()
    {
        $column = 'name';
        if(env('APP_THEME') == 'taxfaculty'){
            $column = 'title';
        }
        $acts = ActList::orderBy($column,'asc')->get();
        $threads = Thread::where('open_to_public', true)->orderBy('id', 'desc');
        return view('resource_centre.legislation.index', compact('threads', 'acts'));
    }

    public function search(Request $request)
    {
        $search = $request['search'];
        $acts = ActList::search($request['search'], null, true)->paginate(10);
        alert()->success('We have found acts matching your search criteria', 'Success!');
        return view('resource_centre.legislation.search_result', compact('acts', 'search'));
    }

    public function opinion()
    {
        $tickets = Freshdesk::list_tickets_filter("type:'Open To Public'");
        return view('resource_centre.legislation.opinion', compact('tickets'));
    }

    public function create()
    {
        return view('resource_centre.legislation.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'tag' => 'required',
            'description' => 'required',
        ]);

        $content = [
            'source' => 1,
            'status' => 2,
            'priority' => 3,
            'group_id' => env('FreshGroupTechnicalId'),
            'type' => 'Open To Public',
            'tags' => [$request['tag']],
            'subject' => 'Get an opinion - '.$request['subject'],
            'email' => auth()->user()->email,
            'phone' => (auth()->user()->cell ? : ""),
            'description' => $request['description']
        ];

        $this->dispatch((new SendTicketToFreshDesk($content)));
        alert()->success('Your conversation has been created and will show in 2-3 minutes', 'Success!')->persistent('close');
        return redirect()->route('resource_centre.legislation.opinion');
    }

    public function show($ticketId)
    {
        $ticket = Freshdesk::show_ticket($ticketId);
        $replies = Freshdesk::list_ticket_replies($ticketId);
        return view('resource_centre.legislation.show', compact('replies', 'ticket'));
    }
}
