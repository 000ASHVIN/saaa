<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\LeadStatus;

class LeadStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = LeadStatus::orderBy('name')
            ->paginate(20);
        return view('admin.leads.statuses.index', compact('statuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.leads.statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, LeadStatus::rules());

        $status = LeadStatus::create($request->except('_token'));
        alert()->success('Your status has been saved!', 'Awesome!');
        return redirect()->route('admin.leads.status.index');
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
        $status = LeadStatus::find($id);
        if(!$status) {
            alert()->error('Status not available!', 'Error!');
            return redirect()->back();
        }
        return view('admin.leads.statuses.edit', compact('status'));
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
        $this->validate($request, LeadStatus::rules($id));

        $status = LeadStatus::find($id);
        if(!$status) {
            alert()->error('Status not available!', 'Error!');
            return redirect()->back();
        }
        $status->update($request->except('_token'));
        alert()->success('Your status has been saved!', 'Awesome!');
        return redirect()->route('admin.leads.status.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $status = LeadStatus::find($id);
        if(!$status) {
            alert()->error('Status not available!', 'Error!');
            return redirect()->back();
        }
        $status->delete();
        alert()->success('Your status has been deleted!', 'Awesome!');
        return redirect()->back();
    }
}
