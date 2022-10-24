<?php

namespace App\Http\Controllers\Admin;

use App\FaqTag;
use App\Store\Tag;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FaqTagController extends Controller
{

    public function getAdminIndex()
    {
        // Retrieve all tags but NOT the Tag that contains all items.
        $tags = FaqTag::where('title', '!=', 'All')->get();
        return view('admin.faq.tags', compact('tags'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['title' => 'required']);
        FaqTag::create($request->all());

        alert()->success('Your tag has been added successfully!', 'Thank you!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $tag = $this->findTag($id);
        $tag->update($request->all());

        alert()->success('Tag has been updated', 'Success!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = $this->findTag($id);
        $tag->delete();

        alert()->success('Tag has been Removed', 'Success!');
        return back();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findTag($id)
    {
        $tag = FaqTag::find($id);
        return $tag;
    }
}
