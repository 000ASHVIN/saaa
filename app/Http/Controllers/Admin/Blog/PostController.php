<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Blog\Author;
use App\Blog\Category;
use App\Blog\Post;
use App\Jobs\AssignWebinarAttendeesToSendinBlue;
use App\Jobs\UploadNewsArticleReadersToSendinBlue;
use App\UploadOrReplaceImage;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Sendinblue\Mailin;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\ContactsApi;
use GuzzleHttp\Client;
use Exception;
use App\Repositories\Sendinblue\SendingblueRepository;

class PostController extends Controller
{
    private $sendingblueRepository;
    public function __construct(SendingblueRepository $sendingblueRepository)
    {
        $this->sendingblueRepository = $sendingblueRepository;
    }
    
    public function index(Request $request)
    {

        $articles = Post::whereRaw(1);

        /* Search FAQs */
        if(isset($request['title']) && $request['title']!="")
        {
            $search = trim($request['title']);
            $articles = $articles->where('title', 'LIKE', '%'.$search.'%');
        }

        if(isset($request['categories']) && $request['categories']!="")
        {
            $articles = $articles->whereHas('categories', function($q) use($request){
                $q->where('categories.id','=',$request['categories']);
            });
        }

        if(isset($request['from_date']) && $request['from_date']!="")
        {
            $articles = $articles->where('created_at', '>=', $request['from_date']);
        }

        if(isset($request['to_date']) && $request['to_date']!="")
        {
            $articles = $articles->where('created_at', '<=', $request['to_date']);
        }
        $categories = Category::whereHas('posts', function($q) {})
        ->orderBy('categories.title')
        ->get();
        $articles = $articles->paginate(10);
        $articles->appends($request->except('search'));
        return view('admin.blog.articles.index', compact('articles','categories'));
    }

    public function create()
    {
        $categories = Category::all()->pluck('title', 'id');
        $authors = Author::all()->pluck('name', 'id');
        return view('admin.blog.articles.create', compact('categories', 'authors'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'author_list' => 'required',
            'category_list' => 'required',
        ]);

        if(strlen($request['publish_time']) > 5){
            $request['publish_time'] = substr($request['publish_time'],0,5);
        }

        \DB::transaction(function () use($request){
            $authors = Author::find($request['author_list']);
            $categories = Category::find($request['category_list']);

            $post = new Post([
                'title' => $request->title,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'draft' => $request->draft,
                'keyword' => $request->keyword,
                'meta_description' => $request->meta_description,
                'publish_date' => Carbon::createFromTimestamp(strtotime($request['publish_date'] . $request['publish_time'] . ':00')),
                'publish_time' => $request->publish_time
                // 'publish_date' => $request->publish_date
            ]);
            $post->save();

            foreach ($categories as $category){
                $post->categories()->save($category);
            };

            foreach ($authors as $author) {
                $post->author()->save($author);
            }

            UploadOrReplaceImage::UploadOrReplace('posts', 'image', $post, $size = 800);

        });
        alert()->success('Your article has been saved!', 'Success!');
        return redirect()->route('admin.news.index');
    }

    public function edit($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $authors = Author::all()->pluck('name', 'id');
        $categories = Category::all()->pluck('title', 'id');
        return view('admin.blog.articles.edit', compact('post', 'authors', 'categories'));
    }

    public function update(Request $request, $slug)
    {
        $this->validate($request, [
            'title' => 'required',
            'short_description' => 'required',
            'description' => 'required',
        ]);

        if(strlen($request['publish_time']) > 5){
            $request['publish_time'] = substr($request['publish_time'],0,5);
        }
        $request['publish_date'] = Carbon::createFromTimestamp(strtotime($request['publish_date'] .' '. $request['publish_time'] . ':00'));
        
        $post = Post::where('slug', $slug)->first();
        \DB::transaction(function () use($request, $post){
            $post->slug = null;

            $post->update($request->except('_token', 'category', 'image', 'author'));

            $post->author()->sync($request->author_list);
            $post->categories()->sync($request->category_list);

            UploadOrReplaceImage::UploadOrReplace('posts', 'image', $post, $size = 479);
        });

        alert()->success('Your article has been saved!', 'Success!');
        return redirect()->route('admin.news.edit', $post->fresh()->slug);
    }

    public function uploadToSendingBlue($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $users = $post->users;

     
        try {
            $data = [
                'name' => 'News Article readers for '.$post->title.' '.date_format(Carbon::now(), 'd F Y'),
                'folderId' => (int)env('ARTICLE_PARENT_FOLDER_ID')
            ];
            $folder = $this->sendingblueRepository->createList($data);
    
            $newsArticleReadersJob = (new UploadNewsArticleReadersToSendinBlue($users, $folder));
            $this->dispatch($newsArticleReadersJob);
        } catch (Exception $e) {}
        
        alert()->success('We are uploading your contacts, Please Check SendinBlue In 5 Minutes', 'Success!');
        return redirect()->route('admin.news.index');
    }
}
