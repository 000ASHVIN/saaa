<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;

use Illuminate\Http\Request;
use App\SponsorList;
use App\Http\Controllers\Controller;
use App\Repositories\DatatableRepository\DatatableRepository;
use App\UploadOrReplaceImage;

class SponsorListController extends Controller
{
    private $datatableRepository;

    public function __construct(DatatableRepository $datatableRepository)
    {
        $this->datatableRepository = $datatableRepository;
    }
 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
          return view('admin.sponsor.index');
    }

    
    public function list_sponsor()
    {
        return $this->datatableRepository->list_sponsor();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sponsor.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'title' => 'required',            
            'email_id' => 'required',
            'content' => 'required',
            'is_active' => 'required',
            'is_premium_partner' => 'required',
        ]);
                
        \DB::transaction(function () use($request){
            if(isset($request->question)){
                $question = implode(',',$request->question);                
            }
            else{
                $question = null; 
            }
            $sponsors = SponsorList::create([
                'name' => $request->name,
                'title' => $request->title,
                'content' => $request->content,
                'email_id' => $request->email_id,
                'is_active' => $request->is_active,
                'is_premium_partner' => $request->is_premium_partner,
                'short_description' => $request->short_description,
                'question_id' => $question
            ]);
            // Logo upload
            if($request->logo){
                $logo = UploadOrReplaceImage::UploadOrReplace('sponsors', 'logo', $sponsors,'full');
                } 
        });
        

        alert()->success('Your Sponsor has been created!', 'Success');
        return redirect()->route('admin.sponsor.index');
    }
 
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sponsor = SponsorList::find($id);
      
        return view('admin.sponsor.edit', compact('sponsor'));
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
        $sponsors = SponsorList::find($id);
        if(isset($request->question)){
            $question = implode(',',$request->question);                
        }
        else{
           $question = null; 
        }
        
        // $sponsors->update($request->except('_token'));
        $sponsors->update([
            'name' => $request->name,
            'title' => $request->title,
            'email_id' => $request->email_id,
            'content' => $request->content,
            'short_description' => $request->short_description,
            'is_active' => $request->is_active,
            'is_premium_partner' => $request->is_premium_partner,
            'question_id' => $question

        ]);

        // Logo upload
          if($request->logo){
            $logo = UploadOrReplaceImage::UploadOrReplace('sponsors', 'logo', $sponsors,'full');
            } 

        alert()->success('Your sponsors Data has been udpated successfully', 'Success!');
        return redirect()->route('admin.sponsor.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
