<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AgentGroup;
use App\Users\User;
use App\Handesk;
use App\Blog\Category;

class AdminAgentGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agentGroups = AgentGroup::with('categories', 'agents')->paginate(10);
        return view('admin.agent_groups.index', compact('agentGroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $agents = User::get()->take(10);
        $agents = User::whereHas('roles', function($q) {})
            ->orderBy('first_name', 'asc')
            ->get();
        
        $categories = Category::where('parent_id', 0)
            ->where('faq_category_id','!=', '0')
            ->orderBy('title', 'asc')
            ->get();

        return view('admin.agent_groups.create', compact('agents', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name' => 'required']);
        $agentGroup = AgentGroup::create($request->except('_token', 'AgentsList', 'categories'));

        if($request->AgentsList) {
            $agentGroup->agents()->attach(
                $request->AgentsList?$request->AgentsList:[]
            );
        }

        if($request->categories) {
            $agentGroup->categories()->attach(
                $request->categories?$request->categories:[]
            );
        }

        $this->syncWithHandesk($agentGroup);

        alert()->success('Your group has been created', 'Success!');
        return redirect()->route('admin.agent_groups.index');
    }

    /*
    * Function will sync Agent group to handesk ticket
    */
    public function syncWithHandesk($agentGroup) {

        // Create team in handesk
        $handesk_team = null;
        if($agentGroup->handesk_team_id) {
            $handesk_team = Handesk\Team::find($agentGroup->handesk_team_id);
        }
        else {
            $handesk_team = Handesk\Team::create([
                'name' => $agentGroup->name
            ]);
        }

        if($handesk_team) {

            if($agentGroup && $agentGroup->handesk_team_id != $handesk_team->id) {
                $agentGroup->update(['handesk_team_id'=>$handesk_team->id]);
            }

            // Assign users to team in handesk
            $handesk_user_ids = collect();
            if($agentGroup->agents) {

                $agents = $agentGroup->agents;
                foreach($agents as $agent) {

                    // Find or create handesk user
                    $user = Handesk\User::findOrCreate([
                        'name' => ucwords($agent->first_name.' '.$agent->last_name),
                        'email' => $agent->email
                    ]);

                    if($user) {
                        $handesk_user_ids->push($user->id);   
                    }

                }

                if($handesk_user_ids->count()) {
                    $handesk_team->members()->sync($handesk_user_ids->toArray());
                }

            }

        }

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
        $agentGroup = AgentGroup::find($id);
        
        $agents = User::whereHas('roles', function($q) {})
            ->orderBy('first_name', 'asc')
            ->get();

        $categories = Category::where('parent_id', 0)
            ->where('faq_category_id','!=', '0')
            ->orderBy('title', 'asc')
            ->get();

        return view('admin.agent_groups.edit', compact('agentGroup','agents','categories'));
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
        $this->validate($request, ['name' => 'required']);

        $agentGroup = AgentGroup::find($id);
        if($agentGroup) {
            $agentGroup->update($request->only('name'));
            if($request->AgentsList) {
                $agentGroup->agents()->sync(
                    $request->AgentsList?$request->AgentsList:[]
                );
            }

            if($request->categories) {
                $agentGroup->categories()->sync(
                    $request->categories?$request->categories:[]
                );
            }

            $this->syncWithHandesk($agentGroup);
            
            alert()->success('Your group has been updated', 'Success!');
        }
        else {
            alert()->error('Group not found', 'Error!');
        }
        return redirect()->route('admin.agent_groups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $agentGroup = AgentGroup::find($id);
        if($agentGroup) {
            $agentGroup->agents()->detach();
            $agentGroup->delete();

            // Delete from handesk
            if($agentGroup->handesk_team_id) {
                $handesk_team = Handesk\Team::find($agentGroup->handesk_team_id);
                if($handesk_team) {
                    $handesk_team->members()->detach();
                    $handesk_team->delete();
                }
            }

            alert()->success('Your group has been deleted', 'Success!');
        }
        else {
            alert()->error('Group not found', 'Error!');
        }
        return redirect()->route('admin.agent_groups.index');
    }
}
