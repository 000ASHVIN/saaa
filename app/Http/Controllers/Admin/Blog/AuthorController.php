<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Blog\Author;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class AuthorController extends Controller
{

    public function index()
    {
        $authors = Author::paginate();
        return view('admin.blog.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.blog.authors.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        Author::create($request->only('name', 'description'));
        alert()->success('Your author has been created', 'Success!');
        return redirect()->route('admin.authors.index');
    }

    public function edit($authorID)
    {
        $author = Author::find($authorID);
        return view('admin.blog.authors.edit', compact('author'));
    }

    public function update(Request $request, $authorID)
    {
        $author = Author::find($authorID);
        $this->validate($request, ['name' => 'required']);
        $author->update($request->only(['name', 'description']));
        alert()->success('Your author has been updated', 'Success!');

        return redirect()->route('admin.authors.index');
    }
}
