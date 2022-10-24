@extends('admin.layouts.master')

@section('title', 'Roles')
@section('description', 'Create a new role')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-8">
                {!! Form::open(['Method' => 'Post', 'route' => 'admin.member_roles.store']) !!}

                <div class="form-group">
                    {!! Form::label('name', 'Role Name') !!}
                    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('description', 'Role Description') !!}
                    {!! Form::input('text', 'description', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::submit('Add Role', ['class' => 'btn btn-primary']) !!}
                </div>

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
@stop