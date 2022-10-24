@extends('admin.layouts.master')

@section('title', 'Permissions')
@section('description', 'Assign new permissions to roles')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-8">
                {!! Form::open(['method' => 'post', 'route' => 'admin.permissions_store_assigned']) !!}
                @include('admin.roles.permissions.includes.form', ['button' => 'Assign Permissions'])
                {!! Form::close() !!}
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
    <script type="text/javascript">
        $('select').select2();
    </script>
@stop