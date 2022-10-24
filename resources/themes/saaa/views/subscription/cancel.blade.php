@extends('app')

@section('content')

@section('title')
    Cancel CPD Subscription
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('cancel_cpd') !!}
@stop

<section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="box-static box-border-top padding-30">
                <div class="box-title margin-bottom-30">
                    Dear CPD Subscriber
                </div>
                <p>
                    We are sorry that you wish to cancel your subscription. Before you make a final decision to cancel,
                    <a href="{{ route('cpd') }}"> please take a look at our new packages for 2017 to see if there is a package which better meets your
                        needs</a>
                </p>
                <p>
                    If you still wish to cancel, please complete the following details:
                </p>

                @if($errors->has())
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                @endif

                {!! Form::open(['method' => 'post', 'route' => 'post_cancel_cpd']) !!}
                <div class="form-group">
                    {!! Form::label('name', 'Full Name') !!}
                    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('id_number', 'ID Number') !!}
                    {!! Form::input('text', 'id_number', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('email', 'Email Address') !!}
                    {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('cell', 'Cell Number') !!}
                    {!! Form::input('text', 'cell', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group text-center">
                    {!! Form::submit('Cancel Subscription', ['class' => 'btn btn-primary']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
@endsection