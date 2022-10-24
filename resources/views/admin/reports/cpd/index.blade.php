@extends('admin.layouts.master')

@section('title', 'CPD Members Extract')
@section('description', 'Extract a list of CPD Members')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        {!! Form::open() !!}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('from')) has-error @endif">
                        {!! Form::label('from', 'From') !!}
                        {!! Form::input('text', 'from', null, ['class' => 'form-control is-date']) !!}
                        @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('to')) has-error @endif">
                        {!! Form::label('to', 'To') !!}
                        {!! Form::input('text', 'to', null, ['class' => 'form-control is-date']) !!}
                        @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('cpd_hours')) has-error @endif">
                        {!! Form::label('cpd_hours', 'CPD Hours') !!}
                        {!! Form::input('text', 'cpd_hours', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('cpd_hours')) <p class="help-block">{{ $errors->first('cpd_hours') }}</p> @endif
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('status')) has-error @endif">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status', [
                            'active' => 'Active',
                            'suspended' => 'Suspended'
                        ],null, ['class' => 'form-control']) !!}
                        @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-12">
                    {!! Form::submit('export members', ['class' => 'btn btn-info']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            $('.is-date').datepicker;
        });
    </script>
@stop