<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\DatatableRepository\DatatableRepository;
use App\Users\Industry;

class IndustryController extends Controller
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
    public function index()
    {
        return view('admin.industries.index');
    }

    public function list_industries()
    {
        return $this->datatableRepository->list_industries();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.industries.create');
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
            'title' => 'required'
        ], [
            'title.required' => 'The Industry field is required.'
        ]);
                
        \DB::transaction(function () use($request){
            $sponsors = Industry::create([
                'title' => $request->title,
            ]);
        });
        

        alert()->success('Your Industry has been created!', 'Success');
        return redirect()->route('admin.industries.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($industry)
    {
        $industry = Industry::find($industry);
        return view('admin.industries.show', compact('industry'));
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
    public function update(Request $request, $industry)
    {
        $industry = Industry::find($industry);
        $industry->update($request->except('_token'));

        alert()->success('Your Industry Data has been udpated successfully', 'Success!');
        return redirect()->back();
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
