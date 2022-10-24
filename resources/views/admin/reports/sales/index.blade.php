@extends('admin.layouts.master')

@section('title', 'Sales Report')
@section('description', 'Sales report per agent')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">

        <div class="row">
            <div class="col-md-12">
                <div id="bussy"></div>
            </div>
        </div>

        {!! Form::open() !!}
        <div class="form-group @if ($errors->has('agent')) has-error @endif">
            {!! Form::label('agent', 'Please select your Agent') !!}
            <select name="agent" id="agent" class="form-control">
                <option value="System">System</option>
                @foreach($users as $agent)
                    <option value="{{ $agent->id }}">{{ $agent->first_name.' '.$agent->last_name }}</option>
                @endforeach
            </select>
            @if ($errors->has('agent')) <p class="help-block">{{ $errors->first('agent') }}</p> @endif
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group @if ($errors->has('from')) has-error @endif">
                    {!! Form::label('from', 'From Date') !!}
                    {!! Form::input('text', 'from', null, ['class' => 'form-control is-date']) !!}
                    @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group @if ($errors->has('to')) has-error @endif">
                    {!! Form::label('to', 'To Date') !!}
                    {!! Form::input('text', 'to', null, ['class' => 'form-control is-date']) !!}
                    @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
                </div>
            </div>
        </div>

        <div class="form-group @if ($errors->has('category')) has-error @endif">
            {!! Form::label('category', 'Please select your category') !!}
            {!! Form::select('category', [
                'event_registration' => 'Event Invocies',
                'webinars_on_demand' => 'Webinar Invocies',
                'subscription_upgrade_procedure' => 'CPD Invoices',
                'store_items' => 'Store Orders',
                'course_subscription' => 'Course Invoices'
            ], null, ['class' => 'form-control']) !!}
            @if ($errors->has('category')) <p class="help-block">{{ $errors->first('category') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('status')) has-error @endif">
            {!! Form::label('status', 'Please select your status') !!}
            {!! Form::select('status', [
                null => 'Please Select..',
                'paid' => 'Paid',
                'unpaid' => 'Unpaid',
                'cancelled' => 'Cancelled',
                'partial' => 'Partial',
                'credit noted' => 'Credit Noted',
            ], null, ['class' => 'form-control']) !!}
            @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('commision_claimed')) has-error @endif">
            {!! Form::label('commision_claimed', 'Commission Paid') !!}
            {!! Form::select('commision_claimed', [
                null => 'Please Select..',
                true => 'Yes',
                false => 'No',
            ], null, ['class' => 'form-control']) !!}
            @if ($errors->has('commision_claimed')) <p class="help-block">{{ $errors->first('commision_claimed') }}</p> @endif
        </div>

        <button type="submit" name="submit" value="export_report" class="btn btn-o btn-primary" id="lock-refresh" ;>
            <span class="spin_div fa fa-gears"><i></i></span> Extract Invoices
        </button>

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

        $('#lock-refresh').click(function() {
            $('.spin_div').addClass( 'fa-spin' );
            document.getElementById('bussy').innerHTML = "<div class=\"alert alert-info\"><i class=\"fa fa-spinner fa-spin\"></i> We are extracting your file, Please wait...</div>"

            setTimeout(function() {
                $('.spin_div').removeClass( 'fa-spin' );
                document.getElementById('bussy').innerHTML = ""
            }.bind(this), 10000);
        });
    </script>
@stop