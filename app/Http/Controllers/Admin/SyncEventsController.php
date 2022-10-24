<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AppEvents\EventRepository;
use App\Body;
use App\Assessment;
use App\Subscriptions\Models\Feature;
use App\AppEvents\Event;
use App\Blog\Category;
use App\Presenters\Presenter;

class SyncEventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function index()
    {
        $events = Event::whereNotNull('reference_id')->orderBy('start_date', 'desc')->paginate(10);

        return view('admin.synced_events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $bodies = Body::all();
        $features = Feature::all();
        $assessments = Assessment::all();
        $event = Event::with('extras', 'venues.dates', 'venues.pricings','pricings.tickets.user')->where('slug',$slug)->first();
        $categories = Category::all()->pluck('title', 'id');
        $presenters = Presenter::all()->pluck('name', 'id');
        return view('admin.synced_events.show', compact('event', 'features', 'assessments', 'bodies', 'categories', 'presenters'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
