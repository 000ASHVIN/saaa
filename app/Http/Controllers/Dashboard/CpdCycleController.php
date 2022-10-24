<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Cycle;
use App\Models\Designation;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CpdCycleController extends Controller
{
    public function store(Request $request)
    {
        $designation = Designation::find($request['designation_id']);

        $cycle = new Cycle([
            'start_date' => Carbon::parse($request['start_date'])->startOfDay(),
            'end_date' => Carbon::parse($request['end_date'])->endOfDay(),
        ]);

        $cycle->designation()->associate($designation);
        $cycle->body()->associate($designation->body);
        $cycle->save();

        $cycle->user()->save(auth()->user());
        alert()->success('Cycle has been created', 'Success!');
        return back();
    }

    public function update($cycleId, Request $request)
    {
        $this->validate($request, [
            'start_date'    => 'required',
            'end_date'      => 'required',
            'designation_id'   => 'required'
        ]);

        try{
            $cycle = Cycle::find($cycleId);
            $cycle->update($request->only('start_date', 'end_date', 'designation_id'));
            alert()->success('Your CPD cycle has been updated', 'Success!');

        }catch (\Exception $exception){
            alert()->error('Something went wrong', 'Warning!');
        }
        return back();
    }
}
