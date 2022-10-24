<?php

namespace App\Http\Controllers\Admin;

use App\Folder;
use App\Photo;
use File;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Response;
use Validator;

class GalleryUploadController extends Controller
{
    public function upload($slug)
    {
        $folder = Folder::with('photos')->where('slug', $slug)->first();
        $photos = $folder->photos()->orderBy('id', 'desc')->paginate(10);
        return view('admin.gallery.photos.upload', compact('folder', 'photos'));
    }

    public function uploadFiles(Request $request, $slug){

        $folder = Folder::findBySlug($slug);

        $files = $request->file('file');
        foreach ($files as $file){
            $rules = array('file' => 'required|mimes:png,gif,jpeg');
            $validator = Validator::make(array('file'=> $file), $rules);
        }

        $this->uploadImages($files, $folder);

        if($validator->fails()){
            return Response::json(array('error' => true));
        }else{
            return Response::json(array('success' => true));
        }
    }

    /**
     * @param $files
     * @param $folder
     * @return \Exception
     */
    public function uploadImages($files, $folder)
    {
        if (!empty($files)):
            foreach ($files as $file) {
                try{
                    $photo = new Photo([
                        'folder_id' => $folder->id,
                    ]);

                    $photo->save();
                    $filename = str_slug(class_basename($photo) . '_' . 'image' . '_' . $photo->id) . '.' . $file->getClientOriginalExtension();

                    $img = Image::make($file->getRealPath());
                    $img->stream('jpg', 20);

                    Storage::disk('local')->put('public/images/'.$filename, $img, 'public');
                    $photo->url = 'images/'.$filename;
                    $photo->save();
                }catch (\Exception $exception){
                    return $exception;
                }
            }
        endif;
    }

    public function destroy($id)
    {
        $photo = Photo::find($id);

        File::delete(storage_path('app/public/'.$photo->url));
        $photo->delete();

        alert()->success('Your photo has been deleted', 'Success');
        return back();
    }
}
