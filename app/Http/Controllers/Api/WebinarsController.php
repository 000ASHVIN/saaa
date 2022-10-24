<?php

namespace App\Http\Controllers\Api;

use App\Video;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WebinarsController extends Controller
{
    public function index(Request $request)
    {

        $builder = $request->user()->webinars();
        
        if($request->has('limit')) {
            if(is_numeric($request->get('limit'))) {
                $builder = $builder->take($request->get('limit'));
                return $builder->get();
            }
        }

        if($request->has('category')) {
            $builder = $builder->where('category', $request->get('category'));
        }

        if($request->has('tag')) {
            $builder = $builder->where('tag', $request->get('tag'));
        }

        return $builder->get();
    }

    public function myWebinars(Request $request)
    {
    	return $request->user()->webinars->unique('id');
    }

    public function categories(Request $request)
    {
        return $request->user()->getWebinarCategoryCount($request->get('tag'));
    }
}
