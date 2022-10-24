@extends('admin.layouts.master')

@section('title', 'Permissions')
@section('description', 'Show All Permissions')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-8">
                <table class="table table">
                    <thead>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    </thead>
                    <tbody>
                    @if(count($permissions))
                        @foreach($permissions as $permission)
                            <tr>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->slug }}</td>
                                <td>{{ $permission->description }}</td>
                            </tr>
                        @endforeach
                    @else
                        <td colspan="2">
                            No Permissions created yet
                        </td>
                    @endif
                    </tbody>
                </table>
                <a class="btn btn-primary" href="{{ route('admin.permissions_new') }}">Create New</a>
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