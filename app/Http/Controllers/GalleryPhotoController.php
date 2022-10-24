<?php

namespace App\Http\Controllers\Admin;

use App\Folder;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GalleryPhotoController extends Controller
{
    public function upload()
    {
        return view('admin.gallery.photos.upload');
    }

    public function uploadFiles(Request $request){
        $files = $request->file('file');
        $folder = Folder::first();
        $this->uploadImages($files, $folder);
        return \Response::json(array('success' => true));
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
                    $img->resize(null, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    Storage::disk('local')->put('public/images/'.$filename, $img, 'public');
                    $photo->url = 'images/'.$filename;
                    $photo->save();
                }catch (\Exception $exception){
                    return $exception;
                }
            }
        endif;
    }
}
