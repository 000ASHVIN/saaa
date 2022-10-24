@extends('app')

@section('meta_tags')
    <title>Custom Login | {{ config('app.name') }}</title>
    <meta name="description" content="Log in to your account to start tracking your CPD hours & watch your webinars. Or sign up as a new member & get access to monthly tax updates & more.">
    <meta name="Author" content="{{ config('app.name') }}"/>
@endsection

@section('content')
@include('subscriptions.2017.include.help_popup')

<section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
    <div class="display-table">
        <div class="display-table-cell vertical-align-middle">
            <div class="container">
                <div class="row">
                    <div class="col-md-push-2 col-md-8">

                        <div class="box-static box-border-top padding-30 margin-bottom-30">
                            <div class="box-title margin-bottom-30">
                                <!-- <h2 class="size-20">I'm a returning member</h2> -->
                                <h2 class="size-20">Sign into your Tax Faculty Profile here</h2>
                            </div>
                              {!! Form::open(['id'=>'login_form','onSubmit'=>'return false']) !!}
                            <auth-login inline-template>
                            <div  v-if="createNew">
                                <div class="panel panel-default" style="margin-bottom: 0px">
                                    <div class="panel-body">
                                        <!-- <p class="text-center">
                                           We couldn't found any email record. Please create new account  <a href="/auth/register">Create an account </a>
                                        </p> -->
                                        <p class="text-center">
                                        We couldn't find that email address, please create a new profile below<br>
                                        <a href="/auth/register">Create an account</a>
                                        </p>
                                    </div>

                                </div>
                                <br>

                            </div>
                                <div class="form-group">
                                {!! Form::text('email', old('email'), ['class' => 'form-control', 'v-model'=>'forms.login.email', 'placeholder' => 'Email Address']) !!}
                                </div>
                                <div class="form-group" v-if="isEmailExist">
                                {!! Form::password('password', ['id' => 'password', 'v-model'=>'forms.login.password', 'class' => 'form-control', 'placeholder' => 'Enter your password']) !!}
                                </div>

                            <div class="row"  v-if="isEmailExist">
                                <div class="col-md-12">
                                    <label class="checkbox pt-20">
                                        <input type="checkbox" name="remember">
                                        <i></i> Keep me logged in
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" >
                                    <!-- <button class="btn btn-primary  ladda-button" type="button" v-on:click="checkemail()" data-style="zoom-in"><span class="ladda-label">Sign in</span></button> -->
                                    <button v-if="!isEmailExist" class="btn btn-primary  ladda-button" type="submit" v-on:keyup.enter="checkemail()" v-on:click="checkemail()" data-style="zoom-in"><span class="ladda-label">Continue</span></button>
                                    <button v-if="isEmailExist" class="btn btn-primary  ladda-button" type="submit" v-on:keyup.enter="checkemail()" v-on:click="checkemail()" data-style="zoom-in"><span class="ladda-label">Login</span></button>

                                    <a href="/password/email" v-if="isEmailExist" class="btn btn-default" >Reset Password</a>

                                </div>
                                <div class="col-md-12" v-if="!isEmailExist">
                                    <div style="margin-top: 15px;">
                                      <h6>Press continue so we can check if you already have a profile with us</h6>
                                  </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12" style="margin-top: -10px;">
                                    <!-- <a href="#" data-target="#need_help_subscription" data-toggle="modal" target="_blank" style="margin-bottom: 10px;" class="btn btn-default ">Need Help ?</a>    -->

									<a href="#" data-target="#need_help_subscription" data-toggle="modal" target="_blank" style="margin-bottom: 10px;" class="btn btn-default btn-lg ">Need Help ?</a>

                                    <!-- <div id="freshwidget-button" data-html2canvas-ignore="true" class="freshwidget-button fd-btn-right" style="display: none; top: 235px;"><a href="javascript:void(0)" class="freshwidget-theme" style="color: white; background-color: rgb(23, 49, 117); border-color: white;">Need Help?</a></div>-->
                                </div>
                            </div>
                        </auth-login>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="box-static box-border-top padding-30 margin-bottom-30">
                                    <div class="box-title margin-bottom-30">
                                        <h4 >Create your Tax Faculty Profile here</h4>
                                    </div>
                                    <a href="/auth/register" style="margin-bottom: 10px;" class="btn btn-default ">Create Your Profile</a>   
                                </div>
                            </div>
                        </div>
                    </div> 
                    {!! Form::close() !!}
                    

                    {{--<div class="col-md-5">
                        <div class="box-static box-border-top padding-30">
                            <div class="box-title margin-bottom-30">
                                <h2 class="size-20">New Member ?</h2>
                            </div>
                            <div class="row">
                               <div class="col-md-12">
                                   <!-- <p>If you don't have a login, please register with us to continue.</p> -->
                                   <p>Don't have a profile with us, after clicking continue you will be taken to the profile creation form.</p>
                                   <!-- <h2 class="weight-300 letter-spacing-1 size-13" style="margin-bottom: 10px;"><span>Join more than 7,636.00 accountants</span></h2> -->
                                   <h2 class="weight-300 letter-spacing-1 size-13" style="margin-bottom: 10px;"><span>Join more than {{ $users }} accountants</span></h2> 

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
                    </div> --}}
                </div>

            </div>

        </div>
    </div>

</section>

@endsection
@section('scripts')
<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
    $(document).ready(function(){
        setTimeout(() => {
            $.cookie("upcoming_renewal_popup", null, { path: '/', secure: false });
        }, 2000);
    })
</script>
@endsection