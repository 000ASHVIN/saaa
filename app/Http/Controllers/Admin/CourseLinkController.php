<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Link;
use App\Models\Course;
use App\Repositories\DatatableRepository\DatatableRepository;
use App\Repositories\Dropbox\DropboxRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;

class CourseLinkController extends Controller
{
    private $dropboxRepository;

    public function __construct(DropboxRepository $dropboxRepository)
    {
        $this->dropboxRepository = $dropboxRepository;
    }

    public function store(Request $request, $courseId)
    {
        $validator = $this->FileCountValidation();

        if ($validator->fails()) {
            alert()->error('Please select 5 files or less', 'Too Many Files')->persistent('close');
            return back()->withInput(['tab' => 'files']);
        }

        $course = Course::find($courseId);

        $MainFolder = 'Events_Files_2018_API/';
        $subfolder = $course->reference;


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
                $course->links()->save($link);
            }
        }else{
            $link = Link::create([
                'name' => $request->name,
                'url' =>  $request->url,
                'instructions' => $request->instructions,
                'secret' => $request->secret
            ]);
            $course->links()->save($link);
        }

        alert()->success('Your link has been created', 'Success!');
        return back()->withInput(['tab' => 'files']);
    }

    public function FileCountValidation()
    {
        $messages = array(
            'upload_count' => 'The :attribute field cannot be more than 5.',
        );

        $validator = Validator::make(
            Input::all(),
            array('files' => array('upload_count:files,5')),
            $messages
        );
        return $validator;
    }

    public function update(Request $request, $id)
    {
        $link = Link::find($id);
        $link->update($request->except(['files', '_token']));

        alert()->success('Your changes has been saved!', 'Success!');
        return back()->withInput(['tab' => 'links']);
    }
}
