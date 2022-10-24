@extends('admin.layouts.master')

@section('title', 'Generate new crypt code')
@section('description', 'This will generate a new encrypted code and send an email')
@section('styles')
    <!-- Data Tables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">

    <style>
        .dt-buttons{
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
    <section class="container-fluid container-fullw bg-white ng-scope">
        <br>
        <div class="container">
            <div class="row">
                {!! Form::open(['method' => 'post']) !!}
                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    {!! Form::label('email', 'Email Address') !!}
                    {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                </div>

                {!! Form::submit('Generate', ['class' => 'btn btn-default']) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script src="/js/app.js"></script>
@stop