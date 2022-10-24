<?php

namespace App\Http\Controllers\Pages;

use App\Profession\Profession;
use App\Store\Category;
use App\Store\Product;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function showAbout()
    {
        return view('pages.about.about');
    }

    public function showStaff()
    {
        return view('pages.about.staff');
    }

    public function showPresenters()
    {
        return view('pages.about.presenters');
    }

    public function showPartners()
    {
        return view('pages.about.partners');
    }

    public function showDraftworx()
    {
        $products = Category::with('listings', 'listings.products')->where('id', '2')->get();
        return view('pages.partners.draftworx', compact('products'));
    }

    public function showSaiba()
    {
        return view('pages.partners.saiba');
    }

    public function showCpd()
    {
        $professions = Profession::with('plans')->get()->sortBy('title');
        return view('pages.about.cpd', compact('professions'));
    }

    // CPD Pages
    public function accounting_only()
    {
        return redirect()->route('cpd');
//        return view('pages.about.cpd.accounting');
    }

    public function accounting_tax()
    {
        return redirect()->route('cpd');
//        return view('pages.about.cpd.accounting_tax');
    }

    public function bookkeeper_junior_accountant()
    {
        return redirect()->route('cpd');
//        return view('pages.about.cpd.bookkeeper_junior_accountant');
    }

    public function accounting_officer()
    {
        return redirect()->route('cpd');
//        return view('pages.about.cpd.accounting_officer');
    }

    public function build_your_own_package()
    {
        return redirect()->route('cpd');
//        return view('pages.about.cpd.build_your_own_package');
    }


    public function independent_reviewer()
    {
        return redirect()->route('cpd');
//        return view('pages.about.cpd.independent_reviewer');
    }

    public function tax_accountant()
    {
        return redirect()->route('cpd');
//        return view('pages.about.cpd.tax_accountant');
    }

    public function freewebinars()
    {
        return view('pages.webinars.freewebinars');
    }
}
