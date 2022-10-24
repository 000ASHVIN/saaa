<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ChangeCpSubscriptionController extends Controller
{
    public function change_subscription(Request $request)
    {
        $user = auth()->user();
        dd($request->all());
    }
}
