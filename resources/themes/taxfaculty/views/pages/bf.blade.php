@extends('app')

@section('meta_tags')
    <title>{{ config('app.name') }} | CPD Provider</title>
    <meta name="description" content="Black Friday Deals! Don't miss out!">
    <meta name="Author" content="{{ config('app.name') }}"/>
@endsection

@section('content')

@section('title')
    Black Friday
@stop

@section('intro')
    Black Friday Deals
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('Contact') !!}
@stop

<section id="slider" class="hidden-sm hidden-xs">
    <center style="background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg')">
        <div style="background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg'); height: 350px; background-color: #000000; position:relative; top: 55px;">
            <h4 style="color: red; line-height: 30px; font-size: 30px">BLACK FRIDAY..</h4>
            <h5 style="color: #ffffff; line-height: 30px;">Find great deals and save up to 75%.</h5>
            <div class="countdown bordered-squared theme-style" data-from="November 30, 2018 00:00:00"></div>
        </div>
    </center>
</section>

<section class="alternate">
    <div class="container">
        <div class="row">
            <div class="col-md-8 text-center">
                <h4>Know when the deals drop.</h4>
                <p>Don’t miss the hottest Black Friday offers on CPD Packages & Shop items.</p>
                <p>Find great deals and save up to 75%.</p>
                <p>Save money and be the first to know.</p>
            </div>
            <div class="col-md-4">
                <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', 'Full Name') !!}
                    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    {!! Form::label('email', 'Email Address') !!}
                    {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('number')) has-error @endif">
                    {!! Form::label('number', 'Contact Number') !!}
                    {!! Form::input('text', 'number', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('number')) <p class="help-block">{{ $errors->first('number') }}</p> @endif
                </div>

                <button style="background-color: black; background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg')" class="btn btn-primary">Sign Up!</button>
            </div>
        </div>
    </div>
</section>
@endsection