@extends('admin.layouts.master')

@section('title', 'Roles')
@section('description', 'View all available roles')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="col-md-8">
            <div class="row">
                <table class="table">
                    <th>Role</th>
                    <th>Description</th>
                    <th>Slug</th>
                    <th>Users</th>
                    <th class="text-center">Tools</th>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->description }}</td>
                            <td>{{ $role->slug }}</td>
                            <td><div class="label label-body">{{ count($role->users) }}</div></td>
                            <td class="text-center"><a class="btn btn-xs btn-default" href="{{ route('admin.member_roles.assign_to_permissions.edit', $role->id) }}">Edit Role</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <a href="{{ route('admin.member_roles.create') }}" class="btn btn-primary">Add New Role</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop