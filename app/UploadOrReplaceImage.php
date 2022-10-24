<?php
/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 2/7/2017
 * Time: 1:30 PM
 */

namespace App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Mockery\Exception;


class UploadOrReplaceImage
{
    /**
     * This functiuon expects the following.
     * $folder = The name of the folder you wish to create or check
     * $prop = The attribute on the model for the image, Example: avatar, thumbnail
     * $object = The actual object.
     * @param $folder
     * @param $prop
     * @param $object
     * @param null $size
     */
    public static function UploadOrReplace($folder, $prop, $object, $size = null)
    {
        if (! $size){
            $size = 200;
        }

        if (Input::file()) {
            $image = Input::file($prop);
            $filename  = str_slug(class_basename($object).'_'.'image'.'_'.$object->id) . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath())->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->stream(); // <-- Key point

            if($folder == 'courses') {
                $random = time();
                $path = 'public/images/'.$folder.'-'.$random.'-'.$filename;

                if($object->$prop) {
                    if(Storage::disk('local')->exists('public/'.$object->$prop)) {
                        Storage::disk('local')->delete('public/'.$object->$prop);
                    }
                }    

                Storage::disk('local')->put($path, $img, 'public');

                $object->$prop = 'images/'.$folder.'-'.$random.'-'.$filename;
                $object->save();

            }
            else {
                $path = 'public/images/'.$folder.'-'.$filename;
                Storage::disk('local')->put($path, $img, 'public');

                $object->$prop = 'images/'.$folder.'-'.$filename;
                $object->save();
            }
        }
    }
}