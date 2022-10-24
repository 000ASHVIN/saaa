<?php

namespace App\Http\Controllers\Admin;

use App\Rep;
use App\Users\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RepController extends Controller
{

    public function index()
    {
        $reps = Rep::all();
        $agents = User::has('roles')->get()->filter(function ($agent){
            if ($agent->hasRole('sales')){
                return $agent;
            }
        });
        return view('admin.reps.index', compact('reps', 'agents'));
    }

    public function create()
    {
        $agents = User::has('roles')->get()->filter(function ($agent){
            if ($agent->hasRole('sales')){
                return $agent;
            }
        });
        return view('admin.reps.create', compact('agents'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        Rep::create($request->except('_token'));

        alert()->success('Your Sales Rep was successfully Created!', 'Success');
        return redirect()->route('admin.reps.index');
    }

    public function show($id)
    {
        $rep = Rep::find($id);
        return view('admin.reps.edit', compact('rep'));
    }

    public function edit($id)
    {
        $rep = Rep::find($id);
        return view('admin.reps.edit', compact('rep'));
    }

    public function update(Request $request, $id)
    {
        $rep = Rep::find($id);
        $user = User::find($request['user_id']);
        $rep->update($request->only(['name', 'email','active']));
        $user->rep()->save($rep);

        alert()->success('Your Sales Rep was successfully Updated!', 'Success!');
        return redirect()->route('admin.reps.index');
    }

    public function assign($memberId, Request $request)
    {
        $member = User::find($memberId);
        $rep = Rep::find($request['agent']);
        $member->fresh()->subscription('cpd')->setAgent($rep->user);
        alert()->success('Agent has been assigned successfully!', 'Success!');
        return back();
    }

    public function destroy($id)
    {
        $rep = Rep::find($id);
        $rep->delete();

        alert()->success('Your Sales Rep was successfully deleted.', 'Success!');
        return redirect()->route('admin.reps.index');
    }
}
