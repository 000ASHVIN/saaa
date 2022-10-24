<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Subscriptions\Models\Feature;
use App\PracticePlanTabs;

class PracticePlanTabsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $features = Feature::all()->pluck('name','id');
        return view('admin.plans.practice_plan.create', compact(['features']));
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
            'name' => 'required'
        ]);
        $tab = PracticePlanTabs::create([
            'name'=>$request->name, 
            'sequence'=>$request->sequence
        ]);

        $features = ($request->features)?$request->features:[];
        $tab->features()->sync($features);

        alert()->success('Tab has been created', 'Success');
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $practice_plan_tab = PracticePlanTabs::findOrFail($id);
        $features = Feature::all()->pluck('name','id');
        return view('admin.plans.practice_plan.edit', compact(['practice_plan_tab', 'features']));

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
        $this->validate($request, [
            'name' => 'required'
        ]);
        $tab = PracticePlanTabs::findOrFail($id);
        $tab->fill([
            'name'=>$request->name, 
            'sequence'=>$request->sequence
        ]);
        $tab->save();
            
        $features = ($request->features)?$request->features:[];
        $tab->features()->sync($features);

        alert()->success('Tab has been updated', 'Success');
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
        $tab = PracticePlanTabs::findOrFail($id);
        $tab->features()->sync([]);
        $tab->delete();

        alert()->success('Tab has been deleted', 'Success');
        return redirect()->route('admin.plans.features.index',['#practice_plan']);
    }
}
