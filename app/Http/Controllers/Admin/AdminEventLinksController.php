<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\EventRepository;
use App\Link;
use App\Repositories\Dropbox\DropboxRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SyncEvent;
use Illuminate\Support\Facades\Input;

class AdminEventLinksController extends Controller
{
    private $eventRepository;
    private $dropboxRepository;

    public function __construct(EventRepository $eventRepository, DropboxRepository $dropboxRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->dropboxRepository = $dropboxRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request, $event)
    {
        $validator = $this->FileCountValidation();

        if ($validator->fails()) {
           alert()->error('Please select 5 files or less', 'Too Many Files')->persistent('close');
            return back()->withInput(['tab' => 'files']);
        }

        $MainFolder = 'Events_Files_2018_API/';
        $subfolder = $event;

        $event = $this->eventRepository->findBySlug($event);

        if($request->hasFile('files')){
            $files = $request->file('files');
            foreach ($files as $file){
                $data = $this->dropboxRepository->upload($file, $MainFolder, $subfolder);

                $link = Link::create([
                    'name' => $data['name'],
                    'url' => $data['link'],
                    'instructions' => $request->instructions,
                    'secret' => $request->secret
                ]);
                $event->links()->save($link);
            }
        }else{
            $link = Link::create([
                'name' => $request->name,
                'url' =>  $request->url,
                'instructions' => $request->instructions,
                'secret' => $request->secret
            ]);
            $event->links()->save($link);
        }

        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);

        alert()->success('Your link has been created', 'Success!');
        return back()->withInput(['tab' => 'files']);
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
        $link = Link::find($id);
        $link->update($request->except(['files', '_token']));

        $event = $link->events->first();
        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);
        alert()->success('Your changes has been saved!', 'Success!');
        return back()->withInput(['tab' => 'files']);
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

    /**
     * @return \Illuminate\Validation\Validator
     */
    public function FileCountValidation()
    {
        $messages = array(
            'upload_count' => 'The :attribute field cannot be more than 5.',
        );

        $validator = \Validator::make(
            Input::all(),
            array('files' => array('upload_count:files,5')),
            $messages
        );
        return $validator;
    }
}
