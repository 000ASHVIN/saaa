<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\FaqQuestion;

class FaqTagMappingScript extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual:faq-tag-mapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $mapping=[
            
        ];

        $faqQuestions = FaqQuestion::all();
        foreach($faqQuestions as $question) {
            $tags = [];
            if($question->tags) {
                $tags[] = $question->tags->id;
            }

            foreach($question->faq_tags as $tag) {
                $tags[] = $tag->id;
            }

            array_unique($tags);
            
            // Categories
            $categories = [];

            if(count($tags)) {
                foreach($tags as $tag) {

                    if(isset($mapping[$tag])) {
                        $categories[] =  $mapping[$tag];
                    }

                }
            }

            if(count($categories)) {
                $question->categories()->sync($categories);
            }
            dd();
        }

    }
}
