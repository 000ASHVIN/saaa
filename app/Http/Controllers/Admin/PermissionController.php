<?php

namespace App\Http\Controllers\Admin;

use App\Users\Permission;
use App\Users\Role;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.roles.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.roles.permissions.create');
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
        Permission::create($request->all());

        alert()->success('Permission created!', 'Thank you');
        return redirect(route('admin.permissions'));
    }

    public function assign()
    {
        $roles = Role::all()->pluck('name', 'id');
        $permissions = Permission::all()->pluck('name', 'id');
        return view('admin.roles.permissions.assign', compact('roles', 'permissions'));
    }

    public function store_assigned(Request $request)
    {
        $role = Role::find($request->role);
        $permissions = Permission::find($request->all_permissions);

        // loop through all the given permissions and assign them to the given role.
        foreach ($permissions as $permission) {
            $role->attachPermission($permission);
//            $role->detachPermission($permission);
        }

        alert()->success('Permissions has been assigned', 'Success!');
        return redirect()->back();
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
        //
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
