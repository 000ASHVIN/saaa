<?php

namespace App\Http\Controllers\ResourceCentre;

use App\FaqTag;
use App\Solution;
use Carbon\Carbon;
use App\FaqQuestion;
use App\Blog\Category;
use App\Http\Requests;
use App\SolutionFolder;
use App\SolutionSubFolder;
use App\FaqCategories;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\DatatableRepository\DatatableRepository;

class TechnicalFaqController extends Controller
{
    private $datatableRepository;
    public function __construct(DatatableRepository $datatableRepository)
    {
        $this->datatableRepository = $datatableRepository;
    }

    public function index()
    {
        $faqCategories = FaqCategories::where('type','technical')->with('categories', 'categories.categories', 'categories.categories.categories', 'categories.faqs', 'categories.categories.faqs', 'categories.categories.categories.faqs')->get();
        return view('technical_faqs.index', compact('faqCategories'));
    }

    public function show($id=null)
    {
        $search_string = '';
        $faq = collect();
        $arrQuestionCount = [];
        $arrCategories = [];
        
        $faqCategories = FaqCategories::where('type','technical')->with('categories', 'categories.categories', 'categories.categories.categories', 'categories.categories.categories.categories', 'categories.faqs', 'categories.categories.faqs', 'categories.categories.categories.faqs', 'categories.categories.categories.categories.faqs', 'categories.faqs.categories', 'categories.categories.faqs.categories', 'categories.categories.categories.faqs.categories', 'categories.categories.categories.categories.faqs.categories')->get();
        $category = null;

        // Get faqs
        foreach($faqCategories as $faqCategory){

            $arrCategories[] = $this->get_categories($faqCategory);

            foreach($faqCategory->categories as $cat) {
                $faq = $this->get_faqs($cat, $faq, $arrQuestionCount);
            }

            // if($faqCategory->id == $id) {
                $category = $faqCategory;
            // }

        }

        $faq = $faq->unique('id');

        return view('technical_faqs.show', compact('faqCategories', 'category', 'faq', 'arrQuestionCount', 'arrCategories','search_string' ));
    }

    // Recursive function to get category hierarchy and questions count
    protected function get_categories($folder) {

        $arrCategory=[];
        $arrCategory['model'] = $folder;
        if(get_class($folder)=="App\FaqCategories") {
            $arrCategory['id'] = 'f_'.$folder->id;
            $arrCategory['name'] = $folder->name;
            $childCategories = $folder->categories;
        }
        else {
            $arrCategory['id'] = $folder->id;
            $arrCategory['name'] = $folder->title;
            $childCategories = $folder->categories;
        }
        $arrCategory['categories'] = [];

        // If child categories exists than call recursive function
        foreach($childCategories as $cat) {
            $recursive = $this->get_categories($cat);
            if($recursive) {
                $arrCategory['categories'][] = $recursive;
            }
        }

        if(get_class($folder)=="App\FaqCategories") {
            $arrCategory['question_ids'] = [];
            $arrCategory['slug'] = '';
            $arrCategory['slugs'] = '';
        }
        else {
            // Calculate final data for all child categories
            $arrCategory['question_ids'] = $folder->faqs->pluck('id')->toArray();
            $arrCategory['slug'] = $folder->slug;
            if(!isset($arrCategory['slugs'])) {
                $arrCategory['slugs'] = $folder->slug;
            }
        }

        foreach($arrCategory['categories'] as $singleCat)  {
            $arrCategory['slugs'] .= " ".$singleCat['slugs'];
            $arrCategory['question_ids'] = array_merge($arrCategory['question_ids'], $singleCat['question_ids']);
            $arrCategory['question_ids'] = array_unique($arrCategory['question_ids']);
        }
        
        if(count($arrCategory['question_ids'])) {
            return $arrCategory;
        }
        return null;
    }

    // Recursive function to include all faqs to a collection
    protected function get_faqs($folder, $faq, &$arrQuestionCount=[]) {
        
        if(count($folder->categories) || $folder->faqs->count()){
            
            // If FAQs are present then add to collection
            if($folder->faqs->count()){
                $folder->faqs->each(function ($item, $key) use ($faq,$folder,&$arrQuestionCount) {
                    $item->sub_folder = $folder->id;
                    if($item->date <=Carbon::now()){
                        $faq->push($item);
                        if(isset($arrQuestionCount[$folder->id])) {
                            $arrQuestionCount[$folder->id]++;
                        }
                        else {
                            $arrQuestionCount[$folder->id]=1;
                        }
                    }
                });
            }

            // If categories are present them call recursive function
            foreach($folder->categories as $cat){
                if($cat){
                    $faq = $this->get_faqs($cat, $faq,$arrQuestionCount);
                }
            }

        }
        else {
            // If the categories and faqs are not present
            return $faq;
        }
        return $faq;
    }
    
    public function general_show($id)
    {
        $question = FaqQuestion::find($id);
        return view('technical_faqs.general_show', compact('question'));
    }

    public function solutions($id)
    {
        $categories = collect();
        $articles = Category::where('id', $id)->first();

        foreach ($articles->faqs as $article){
            foreach ($article->faq_tags as $tag){
                $categories->push($tag->slug);
            }
        }
        return view('technical_faqs.articles', compact('articles', 'categories'));
    }

    public function view_solutions($id)
    {
        $article = FaqQuestion::where('slug', $id)->first();
        return view('technical_faqs.article', compact('article'));
    }

    public function get_tickets()
    {
        return $this->datatableRepository->support_tickets();
    }
    public function search(Request $request)
    {
        $categories = Category::all();
        $category = $categories->first();

        $faq = FaqQuestion::select('faq_questions.*')->join('category_faq_question','category_faq_question.faq_question_id','=','faq_questions.id')->search($request['search'], null, true)->orderBy('relevance','desc')->get();
        
        if (count($faq)){
            alert()->success("We found ".count($faq)." matching your search criteria", 'Success');
            return view('technical_faqs.search', compact('folders' ,'categories' , 'category', 'faq' ));
        }else{
            alert()->error('We did not find any faq matching this search criteria', 'No faq Found');
            return back();
        } 
    } 
}
