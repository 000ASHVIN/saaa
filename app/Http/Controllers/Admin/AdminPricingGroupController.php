<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\AssignWebinarAttendeesToSendinBlue;
use App\Jobs\UploadNewsArticleReadersToSendinBlue;
use App\UploadOrReplaceImage;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PricingGroup;

use App\Console\Commands\backdateUserNotes;
use App\Repositories\DatatableRepository\DatatableRepository;
use Illuminate\Http\Response;
  
class AdminPricingGroupController extends Controller
{
    // Display all pricing group data
    public function index()
    {
        $pricing_groups = PricingGroup::paginate(10);
        return view('admin.pricing_group.index', compact('pricing_groups'));
    } 

    // Redirect to create form
    public function create()
    {
        return view('admin.pricing_group.create');
    }

    // Add data in pricing group
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'min_user' => 'required',
            'max_user' => 'required',
            'billing' => 'required',
            'is_active' => 'required',
        ]);
        \DB::transaction(function () use($request){
            $pricing_group = new PricingGroup([
                'name' => $request->name,
                'price' => $request->price,
                'min_user' => $request->min_user,
                'max_user' => $request->max_user,
                'billing' => $request->billing,
                'is_active' => $request->is_active
            ]);
            $pricing_group->save();
        });
        alert()->success('Your pricing group has been saved!', 'Success!');
        return redirect()->route('admin.pricing_group.index');
    }

    // Redirect to update form with data
    public function edit($id)
    {
        $pricing_group = PricingGroup::where('id', $id)->first();
        return view('admin.pricing_group.edit', compact('pricing_group'));
    }

    // Update the particular record of pricing group
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
            'min_user' => 'required',
            'max_user' => 'required',
            'billing' => 'required',
            'is_active' => 'required',
        ]);

        \DB::transaction(function () use($request, $id){
            $pricing_group = PricingGroup::where('id', $id)->first();
            $pricing_group->update($request->except('_token'));
        });

        alert()->success('Your pricing group has been updated!', 'Success!');
        return back();
    }

    // Delete the item from pricing group
    public function destroy($id)
    {
        $pricing_group = PricingGroup::findorFail($id);
        $pricing_group->delete();
        alert()->success('Your data has been removed!', 'Success!');
        return back();
    }

}
