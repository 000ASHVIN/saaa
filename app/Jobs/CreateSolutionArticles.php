<?php

namespace App\Jobs;

use App\Freshdesk;
use App\Solution;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateSolutionArticles extends Job implements SelfHandling, ShouldQueue
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $articles = Freshdesk::list_articles_in_folder($this->id);

        foreach ($articles as $article) {
            // General
            $bool = Solution::where('solution_article_id', $article->id)->exists();
            if ($bool === true) {
                $article = Solution::where('solution_article_id', $article->id)->first();
                $article->update([
                    'title' => $article->title,
                    'description' => $article->description,
                    'description_text' => $article->description_text,
                    'tags' => $article->tags,
                    'hits' => $article->hits,
                    'seo_data' => $article->seo_data,
                    'thumbs_up' => $article->thumbs_up,
                    'thumbs_down' => $article->thumbs_down,
                    'feedback_count' => $article->feedback_count,
                ]);
            } else {
                Solution::create([
                    'solution_article_id' => $article->id,
                    'solution_folder_id' => $article->category_id,
                    'solution_sub_folder_id' => $article->folder_id,
                    'title' => $article->title,
                    'description' => $article->description,
                    'description_text' => $article->description_text,
                    'tags' => $article->tags,
                    'hits' => $article->hits,
                    'seo_data' => $article->seo_data,
                    'thumbs_up' => $article->thumbs_up,
                    'thumbs_down' => $article->thumbs_down,
                    'feedback_count' => $article->feedback_count,
                ]);
            }
        }
    }
}
