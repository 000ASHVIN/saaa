<?php

namespace App\Http\Controllers;

use App\LiveWebinar;
use App\Repositories\LiveWebinars\LiveWebinarRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LiveWebinarController extends Controller
{

    private $liveWebinarRepositorye;

    public function __construct(LiveWebinarRepository $liveWebinarRepositorye)
    {
        $this->liveWebinarRepositorye = $liveWebinarRepositorye;
    }

    public function show($liveWebinar)
    {
        $tickets = auth()->user()->tickets;
        $webinar = $this->liveWebinarRepositorye->show($liveWebinar);
        $ticket = $tickets->where('event_id', $webinar->events->first()->id);
        return $this->checkTicketStatus($ticket, $webinar);
    }

    /**
     * @param $ticket
     * @param $webinar
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function checkTicketStatus($ticket, $webinar)
    {
        if (count($ticket) && $ticket->first()->description === 'Access to the webinar / recording') {
            $invoice = $ticket->first()->invoice;
            if ($invoice && $invoice->paid == true && $invoice->status == 'paid') {
                return view('webinars.index', compact('webinar'));

            } elseif (count($invoice) == null) {
                return view('webinars.index', compact('webinar'));

            } else {
                alert()->warning('Please check your invoice before trying again', 'Warning');
                return back();
            }
        }else {
            alert()->error('You do not have access to this webinar recording', 'Ticket Required!');
            return back();
        }
    }
}
