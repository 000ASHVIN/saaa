<?php

namespace App\Http\Controllers\Pages;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CertificateController extends Controller
{
   
    public function index()
    {
        return view('pages.courses.certificate_courses');
    }

    public function ifrs_for_smes()
    {
        return view('pages.courses.ifrs_for_smes');
    }

    public function independent_review_engagements()
    {
        return view('pages.courses.independent_review_engagements');
    }

    public function practising_licence_independent_review_engagements()
    {
        return view('pages.courses.practising_licence_independent_review_engagements');
    }

    public function external_courses()
    {
        return view('pages.courses.external_courses');
    }

    public function ifrs_learning_and_assessment_programme()
    {
        return view('pages.courses.ifrs_learning_and_assessment_programme');
    }

    public function ifrs_for_smes_learning_and_assessment_programme()
    {
        return view('pages.courses.ifrs_for_smes_learning_and_assessment_programme');
    }

    public function isas_online_learning_and_assessment_programme()
    {
        return view('pages.courses.isas_online_learning_and_assessment_programme');
    }
}
