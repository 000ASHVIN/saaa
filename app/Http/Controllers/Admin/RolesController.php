<?php

namespace App\Http\Controllers\Admin;

use App\Users\Permission;
use App\Users\Role;
use App\Users\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('users')->get();
        return view('admin.roles.index', compact('roles'));
    }


    public function permissions()
    {
        $roles = Role::all()->pluck('name', 'id');
        $permissions = Permission::all()->pluck('label', 'id');
        return view('admin.roles.permissions', compact('permissions', 'roles'));
    }

    public function storePermissions(Request $request)
    {
        Role::findorFail($request->role_id)
        ->permissions()->attach($request->permission_id);
        alert()->success("Your Permissions has been assigned to the given role!");
        return redirect()->back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.roles.create');
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
        Role::create($request->all());
        alert()->success('You have created a new role', 'Success');
        return redirect('/admin/roles');
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
        $role = Role::find($id);
        $roles = Role::all()->pluck('name', 'id');
        $permissions = Permission::all()->pluck('name', 'id');

        return view('admin.roles.edit', compact('role', 'roles', 'permissions'));
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
        $role = Role::find($id);
        if (! $request->has('all_permissions')){
            $role->detachAllPermissions();
        }else{
            $role->permissions()->sync($request->input('all_permissions'));
        }
        $role->update($request->all());

        alert()->success($role->name .' role '. 'has been updated', 'Success!');
        return back();
    }

    public function removeFromUser($id)
    {
        $user = User::find($id);
        $user->detachAllRoles();

        alert()->success('All roles has been removed', 'Success!');
        return back();
    }

    public function removeRoleFromUser(Request $request, $id)
    {
        $role = Role::find($id);
        $user = User::find($request->user_id);
        $user->detachRole($role);

        alert()->success('Role has been removed', 'Success!');
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
        //
    }
}
