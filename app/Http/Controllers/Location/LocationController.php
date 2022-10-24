<?php

namespace App\Http\Controllers\Location;

use App\Location\City;
use App\Location\State;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
class LocationController extends Controller
{
    public function getStateList(Request $request)
    {
        $states = State::where("country_id",$request['country_id'])->get()->pluck('name', 'id');
        return response()->json($states);
    }

    public function getCityList(Request $request)
    {
        $cities = City::where('state_id', $request['state_id'])->get()->pluck('name', 'id');
        return response()->json($cities);
    }
}
