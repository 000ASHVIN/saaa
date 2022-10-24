<?php

namespace App\Http\Controllers\Admin;

use App\Subscriptions\Models\Feature;
use Artisan;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input; 

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\PracticePlanTabs;

class MembershipPlanFeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Features tab
        $feature_search = '';
        $data =[];

        if($request['search']) {
            // Incase of searching
            $feature_search = $request['search'];
            $search =strtolower(preg_replace('/[^a-zA-Z0-9 .]/', '', trim($request['search'])));
            
            $features = Feature::where('name', 'LIKE', '%'.$search.'%')->paginate(8);
            $features->appends(Input::except('page'));
            
        } else {
            $features = Feature::paginate(8);
        }

        // Practice plan tab
        $practice_plan_tabs = PracticePlanTabs::paginate(20);

        // Pass data to view
        $data['feature_search'] = $feature_search;
        $data['features'] = $features;
        $data['practice_plan_tabs'] = $practice_plan_tabs;

        return view('admin.plans.features.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.plans.features.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        Feature::create($request->except('_token'));
        alert()->success('Your feature has been created', 'Success');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $feature
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit($feature)
    {
        $feature = Feature::where('slug', $feature)->first();
        return view('admin.plans.features.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $feature
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, $feature)
    {
        $feature = Feature::where('slug', $feature)->first();
        $feature->slug = null;

        $feature->update($request->all());
        $feature->save();

        alert()->success('Your feature has been saved', 'Success!');
        return redirect()->route('admin.plans.features.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $feature
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($feature)
    {
        Feature::where('slug', $feature)->first()->delete();
        alert()->success('Your feature has been deleted', 'Success');
        return back();
    }

    public function default_byo_features()
    {
        $data = config('byo_default');
        $default = collect();

        foreach ($data as $topic){
            $default->push(Feature::where('slug', $topic)->first());
        }

        $topics = Feature::all();
        return view('admin.plans.byo.index', compact('default', 'topics'));
    }

    public function default_byo_features_update(Request $request)
    {
        $features = Feature::find($request->topics);
        $data = $features->map(function($item) {
            return $item['slug'];
        })->toArray();

        config()->set('byo_default', []);

        foreach ($data as $key => $value){
            config(['byo_default.'.$key => $value]);
            $fp = fopen(base_path() .'/config/byo_default.php' , 'w');
            fwrite($fp, '<?php return ' . var_export(config('byo_default'), true) . ';');
            fclose($fp);
        }

        // Cache the new config.
        Artisan::call('config:cache');

        alert()->success('Topics has been updated successfully', 'Success!');
        return redirect()->back();
    }
}
