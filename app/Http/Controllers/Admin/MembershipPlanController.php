<?php

namespace App\Http\Controllers\Admin;

use App\PricingGroup;
use App\Billing\Item;
use App\Profession\Profession;
use App\Subscriptions\Models\Feature;
use App\Subscriptions\Models\Plan;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class MembershipPlanController extends Controller
{
    /**
     * Display all membership plans.
     */
    public function index()
    {
        $plans = Plan::where('invoice_description','NOT LIKE','%Course:%')->get()->sortBy('inactive');
        return view('admin.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $professions = Profession::all()->pluck('title', 'id');
        $features = Feature::all()->pluck('name', 'id');
        $pricing_group = PricingGroup::select(
            DB::raw("CONCAT(name,' (',price,')') AS name"),'id')->get()->pluck('name','id');
        return view('admin.plans.create', compact('features', 'professions','pricing_group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Check for custom_plan checkbox
        $data = $request->except('_token');
        $plan = Plan::create($data);
        $plan->features()->sync(($request->PlanFeaturesList ? : []));
        $plan->professions()->sync(($request->ProfessionList ? : []));
        $plan->pricingGroup()->sync(($request->pricing_group ? : []));

        alert()->success('Your plan has been created', 'Success');
        return redirect()->route('admin.plans.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $plan
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function edit($plan)
    {
        $plan = Plan::find($plan);
        $features = Feature::all()->pluck('name', 'id');
        $professions = Profession::all()->pluck('title', 'id');
        $pricing_group = PricingGroup::select(
            DB::raw("CONCAT(name,' (',price,')') AS name"),'id')->get()->pluck('name','id');
        
        return view('admin.plans.edit', compact('plan', 'features', 'professions','pricing_group'));
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
        $plan = Plan::find($id);
        $plan->professions()->sync(($request->ProfessionList ? : []));
        $plan->pricingGroup()->sync(($request->pricing_group ? : []));

        $plan->features()->sync(($request->PlanFeaturesList ? : []));
        $plan->update($request->except('_token'));

        $data = $request->except('_token');
        $plan->update($data);

        alert()->success('Your plan has been created', 'Success');
        return redirect()->back();
    }

    public function export_invoices(Request $request, $planId)
    {
        $plan = Plan::find($planId);
        $items = Item::with('invoices')->where('type', 'subscription')->where('name', 'LIKE', '%'.$plan->name.'%')->get();
        $items = $items->filter(function ($item){
            if (count($item->invoices) >= 1){
                return $item;
            }
        });

        Excel::create('All Invoices for '.$plan->name, function($excel) use($items) {
            $excel->sheet('All Invoices', function($sheet) use($items){
                $sheet->appendRow([
                    'User ID',
                    'Reference',
                    'Invoice Created At',
                    'Invoice Balance',
                    'Invoice Type'
                ]);

                foreach ($items as $item) {
                    $sheet->appendRow([
                        $item->invoices->first()->user_id,
                        $item->invoices->first()->reference,
                        date_format($item->invoices->first()->created_at, 'd F Y'),
                        $item->invoices->first()->transactions->where('type', 'debit')->sum('amount') - $item->invoices->first()->transactions->where('type', 'credit')->sum('amount'),
                        $item->invoices->first()->type,
                    ]);
                }
            });
        })->export('xls');
    }
}
