<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Blog\Comment;
use App\Blog\Post;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{
    public function index($slug)
    {
        $comments = Post::where('slug', $slug)->first()->pendingComments();
        return view('admin.blog.comments.update', compact('comments'));
    }

    public function update($commentId)
    {
        $comment = Comment::find($commentId);
        $comment->update(['approved' => true]);
        alert()->success('The comment was approved and published', 'Success!');
        return back();
    }

    public function decline($commentId)
    {
        $comment = Comment::find($commentId);
        $comment->delete();
        alert()->success('The comment was sucessfully deleted', 'Success!');
        return back();
    }
}
