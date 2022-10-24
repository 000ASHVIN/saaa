<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\EventRepository;
use App\AppEvents\Extra;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SyncEvent;

class EventExtraController extends Controller
{
    private $eventRepository;
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function store(Request $request, $event)
    {
        $event = $this->eventRepository->findBySlug($event);

        $extra = new Extra($request->all());
        $event->extras()->save($extra);

        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);
        alert()->success('Your extra has been saved', 'Success!');
        return back()->withInput(['tab' => 'extra']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $event, $id)
    {
        $extra = Extra::find($id);
        $extra->update($request->all());

        $event = $this->eventRepository->findBySlug($event);
        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);

        alert()->success('Your extra has been updated!', 'Success!');
        return back()->withInput(['tab' => 'extra']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $slug
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($event, $id)
    {
        $extra = Extra::find($id);
        $extra->delete();

        $event = $this->eventRepository->findBySlug($event);
        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);
        alert()->success('Your extra has been deleted!', 'Success!');
        return back()->withInput(['tab' => 'extra']);
    }
}
