<?php

namespace App\Http\ViewComposers;

use App\Store\Listing;
use Illuminate\Contracts\View\View;

class ListingsComposer
{
    protected $listings;

    public function __construct(Listing $listingModel)
    {
        $this->listings = $listingModel->all();
    }

    public function compose(View $view)
    {
        $view->with('listings', $this->listings->pluck('title', 'id'));
    }
}