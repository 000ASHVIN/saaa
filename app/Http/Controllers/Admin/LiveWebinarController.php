<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Event;
use App\Presenters\Presenter;
use App\Repositories\LiveWebinars\LiveWebinarRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class LiveWebinarController extends Controller
{
    /**
     * @var LiveWebinarRepository
     */
    private $liveWebinarRepository;

    public function __construct(LiveWebinarRepository $liveWebinarRepository)
    {
        $this->liveWebinarRepository = $liveWebinarRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $webinars = $this->liveWebinarRepository->all();
        return view('admin.live_webinars.index', compact('webinars'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $events = Event::where('is_active', true)->get()->pluck('name', 'id');
        return view('admin.live_webinars.create', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'webinar_url' => 'required|url',
            'date' => 'required',
            'video_url' => 'required|url',
            'time' => 'required',
            'description' => 'required'
        ]);
        $this->liveWebinarRepository->create($request);

        alert()->success('You have created a webinar', 'Success!');
        return redirect()->route('admin.live_webinars.index');
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
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $webinar = $this->liveWebinarRepository->find($slug);
        $events = Event::where('is_active', true)->get()->pluck('name', 'id');
        return view('admin.live_webinars.edit', compact('webinar', 'events'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $webinar = $this->liveWebinarRepository->update($slug, $request);
        alert()->success('Youe webinar has been updated!', 'Succsess!');
        return redirect()->route('admin.live_webinars.edit', $webinar->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $webinar = $this->liveWebinarRepository->find($slug);
        $webinar->delete();
        
        alert()->success('Your webinar has been deleted', 'Success');
        return back();
    }
}
