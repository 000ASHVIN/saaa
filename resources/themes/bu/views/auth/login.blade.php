@extends('app')

@section('meta_tags')
    <title>SAAA | SA Accounting Academy | CPD Provider</title>
    <meta name="description" content="Log in to your account to start tracking your CPD hours and watch your webinars.">
    <meta name="Author" content="SA Accounting Academy"/>
@endsection

@section('content')

<section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
    <div class="display-table">
        <div class="display-table-cell vertical-align-middle">
            <div class="container">
                <div class="row">
                    <div class="col-md-7">

                        <div class="box-static box-border-top padding-30 margin-bottom-30">
                            <div class="box-title margin-bottom-30">
                                <h2 class="size-20">I'm a returning member</h2>
                            </div>

                            {!! Form::open() !!}
                                {!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email Address']) !!}
                                {!! Form::password('password', ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Enter your password']) !!}

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="checkbox pt-20">
                                        <input type="checkbox" name="remember">
                                        <i></i> Keep me logged in
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label">Sign in</span></button>
                                    <a href="/password/email" class="btn btn-default">Reset Password</a>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="box-static box-border-top padding-30">
                            <div class="box-title margin-bottom-30">
                                <h2 class="size-20">New Member ?</h2>
                            </div>

                            <div class="row">
                               <div class="col-md-12">
                                   <p>If you don't have a login, please register with us to continue.</p>
                                   <h2 class="weight-300 letter-spacing-1 size-13" style="margin-bottom: 10px;"><span>Join more than 7,636.00 accountants</span></h2>
                                   <ul class="list-unstyled list-icons">
                                       <li><i class="fa fa-check text-success"></i> Personal online learning profile.</li>
                                       <li><i class="fa fa-check text-success"></i> Firm wide training and monitoring.</li>
                                       <li><i class="fa fa-check text-success"></i> Keep track of your CPD hours.</li>
                                       <li><i class="fa fa-check text-success"></i> Print your CPD certificate.
                                       </li>
                                       <li><i class="fa fa-check text-success"></i> Attend or download webinars.</li>
                                       <li><i class="fa fa-check text-success"></i> IFAC IES7 compliant CPD content  </li>
                                       <li><i class="fa fa-check text-success"></i> Monthly compliance and legislation update.  </li>
                                       <li><i class="fa fa-check text-success"></i> Access to technical query helpline. </li>
                                   </ul>
                                   <a href="/auth/register" class="btn btn-primary">Register Now</a>
                               </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</section>

@endsection
