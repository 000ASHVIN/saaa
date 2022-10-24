<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    public function index()
    {
        $folders = Folder::with('photos')->orderBy('date', 'desc')->paginate(6);
        return view('gallery.index', compact('folders'));
    }

    public function show($slug)
    {
        $folder = Folder::with('photos')->where('slug', $slug)->get()->first();
        $photos = $folder->photos()->paginate(12);
        return view('gallery.show', compact('folder', 'photos'));
    }
}
