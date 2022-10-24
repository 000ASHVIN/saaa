<?php

namespace App\Http\Controllers\Blog;

use App\Blog\Comment;
use App\Blog\Post;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function store(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->first();
        $user = auth()->user();

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'description' => 'required|min:10'
        ]);

        DB::transaction(function () use($request, $user, $post){
            $clean_html = preg_replace("#<\s*\/?\s*[^>]*?>#im", '', $request['description']);
            $comment = New Comment([
                'name' => $request['name'],
                'email' => $request['email'],
                'description' => $clean_html,
                'approved' => false
            ]);

            $comment->post()->associate($post);
            $comment->user()->associate($user);
            $comment->save();
        });

        alert()->success('Your comment has been sent for moderation', 'Success!');
        return back();
   }
}
