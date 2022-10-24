<?php

namespace App\Http\Controllers\Questionaire;

use App\DatabaseQuestionnaire;
use App\Location\Country;
use DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Queue\DatabaseQueue;

class QuestionaireController extends Controller
{
    public function index()
    {
        $countries = DB::table("countries")->lists("name","id");
        return view('questionnaire.index', compact('countries'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact_number' => 'required',
            'email' => 'required|email',
            'age' => 'required',
            'gender' => 'required',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'income' => 'required',
            'race' => 'required',
            'job_title' => 'required',
            'other_job_title' => 'required_if:job_title,other',
            'level_of_education' => 'required',
            'accounting_field' => 'required',
            'type_of_professional' => 'required',
            'other_accountant_type' => 'required_if:type_of_professional,other',

            'staff_members_amount' => 'required_if:type_of_professional,own_practice|required_if:type_of_professional,part_time_practice',

            'staff_benefits' => 'required_if:type_of_professional,own_practice|required_if:type_of_professional,part_time_practice',

            'professional_indemnity' => 'required_if:type_of_professional,own_practice|required_if:type_of_professional,part_time_practice',

            'organisation_do_you_work' => 'required_if:type_of_professional,commerce|required_if:type_of_professional,in_practice',

            'employer_offer_benefits' => 'required_if:type_of_professional,commerce|required_if:type_of_professional,in_practice',

            'do_you_belong_to_a_professional_body' => 'required',
            'select_professional_body' => 'required_if:do_you_belong_to_a_professional_body,yes',
            'other_professional_body' => 'required_if:select_professional_body,other',

            'expand_practice_income' => 'required',
            'reduce_risk_products' => 'required',
        ]);

       DB::transaction(function () use($request){
           DatabaseQuestionnaire::create($request->except('_token'));
       });

       alert()->success('Thank you '.ucfirst($request->first_name).' for completing our short survey', 'Success');
       return redirect()->route('home');
    }
}
