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
                                    <h1 class="size-20 mb-30" style="text-align: center">Password Reset Request</h1>
                                </div>

                                {!! Form::open(['method' => 'post', 'url' => '/password/email' ]) !!}
                                    {!! Form::input('text','email','', ['class' => 'form-control', 'placeholder' => 'Email Address']) !!}
                                    {!! Form::input('submit', null, 'Send Password Reset Link',['class' => 'btn btn-primary btn-lg btn-block']) !!}
                                {!! Form::close() !!}

                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </section>

@endsection
