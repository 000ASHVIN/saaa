<?php

namespace App\Http\Controllers\Admin;

use App\Blog\Category;
use App\FaqQuestion;
use App\FaqCategories;
use App\FaqTag;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FaqQuestionController extends Controller
{

    public function getHome(Request $request)
    {
        $faqQuestion = FaqQuestion::whereRaw(1);

        /* Search FAQs */
        if(isset($request['question']) && $request['question']!="")
        {
            $search = $request['question'];
            $faqQuestion = $faqQuestion->where('question', 'LIKE', '%'.$search.'%');
        }

        if(isset($request['categories']) && $request['categories']!="")
        {
            $faqQuestion = $faqQuestion->whereHas('categories', function($q) use($request){
                $q->where('categories.id','=',$request['categories']);
            });
        }

        if(isset($request['from_date']) && $request['from_date']!="")
        {
            $faqQuestion = $faqQuestion->where('created_at', '>=', $request['from_date']);
        }

        if(isset($request['to_date']) && $request['to_date']!="")
        {
            $faqQuestion = $faqQuestion->where('created_at', '<=', $request['to_date']);
        }

        // Get categories
        $categories = Category::whereHas('faqs', function($q) {})
            ->orderBy('categories.title')
            ->get();

        $faqQuestion = $faqQuestion->orderBy('question')->paginate(30);
        return view('admin.faq.all', compact('faqQuestion', 'categories'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = $this->getTags();
        $faqTypes = FaqCategories::with('categories')->get();

        $categories = collect();
        foreach($faqTypes as $faqType) {

            $type = $faqType->type;
            foreach($faqType->categories as $category) {
                $category->type = $type;
                $categories->push($category);

                foreach($category->categories as $subcategory) {

                    $subcategory->type = $type;
                    $categories->push($subcategory);

                    foreach($subcategory->categories as $topic) {
                        $topic->type = $type;
                        $categories->push($topic);
                    }

                }

            }
        }
        return view('admin.faq.questions', compact('tags', 'faqTypes', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function store(Request $request)
    {

        $this->validate($request, ['question' => 'required', 'answer' => 'required']);
        \DB::transaction(function () use($request){
            $FaqQuestion = new FaqQuestion();
            $question = FaqQuestion::create([
                'question' => $request->question,
                'answer' => $request->answer,
                'date' => date('Y-m-d H:i:s',strtotime($request->date))
            ]);
            $question->categories()->sync(($request['categories_list'] ? : []));

            // Sync tags
            $tagIds = $this->getFaqTags($request['faq_tags']);
            $question->faq_tags()->sync($tagIds);
        });

     
        alert()->success('Thank you for adding more helpful questions!', 'Thanks :)');
        return back();
    }

    // Create or get faq tags from titles
    public function getFaqTags($strTags) {
        $tagIds = [];
        if(trim($strTags)) {
            $arrTags = explode(',', $strTags);
            if(count($arrTags)) {
                foreach($arrTags as $tag) {

                    $faqTag = FaqTag::where('title', $tag)->first();

                    if(!$faqTag) {
                        $faqTag = FaqTag::create([
                            'title' => $tag
                        ]);    
                    }

                    if($faqTag) {
                        $tagIds[] = $faqTag->id;
                    }
                    
                }

            }
        }
        return $tagIds;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = FaqQuestion::findorFail($id);
        $tags = $this->getTags();
        $faqTypes = FaqCategories::with('categories')->get();

        $categories = collect();
        foreach($faqTypes as $faqType) {

            $type = $faqType->type;
            foreach($faqType->categories as $category) {
                $category->type = $type;
                $categories->push($category);

                foreach($category->categories as $subcategory) {

                    $subcategory->type = $type;
                    $categories->push($subcategory);

                    foreach($subcategory->categories as $topic) {
                        $topic->type = $type;
                        $categories->push($topic);
                    }

                }

            }
        }
        return view('admin.faq.questions_edit', compact('question','tags','faqTypes', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        \DB::transaction(function () use($request,$id){
            $question = FaqQuestion::findorFail($id);
            $question->update([
                'question' => $request->question,
                'answer' => $request->answer,
                'date' => date('Y-m-d H:i:s',strtotime($request->date))
            ]);
            $question->categories()->sync(($request['categories_list'] ? : []));
            
            // Sync tags
            $tagIds = $this->getFaqTags($request['faq_tags']);
            $question->faq_tags()->sync($tagIds);
        });

        alert()->success('Thank you for updating this question!', 'Thanks :)');
        return redirect('admin/faq/all'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FaqQuestion::destroy($id);
        alert()->success('Question has been removed', 'Thank you!');
        return back();
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        $tags = FaqTag::where('title', '!=', 'all')->with('questions')->get();
        return $tags;
    }

    public function getFaqCategories() {
     
        $arrCategories = [];
        
        $faqCategories = FaqCategories::all();

        foreach($faqCategories as $faqCategory){

            $arrCategories[] = $this->get_categories($faqCategory);
        }

        // dd($arrCategories);
        return view('admin.faq.categories', compact('categories','category','arrCategories'));
    }

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
            $childCategories = $folder->childCategory();
        }
        $arrCategory['categories'] = [];

        // If child categories exists than call recursive function
        foreach($childCategories as $cat) {
            $arrCategory['categories'][] = $this->get_categories($cat);
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
        return $arrCategory;
    }
}
