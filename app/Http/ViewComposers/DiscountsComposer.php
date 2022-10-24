<?php

namespace App\Http\ViewComposers;

use App\Store\Discount;
use App\Store\Listing;
use Illuminate\Contracts\View\View;

class DiscountsComposer
{
    protected $discounts;

    public function __construct(Discount $discountModel)
    {
        $this->discounts = $discountModel->all();
    }

    public function compose(View $view)
    {
        $view->with('discounts', $this->discounts->pluck('detailedTitle', 'id'));
    }
}