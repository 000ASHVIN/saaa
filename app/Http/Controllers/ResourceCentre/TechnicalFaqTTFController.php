<?php

namespace App\Http\Controllers\ResourceCentre;

use App\FaqQuestion;
use App\FaqTag;
use App\Repositories\DatatableRepository\DatatableRepository;
use App\Solution;
use App\SolutionFolder;
use App\SolutionSubFolder;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TechnicalFaqTTFController extends Controller
{
    private $datatableRepository;
    public function __construct(DatatableRepository $datatableRepository)
    {
        $this->datatableRepository = $datatableRepository;
    }

    public function index()
    {
        $folders = SolutionFolder::all();
        return view('technical_faqs.index', compact('folders'));
    }

    public function show($id)
    {
        $folders = SolutionSubFolder::where('solution_folder_id', $id)->get();
        return view('technical_faqs.show', compact('folders'));
    }

    public function general_show($id)
    {
        $question = FaqQuestion::find($id);
        return view('technical_faqs.general_show', compact('question'));
    }

    public function solutions($id)
    {
        $categories = collect();
        $articles = Solution::where('solution_sub_folder_id', $id)->get();

        foreach ($articles as $article){
            foreach ($article->tags as $tag){
                $categories->push($tag);
            }
        }
        return view('technical_faqs.articles', compact('articles', 'categories'));
    }

    public function view_solutions($id)
    {
        $article = Solution::where('solution_article_id', $id)->first();
        return view('technical_faqs.article', compact('article'));
    }

    public function get_tickets()
    {
        return $this->datatableRepository->support_tickets();
    }
}