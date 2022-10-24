@extends('admin.layouts.master')

@section('title', 'Careers')
@section('description', 'Show Available Jobs')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {!! Form::model($job, ['Method' => 'Update', 'route' => ['jobs.update', $job->slug]]) !!}
                       @include('admin.careers.includes.form', ['SubmitButton' => 'Save Changes'])
               {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop