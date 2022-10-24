<?php

namespace App\Http\Controllers\Admin;

use App\Presenters\Presenter;
use App\UploadOrReplaceImage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminPresenterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $presenters = Presenter::with('events')->sorted()->paginate(10);
        return view('admin.presenters.index', compact('presenters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.presenters.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request)
    {
        \DB::transaction(function () use($request){
            $presenter = Presenter::create($request->all());
            $avatar = UploadOrReplaceImage::UploadOrReplace('presenters', 'avatar', $presenter);
            $presenter->update($request->except('avatar'));
        });
        alert()->success('Your presenter has been saved!', 'Success!');
        return back();
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
        $presenter = Presenter::find($id);
        return view('admin.presenters.show', compact('presenter'));
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
        $presenter = Presenter::find($id);
        $avatar = UploadOrReplaceImage::UploadOrReplace('presenters', 'avatar', $presenter);
        $presenter->update($request->except('avatar'));
        alert()->success('Your changes has been saved!', 'Success!');
        return back();
    }

    // in order for this function to work, it must accept an avatar attribute.

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $presenter = Presenter::find($id);
        $presenter->delete();
        alert()->success('You presenter has been removed', 'Success!');
        return back();
    }
}
