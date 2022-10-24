<?php

namespace App\Http\Controllers\Admin;

use App\Departments;
use App\Jobs;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Departments::all();
        return view('admin.careers.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Departments::all();
        return view('admin.careers.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'         => 'required',
            'period'        => 'required',
            'description'   => 'required',
            'available'     => 'required'
        ]);
        $job = $request->all();
        Jobs::create($job);
        return redirect()->back();
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function applications()
    {
        return view('admin.careers.applications');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit($slug)
    {
        $departments = Departments::all();
        $job = Jobs::where('slug', $slug)->first();
        return view('admin.careers.edit', compact('job', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $slug
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, $slug)
    {
        $job = Jobs::where('slug', $slug)->first();
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required'
        ]);
        $input = $request->all();
        $job->fill($input)->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
