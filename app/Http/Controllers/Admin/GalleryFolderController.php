<?php

namespace App\Http\Controllers\Admin;

use App\Folder;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class GalleryFolderController extends Controller
{

    public function index()
    {
        $folders = Folder::with('photos')->orderBy('date', 'desc')->paginate(10);
        return view('admin.gallery.folders.index', compact('folders'));
    }

    public function create()
    {
        return view('admin.gallery.folders.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required:max:40',
            'date' => 'required:date'
        ]);

        Folder::create($request->only('title', 'date', 'type'));
        alert()->success('Your folder has been created', 'Success!');
        return redirect()->route('admin.folders.index');
    }

    public function edit($slug)
    {
        $folder = Folder::findBySlug($slug);
        return view('admin.gallery.folders.edit', compact('folder'));
    }

    public function update(Request $request, $slug)
    {
        $folder = Folder::findBySlug($slug);
        $folder->slug = null;

        $folder->update($request->all());

        alert()->success('Your folder has been updated!', 'Success!');
        return redirect()->route('admin.folders.edit', $folder->fresh()->slug);
    }

    public function destroy($slug)
    {
        $folder = Folder::findBySlug($slug);
        $folder->delete();

        alert()->success('Your Folder has been deleted', 'Success');
        return back();
    }
}
