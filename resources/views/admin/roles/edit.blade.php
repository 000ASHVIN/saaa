@extends('admin.layouts.master')

@section('title', 'Role')
@section('description', 'Edit ' .$role->name. ' role')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="col-md-8">
            {!! Form::model($role, ['method' => 'post', 'route' => ['admin.member_roles.assign_to_permissions.save', $role->id]]) !!}

            <div class="form-group">
                {!! Form::label('name', 'Role Name') !!}
                {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('all_permissions', 'Available Permissions') !!}
                {!! Form::select('all_permissions[]', $permissions , $role->permissions->pluck('id', 'name')->toArray(), ['class' => 'form-control', 'multiple' => 'multuple']) !!}
            </div>

            <div class="form-group">
                {!! Form::submit('save changes', ['class' => 'btn btn-primary']) !!}
            </div>
            {!! Form::close() !!}

            <hr>
            <h4>Role Users</h4>
            <hr>
            <table class="table">
                <thead>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Remove from role</th>
                </thead>
                <tbody>
                    @if(count($role->users))
                        @foreach($role->users as $user)
                            <tr>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    {!! Form::open(['method' => 'post', 'route' => ['remove_role_from_user', $role->id]]) !!}
                                    {!! Form::hidden('user_id', $user->id, ['class' => 'form-control']) !!}
                                    {!! Form::submit('remove this user', ['class' => 'btn btn-xs btn-danger']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4"><strong>There are no users for this role</strong></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <hr>
            <h4>Role Permissions</h4>
            <hr>
            <table class="table">
                <thead>
                <th>Name</th>
                <th>Description</th>
                </thead>
                <tbody>
                    @if(count($role->permissions))
                        @foreach($role->permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->description }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="2"><b>There is no permissions for this role.</b></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            $('select').select2();
        });
    </script>
@stop