<?php

namespace App\Http\Controllers;

use App\AppEvents\Event;
use App\AppEvents\EventRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventsApiController extends Controller
{

    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function all_events()
    {
        return $this->eventRepository->getAll();
   }

    public function show_event($slug)
    {
        return $this->eventRepository->findBySlug($slug);
    }
}
