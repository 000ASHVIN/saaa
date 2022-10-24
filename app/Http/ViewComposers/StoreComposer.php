<?php

namespace App\Http\ViewComposers;

use App\Store\Category;
use Illuminate\Contracts\View\View;

class StoreComposer
{
    protected $categories;

    public function __construct(Category $categoryModel)
    {
        $this->categories = $categoryModel->where('active', true)->get();
    }

    public function compose(View $view)
    {
        $view->with('categories', $this->categories);
    }
}