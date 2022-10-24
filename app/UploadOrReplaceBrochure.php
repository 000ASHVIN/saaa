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
use Mockery\Exception;


class UploadOrReplaceBrochure
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
        if($size == 'full'){
            $size=null;
        }
        if (Input::file()) {
            $brochure = Input::file($prop);
            $filename  = str_slug($folder.'-'.class_basename($object).'_'.'brochure'.'_'.$object->id) . '.' . $brochure->getClientOriginalExtension();

            Storage::disk('local')->put('public/brochure/'.$filename, File::get($brochure->getRealPath()), 'public');
            // $destinationPath = public_path()."/storage/brochure";
            // $brochure->move($destinationPath, $filename);

            $object->$prop = 'brochure/'.$filename;
            $object->save();
        }
    }
}