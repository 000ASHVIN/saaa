@extends('app')

@section('content')

@section('title')
    Thank you for renewing your subscription
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('confirm_subscription') !!}
@stop
<section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="box-static box-border-top padding-30">
                <div class="box-title margin-bottom-30">
                    Dear {{ $user->first_name }} {{ $user->last_name }}
                </div>

                <p>
                    Thank you very much for renewing your subscription. Please go to <a href="{{ route('cpd') }}">CPD</a> for
                    more information about the topics included in your package for next year
                </p>

                @if($errors->has())
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                @endif

                {!! Form::open(['method' => 'post', 'route' => 'renew_subscription_post']) !!}
                {!! Form::hidden('user_id', $user->id) !!}

                <div class="form-group">
                    {!! Form::label('first_name', 'First Name') !!}
                    {!! Form::input('text', 'first_name', $user->first_name, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('last_name', 'Last Name') !!}
                    {!! Form::input('text', 'last_name', $user->last_name, ['class' => 'form-control']) !!}
                </div>

                {!! Form::hidden('email', $user->email) !!}

                <div class="form-group">
                    {!! Form::label('email_1', 'Email Address') !!}
                    {!! Form::input('text', 'email_1', $user->email, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('new_plan', 'Please select your new plan') !!}
                    {!! Form::select('new_plan', $plans ,null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    <p>Please indicate if you would like to have Professional Indemnity cover</p>
                    <label class="checkbox-inline"><input name="pi_cover" type="checkbox" value="1"> Yes, please sign me up</label>
                </div>
                <hr>

                <div class="form-group text-center">
                    <label><input type="checkbox" name="terms"> I have read and understood the <a target="_blank" href="{{ route('terms_and_conditions') }}">terms and conditions</a></label>
                </div>

                <hr>
                <div class="form-group">
                    {!! Form::submit('I Confirm', ['class' => 'btn btn-primary btn-block']) !!}
                </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</section>
@endsection
