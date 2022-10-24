<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\EventRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SyncEvent;

class AdminAssessmentController extends Controller
{
    private $eventRepository;
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param Request $request
     * @param $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $event)
    {
        $event = $this->eventRepository->findBySlug($event);
        $event->assessments()->sync($request->assessments? : []);

        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);
        alert()->success('Your Assessment has been saved!', 'Success!');
        return back();
    }
}
