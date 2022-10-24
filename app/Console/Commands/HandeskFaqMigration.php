<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Freshdesk;
use Carbon\Carbon;
use App\Blog\Category;
use App\FaqQuestion;
use App\FaqCategories;
use App\FaqTag;


class HandeskFaqMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handesk:migrate-faqs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will migrate FAQs from Freshdesk to the website.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $solutions = Freshdesk::list_solution_categories();
        foreach($solutions as $solution) {

            // Find FAQ category
            $faqCategory = '';
            $solution_id = $solution->id;
            if($solution->name == "Technical Frequently Asked Questions") {
                $faqCategory = FaqCategories::where('type', 'technical')->first();
            }
            else if($solution->name == "General") {
                $faqCategory = FaqCategories::where('type', 'general')->first();
            }
            
            if($faqCategory) {

                $categories = Freshdesk::list_category_solutions($solution_id);
                foreach($categories as $freshdeskCategory) {
                    
                    // Find/Create category
                    $category = Category::where('title', $freshdeskCategory->name)->first();
                    if(!$category) {
                        $category = Category::create([
                            'title' => $freshdeskCategory->name,
                            'parent_id' => 0,
                            'description' => $freshdeskCategory->description,
                            'faq_category_id' => $faqCategory->id,
                            'faq_type' => $faqCategory->type
                        ]);
                    }


                    $category_id = $freshdeskCategory->id;
                    $questions = Freshdesk::list_articles_in_folder($category_id, 'per_page=100');
                    foreach($questions as $freshdeskQuestion) {

                        $question = FaqQuestion::where('question', $freshdeskQuestion->title)->first();
                        if(!$question) {

                            $question = FaqQuestion::create([
                                'question' => $freshdeskQuestion->title,
                                'answer' => str_replace('<p><br></p>', '', $freshdeskQuestion->description),
                                'date' => date('Y-m-d H:i:s',strtotime($freshdeskQuestion->created_at))
                            ]);
                            $question->categories()->sync([$category->id]);

                            // Sync tags
                            $tagIds = $this->getFaqTags($freshdeskQuestion->tags);
                            $question->faq_tags()->sync($tagIds);
                            $this->info('Question synced.');
                        }
                        else {
                            $this->info('Question already exists: '.$question->question);
                        }

                    }
                }

            }
        }
    }

    // Create or get faq tags from titles
    public function getFaqTags($arrTags) {
        $tagIds = [];
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
        return $tagIds;
    }
}
