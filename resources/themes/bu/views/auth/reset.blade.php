@extends('app')

@section('content')

    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <div class="display-table">
            <div class="display-table-cell vertical-align-middle">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">

                            <div class="box-static box-border-top padding-30">
                                <div class="box-title margin-bottom-30">
                                    <h2 class="size-20" style="text-align: center">Reset Your Password</h2>
                                </div>

                                {!! Form::open(['method' => 'post', 'url' => '/password/reset']) !!}
                                {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email Address']) !!}
                                {!! Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Enter your new password']) !!}
                                {!! Form::password('password_confirmation', ['id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => 'Confirm Your Password']) !!}
                                {!! Form::input('submit', null, 'Reset Password',['class' => 'btn btn-primary btn-lg btn-block']) !!}
                                <input type="hidden" name="token" value="{{ $token }}">
                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
