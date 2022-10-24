<?php

namespace App\Http\Controllers;

use App\PiUser;
use App\PiAddress;
use App\PiAssessment;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('http://www.firstforaccountants.co.za');
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
    public function storeAddress(Request $request)
    {
//        $this->validate($request, [
//            'user_id' => 'required',
//            'business.line_one' => 'required',
//            'postal.line_one' => 'required'
//        ], [
//            'business.line_one.required' => 'A valid Business or Residential address is required',
//            'postal.line_one.required' => 'A valid postal address is required'
//        ]);

        // return $request->all();

//        // Store the PiUser
//        $user =  PiUser::find($request->user_id);
//
//        $user->piAddresses()->save(new PiAddress($request->business));
//        $user->piAddresses()->save(new PiAddress($request->postal));
//
//        return $user;
    }

    public function storeAssessment(Request $request)
    {
//        $user =  PiUser::find($request->user_id);
//
//        $user->update([
//            'registered' => $request->registered,
//            'body' => $request->body
//        ]);
//
//        $assessment = new PiAssessment($request->options);
//
//        return $user->piAssessment()->save($assessment);
    }

    public function storeComplete(Request $request)
    {
//        $user =  PiUser::find($request->user_id);
//
//        $user->update([
//            'declined_reason' => $request->declined_reason,
//            'aware' => $request->aware,
//            'aware_description' => $request->aware_description,
//            'legal_entity' => $request->legal_entity,
//            'negligence' => $request->negligence,
//            'practice_abroad' => $request->practice_abroad,
//            'work_abroad' => $request->work_abroad
//        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUser(Request $request)
    {
//        $this->validate($request, [
//            'first_name' => 'required',
//            'last_name' => 'required',
//            'email' => 'required|email|unique:pi_users',
//            'terms' => 'required'
//        ]);
//
//        // Store the PiUser
//        return PiUser::create($request->only(['first_name', 'last_name', 'email']));
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
        //
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
        //
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
