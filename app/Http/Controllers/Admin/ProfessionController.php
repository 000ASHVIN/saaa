<?php

namespace App\Http\Controllers\Admin;

use App\Body;
use App\Profession\Profession;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SponsorList;

class ProfessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professions = Profession::all();
        return view('admin.professions.index', compact('professions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bodies = Body::all()->pluck('title', 'id');
        $sponsors = SponsorList::all()->pluck('name', 'id');
        return view('admin.professions.create', compact('bodies', 'sponsors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $profession = Profession::create($request->except('_token', 'body_list', 'sponsor_list'));
        $profession->bodies()->sync(! $request->body_list ? [] : $request->body_list);
        $profession->sponsors()->sync(!$request->sponsor_list ? [] : $request->sponsor_list);
        alert()->success('Your profession has been saved!', 'Awesome!');
        return redirect()->route('admin.professions.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $bodies = Body::all()->pluck('title', 'id');
        $profession = Profession::where('slug', $slug)->first();
        $sponsors = SponsorList::all()->pluck('name', 'id');
        return view('admin.professions.edit', compact('profession', 'bodies', 'sponsors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $profession = Profession::where('slug', $slug)->first();
        $profession->bodies()->sync(! $request->body_list ? [] : $request->body_list);
        $profession->sponsors()->sync(!$request->sponsor_list ? [] : $request->sponsor_list);
        $profession->update($request->except('_token', 'body_list', 'sponsor_list'));
        return back();
    }
}
