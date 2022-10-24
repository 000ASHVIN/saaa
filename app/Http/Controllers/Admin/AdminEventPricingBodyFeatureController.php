<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Pricing;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminEventPricingBodyFeatureController extends Controller
{
    public function update(Request $request, $pricing)
    {
        $pricing = Pricing::find($pricing);
        $pricing->bodies()->sync(! $request['bodyList'] ? [] : $request['bodyList']);

        alert()->success('Your professional bodies has been saved!', 'Success!');
        return back()->withInput(['tab' => 'pricings']);
    }
}
