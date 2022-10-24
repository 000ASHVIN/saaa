<?php

namespace App\Store\Cart;

class SimpleCartStorageItem
{
    public $id;
    public $qty;
    public $model;
    public $venue;
    public $pricing;
    public $dates;
    public $request;

    public function __construct($id,$model, $qty = 1)
    {
        $this->id = $id;
        $this->qty = $qty;
        $this->model = $model;
        $this->venue = (request()->venue)?request()->venue:"";
        $this->pricing = (request()->pricing)?request()->pricing:"";
        $this->dates = (count(request()->dates)>0)?request()->dates[0]['id']:"";
        $this->request = request()->all();
    }
}