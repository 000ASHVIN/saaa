<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TermsController extends Controller
{
    public function privacy_policy()
    {
        return view('pages.terms.privacy_policy');
    }

    public function terms_and_conditions()
    {
        return view('pages.terms.terms_and_conditions');
    }
}
