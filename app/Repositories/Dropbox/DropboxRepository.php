<?php
/**
 * Created by PhpStorm.
 * User: Tiaan
 * Date: 2018/04/06
 * Time: 09:03
 */

namespace App\Repositories\Dropbox;
use League\Flysystem\Filesystem;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class DropboxRepository
{
    public function upload($file, $MainFolder, $subfolder)
    {
        $fileName = $file->getClientOriginalName();
        Storage::put($fileName, file_get_contents($file));

        $client = new Client('cqreu8Ha6Z4AAAAAAABlOhmxTWVFO9Xwi8ZlnPzs7q7zGbHFoiEfmI3rwKtzAQen');
        $adapter = new DropboxAdapter($client);
        $filesystem = new Filesystem($adapter);

        $folder = $MainFolder.str_replace('_', '', $subfolder);

        if (! $filesystem->has($folder)){
            $filesystem->createDir($folder);
            $link = $this->saveToDropbox($subfolder, $fileName, $client, $MainFolder);
        }else{
            $link = $this->saveToDropbox($subfolder, $fileName, $client, $MainFolder);
        }
        Storage::delete($fileName);
        return ['link' => $link, 'name' => $fileName];
    }

    public function saveToDropbox($subfolder, $fileName, $client, $MainFolder)
    {
        $file = Storage::get($fileName);
        $dropBoxFileName = '/'.$MainFolder. str_replace('_', ' ', $subfolder) . '/' . $fileName;

        $client->upload($dropBoxFileName, $file);

        if (! $client->listSharedLinks($dropBoxFileName)){
            $link = str_replace('dl=0', 'dl=1', $client->createSharedLinkWithSettings($dropBoxFileName, ['requested_visibility' => 'public'])['url']);
        }else{
            $link = str_replace('dl=0', 'dl=1', $client->listSharedLinks($dropBoxFileName)[0]['url']);
        }
        return $link;
    }
}