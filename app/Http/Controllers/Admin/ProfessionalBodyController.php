<?php

namespace App\Http\Controllers\Admin;

use App\Body;
use App\Models\Designation;
use App\Subscriptions\Models\Plan;
use App\UploadOrReplaceImage;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProfessionalBodyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bodies = Body::all();
        return view('admin.professional_bodies.index', compact('bodies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $plans = Plan::active()->get()->each(function ($plan){
            $plan->name = $plan->name.' - R'.$plan->price.' '.ucwords($plan->interval).'ly';
        })->pluck('name', 'id');
        return view('admin.professional_bodies.create', compact('plans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required']);
        $body = Body::create($request->except(['plan_list', '_token']));

        $body->plans()->sync(! $request->plan_list ? [] : $request->plan_list);
        $avatar = UploadOrReplaceImage::UploadOrReplace('body', 'logo', $body);

        alert()->success('Your Professional Body has been saved', 'Success!');
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $body = Body::find($id);
        $plans = Plan::active()->get()->each(function ($plan){
            $plan->name = $plan->name.' - R'.$plan->price.' '.ucwords($plan->interval).'ly';
        })->pluck('name', 'id');

        return view('admin.professional_bodies.edit', compact('body', 'plans'));
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
        $body = Body::find($id);
        $body->update($request->except('plan_list', '_token'));
        $body->plans()->sync(! $request->plan_list ? [] : $request->plan_list);

        $avatar = UploadOrReplaceImage::UploadOrReplace('body', 'logo', $body);
        alert()->success('Your Professional Body has been updated', 'Success!');
        return back();
    }

    //TODO IMplement functionlaity to delelte professional body
    public function destroy($id)
    {
        //
    }
}
