<?php

namespace App\Http\ViewComposers;

use App\Store\Category;
use Illuminate\Contracts\View\View;
use App\Body;
use App\Rep;
use App\Subscriptions\Models\Plan;
use DB;

class SearchComposer
{ 
    protected $Plan;
    protected $Body;
    protected $Rep;

    public function __construct(Category $categoryModel)
    {
        $this->Plan = Plan::select(DB::raw("CONCAT(`name`, ' - ', `interval`)  as Name"),'id')->where('invoice_description','NOT LIKE','%Course:%')->where('inactive', false)->get()->pluck('Name','id');
        $this->Body = Body::get()->pluck('title','id');
        $this->Rep = Rep::where('active',1)->get()->pluck('name','user_id');
    }

    public function compose(View $view)
    {
        $view->with('plan', $this->Plan);
        $view->with('body', $this->Body);
        $view->with('rep', $this->Rep);
    }
}