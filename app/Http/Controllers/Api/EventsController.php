<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\AppEvents\EventRepository;
use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    private $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function index()
    {
        $events = $this->eventRepository->getUpcomingUnpaginated();
        return $events;
    }
}
