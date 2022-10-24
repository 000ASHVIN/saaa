<?php
/**
 * Created by PhpStorm.
 * User: Tiaan
 * Date: 2018/05/08
 * Time: 14:47
 */

namespace App\Http\ViewComposers;

use App\Blog\Category;
use App\Blog\Comment;
use Illuminate\Contracts\View\View;

class NewsComposer
{
    protected $categories;
    protected $comments;

    public function __construct(Category $categories, Comment $comment)
    {
        $this->categories = $categories->all();
        $this->comments = $comment->where('approved', true)->get()->sortByDesc('created_at')->take(3);
    }

    public function compose(View $view)
    {
        $view->with(['categories' => $this->categories, 'comments' => $this->comments]);
    }
}