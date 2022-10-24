<?php

namespace App\Http\Controllers\Blog;

use App\Blog\Category;
use App\Blog\Post;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;

class PostController extends Controller
{
    public function index($category = null)
    {
        if ($category){
            $category = Category::where('slug', $category)->first();
            return view('blog.filtered_index', compact('category'));
        }else{
            $posts = Post::with('categories')->orderBy('created_at','DESC')->where('draft', false)->where('publish_date','<=',Carbon::now(env('SOUTHAFRICA_TIMEZONE_NAME')))->get()->take(3);
            $categories_post = Category::with('publishedPosts')->get();
            return view('blog.index', compact('posts','categories_post'));
        }
    }

    public function show($slug)
    {
        $user = auth()->user();
        $post = Post::with(['categories', 'author'])->where('draft',false)->where('slug', $slug)->first();
        if(!$post){
            alert()->error('No such post found', 'Error');
            return redirect('/');
        }
        /*
         * Save the post for the user and also save his categories, this way we will know
         * what the user is intrestead in.
         */
        \DB::transaction(function () use($user, $post){
            if ($user && ! $user->posts->contains($post)){
                $user->posts()->save($post);
                $categories = $post->categories;
                $user->saveCategories($categories);
            }
        });

        return view('blog.post', compact('post'));
    }

    public function search(Request $request)
    {
        $search = str_replace('--', '-', str_replace(' ', '-', strtolower(preg_replace('/[^a-zA-Z0-9 .]/', '', $request['search']))));
        $posts = Post::where('slug', 'LIKE', '%'.$search.'%')->where('draft',false)->where('publish_date','<=',Carbon::now(env('SOUTHAFRICA_TIMEZONE_NAME')))->get();

        if (count($posts)){
            alert()->success('Awesome! we some articles you might like!', 'News Articles found');
            return view('blog.search', compact('posts'));
        }else{
            alert()->error('We did not find any posts matching your search criteria, please try again', 'No Articles Found')->persistent('Close');
            return redirect(route('news.index'));
        }
    }
}
