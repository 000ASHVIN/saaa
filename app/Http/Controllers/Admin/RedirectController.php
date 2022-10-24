<?php

namespace App\Http\Controllers\Admin;

use App\Redirect;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RedirectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $redirect = Redirect::paginate(8);
        return view('admin.seo.redirect.index',compact('redirect'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $redirect = New Redirect();
        return view('admin.seo.redirect.redirect',compact('redirect'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $redirect = Redirect::create($request->except('_token'));
        alert()->success('We have created redirect', 'Thank you');
        return redirect()->route('admin.redirect.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $redirect = Redirect::find($id);
        return view('admin.seo.redirect.edit',compact('redirect'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
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
        $data = $request->all();
        $redirect = Redirect::find($id)->update($request->except('_token'));
        alert()->success('We have updated redirect', 'Thank you');
        return redirect()->route('admin.redirect.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $redirect = Redirect::find($id);
        $redirect->delete();
        alert()->error('We have removed redirect rule', 'Removed');
        return redirect()->route('admin.redirect.index');
    }
}
