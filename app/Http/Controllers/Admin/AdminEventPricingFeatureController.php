<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Pricing\PricingRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminEventPricingFeatureController extends Controller
{

    private $pricingRepository;
    public function __construct(PricingRepository $pricingRepository)
    {
        $this->pricingRepository = $pricingRepository;
    }

    public function update(Request $request, $pricing)
    {
        $pricing = $this->pricingRepository->findPricing($pricing);
        $pricing->features()->sync($request->selected_features? : []);

        alert()->success('Your features has been saved!', 'Success!');
        return back()->withInput(['tab' => 'pricings']);
    }
}
