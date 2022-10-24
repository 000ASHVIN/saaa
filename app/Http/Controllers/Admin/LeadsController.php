<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CourseProcess;
use App\LeadStatus;
use App\Rep;

class LeadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leads = CourseProcess::orderBy('updated_at', 'desc');

        // Search parameters
        if($request->full_name!=""){
            $sanitizedSearch = $this->trim($request->full_name);
            $strippedSanitizedSearch = preg_replace('/\s/', '', $sanitizedSearch);
            $leads= $leads->where(function($query) use($sanitizedSearch, $strippedSanitizedSearch) {
                $query->where('first_name', 'LIKE', '%' . $sanitizedSearch . '%')
                ->orWhere('last_name', 'LIKE', '%' . $sanitizedSearch . '%')
                ->orWhereRaw('CONCAT(first_name,last_name) like ?', ['%' . $strippedSanitizedSearch . '%']); 
            });
        } 
        if($request->email!=""){
            $sanitizedSearch = $this->trim($request->email);
            $leads= $leads->Where('email', 'LIKE', '%' . $sanitizedSearch . '%');
        }
        if($request->cell!=""){
            $sanitizedSearch = $this->trim($request->cell);
            $leads= $leads->Where('mobile', 'LIKE', '%' . $sanitizedSearch . '%');
        }
        if($request->status!=""){
            $leads= $leads->Where('lead_status_id',  $request->status);
        }

        if($request->is_converted!=""){
            $leads= $leads->Where('is_converted',  $request->is_converted);
        }

        // Lead owner
        $user = auth()->user();
        if(!$user->is('super')) {
            $leads= $leads->Where('owner_id', $user->id);
        }
        else {
            if($request->owner_id!=""){
                $leads= $leads->Where('owner_id',  $request->owner_id);
            }
        }
        
        $leads = $leads->paginate(20);
        $statuses = LeadStatus::all();
        $reps = Rep::all();

        return view('admin.leads.index', compact('leads', 'statuses', 'reps', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lead = CourseProcess::find($id);
        return view('admin.leads.activity', compact(['lead']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $lead_id = $request->lead_id;
        $lead = CourseProcess::find($lead_id);
        if($lead) {

            $data = [];
            if(isset($request->lead_status_id)) {
                $lead->lead_status_id = $request->lead_status_id ? $request->lead_status_id : 0;
            }

            if(isset($request->owner_id) && auth()->user()->is('admin|super')) {
                $lead->owner_id = $request->owner_id ? $request->owner_id : 0;
            }
            $lead->timestamps = false;
            $lead->save();
            $lead->timestamps = true;
            alert()->success('Lead updated successfully!', 'Awesome!');

        }
        else {
            alert()->error('Invalid request!', 'Error!');
        }
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

    private function trim($search)
    {
        $sanitizedSearch = trim(strtolower($search));
        $strippedSanitizedSearch = preg_replace('/\s/', '', $sanitizedSearch);
        return $strippedSanitizedSearch;
    }
}
