@extends('admin.layouts.master')

@section('title', 'Departments')
@section('description', 'Create a new department')

@section('content')
    <div class="container">
        <br>
        <div class="row">
          <div class="col-md-6">
              {!! Form::open(['Method' => 'STORE', 'route' => ['admin.departments.store']]) !!}
                    <div class="form-group">
                        {!! Form::label('title','Department Title') !!}
                        {!! Form::input('text','title', null, ['class' => 'form-control', 'placeholder' => 'Accounts Department']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('description','Department Description') !!}
                        {!! Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => 'Describe this department']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::submit('Create Department', ['class' => 'btn btn-default']) !!}
                    </div>
                {!! Form::close() !!}
          </div>

            <div class="col-md-6">
                @if(count($departments))
                    <table class="table">
                        <thead>
                            <th>Department Title</th>
                            <th>Department Description</th>
                            <th>Open Jobs</th>
                            <th>Edit Department</th>
                        </thead>
                        @foreach($departments as $department)
                        <tr>
                            <td>{{$department->title}}</td>
                            <td>{{str_limit($department->description, 50)}}</td>
                            <td><a href="/admin/jobs">{{count($department->jobs)}} Position Open</a></td>
                            <td><a href="#"><i class="fa fa-edit"></i> Edit Department (Coming Soon)</a></td>
                        </tr>
                        @endforeach
                    </table>
                @endif
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop