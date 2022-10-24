<?php

namespace App\Http\Controllers;

use App\Coupon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CouponsController extends Controller
{
    public function checkCoupon(Request $request, $code)
    {

        $input = $request->all();
        dd($input);
        return Coupon::where('code', $code)->firstOrFail();
    }
}
