<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Requests;
use App\Users\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressesController extends Controller
{
    /**
     * Display a listing of all the user's addresses.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAddresses()
    {
        return view('dashboard.edit.addresses');
    }

    /**
     * Store a newly created address in storage.
     *
     * @param Requests\AddAddressRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\AddAddressRequest $request)
    {
        if($request->province == 'others'){
           $request->province = $request->other_province;
        } 
        $user = auth()->user();
        $address = [
            'type' => $request->type,
            'line_one' => preg_replace("/[^A-Za-z0-9 ]/", '', $request->line_one),
            'line_two' => preg_replace("/[^A-Za-z0-9 ]/", '', $request->line_two),
            'province' => $request->province,
            'country' => $request->country,
            'city' => $request->city,
            'area_code' => $request->area_code,
        ];

        $user->addresses()->create($address);
        alert()->success('Your new address has been added.', 'Success')->persistent('Close');
        return redirect()->back();
    }

    /**
     * Set an address as the primary address for user
     */
    public function setPrimary($id)
    {
        Address::find($id)->setPrimary();
        alert()->success('Your address has been set as primary address.', 'Success');
        return back();
    }

    /**
     * Remove the specified address from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Address::find($id)->delete();
        alert()->success('Your address has been deleted.', 'Success');
        return back();
    }
}
