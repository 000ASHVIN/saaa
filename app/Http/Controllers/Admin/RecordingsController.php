<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\Jobs\AssignVideoToUser;
use App\Recording;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SyncEvent;

class RecordingsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Requests\CreateUpdateRecordingRequest $request
     * @param $videoId
     * @return \Illuminate\Http\Response
     */
    public function create(Requests\CreateUpdateRecordingRequest $request, $videoId)
    {
        $pricings = Pricing::where('event_id', $request->event_id)->get();
        $event = Event::find($request->event_id);

        foreach ($pricings as $pricing){
            try{
                $recording = new Recording();
                $recording->pricing_id = $pricing->id;
                $recording->video_id = $videoId;
                $recording->save();

                $job = (new AssignVideoToUser($event, $recording->video))->onQueue('default');
//                $this->dispatch($job);

            }catch (\Exception $exception){
                return $exception;
            }
        }

        $sync_event = new SyncEvent();
        $sync_event->sync_event($event);
        alert()->success('Recording added.', 'Success');
        return redirect()->back()->withInput(['tab' => 'recordings']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $recording = Recording::find($id);
        $event = null;
        if($recording->pricing && $recording->pricing->event) {
            $event = $recording->pricing->event;
        }
        Recording::destroy($id);
        if($event) {
            $sync_event = new SyncEvent();
            $sync_event->sync_event($event);
        }
        alert()->success('The recording has been deleted', 'Success');
        return redirect()->back()->withInput(['tab' => 'recordings']);
    }
}
