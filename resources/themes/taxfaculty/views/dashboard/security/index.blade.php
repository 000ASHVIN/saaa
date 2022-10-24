@extends('app')

@section('title')

@stop

@section('content')
    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <div class="display-table">
            <div class="display-table-cell vertical-align-middle">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">

                            <div class="box-static box-border-top padding-30 text-center">
                                <div class="box-title margin-bottom-30">
                                    <h2 class="size-20">Security Question</h2>
                                    <p>Dear {{ $user->first_name }} {{ $user->last_name }}, We need to verify that this
                                        account belongs to you, Please complete your email address below and click on
                                        continue.</p>
                                </div>

                                {!! Form::open(['method' => 'post', route('dashboard.security_question')]) !!}
                                {!! Form::hidden('secret', $secret) !!}

                                <div class="form-group @if ($errors->has('email')) has-error @endif">
                                    {!! Form::label('email', 'Please complete your full email address below') !!}
                                    {!! Form::input('text', 'email', (auth()->user()? auth()->user()->email : null), ['class' => 'form-control']) !!}
                                    @if ($errors->has('email'))
                                        <p class="help-block">{{ $errors->first('email') }}</p> @endif
                                </div>

                                <hr>
                                <p>Please select one of the following payment options available</p>

                                <label class="radio">
                                    <input type="radio" name="payment_option" value="debit" checked="">
                                    <i></i> Debit Order
                                </label>

                                <label class="radio">
                                    <input type="radio" name="payment_option" value="full">
                                    <i></i> Full Payment
                                </label>

                                <hr>
                                <a href="/contact" class="btn btn-info"><i class="fa fa-envelope"></i> Contact Enquiries</a>
                                {!! Form::submit('Next Step', ['class' => 'btn btn-primary']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop