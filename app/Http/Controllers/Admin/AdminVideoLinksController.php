<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\Dropbox\DropboxRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

use App\Video;
use App\Link;

class AdminVideoLinksController extends Controller
{
    private $dropboxRepository;

    public function __construct(DropboxRepository $dropboxRepository) {

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
    public function store(Request $request, $video_id)
    {
        $validator = $this->FileCountValidation();

        if ($validator->fails()) {
           alert()->error('Please select 5 files or less', 'Too Many Files')->persistent('close');
            return back()->withInput(['tab' => 'files']);
        }

        $MainFolder = 'Video_Files_API/';
        $subfolder = $video_id;

        $video = Video::findOrFail($video_id);

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
                $video->links()->save($link);
            }

        }else{
            $link = Link::create([
                'name' => $request->name,
                'url' =>  $request->url,
                'instructions' => $request->instructions,
                'secret' => $request->secret
            ]);
            $video->links()->save($link);
        }

        alert()->success('Your link has been created', 'Success!');
        return back()->withInput(['tab' => 'files']);
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
    public function update(Request $request, $id)
    {
        $link = Link::find($id);
        $link->update($request->except(['files', '_token']));

        alert()->success('Your changes has been saved!', 'Success!');
        return back()->withInput(['tab' => 'files']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($video, $link)
    {
        $video = Video::findOrFail($video);
        $video->links()->detach($link);

        alert()->success('Link deleted!', 'Success!');
        return back()->withInput(['tab' => 'files']);
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
