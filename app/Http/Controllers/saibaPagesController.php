<?php

namespace App\Http\Controllers;

use App\Profession\Profession;
use App\Subscriptions\Models\Plan;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class saibaPagesController extends Controller
{
    public function index()
    {
        $profession = Profession::where('slug', 'business-accountant-in-practice-saiba')->first();
        $plans = collect(Plan::whereIn('id', ['18', '23', '24', '29', '30', '48', '54'])->get());
        unset($profession->plans);
        $profession->plans = $plans;
        return view('saiba.index', compact('profession'));
    }

    public function tax_practitioner()
    {
        $profession = Profession::with('plans', 'plans.features')->where('slug', 'tax-practitioner')->first();
        return view('saiba.tax_practitioner', compact('profession'));
    }
}
