@extends('app')

@section('meta_tags')
    <title>Donation | Get Started</title>
    <meta name="description" content="A library of on demand learning videos covering a wide range of accountancy and practice management topics that are always available for viewing.">
    <meta name="Author" content="{{ config('app.name') }}"/>
@endsection

@section('content')

@section('title')
    Donation
@stop

@section('intro')
    Donation
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('Donate') !!}
@stop
<section id="slider" class="hidden-sm hidden-xs">
    <img src="{{url('/assets/themes/taxfaculty/img/Donor_Funding_Web_Banner_2800x600.jpg')}}" alt="Technical Resource Centre" style="width: 100%">
</section>
<section class="alternate">
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                
                <div class="col-md-12">
                    <app-subscriptions-donation :user="{{ auth()->user()?auth()->user()->load('cards'):null }}" inline-template>
                        <div>
                           <strong> Shape a future:</strong>
                           <br>
                           <br>

As a non-profit organisation, The Tax Faculty’s purpose is to empower and transform people’s lives. Over the past two years, we have successfully empowered over 440 learners in collaboration with public and private partnerships to fund, source and train unemployed youth to become world-class tax professionals. The Tax Faculty is a recognised SARS public benefit organisation and a level 1 B-BBEE contributor.

<br>
<br>

From as little as R100 you can help shape a future.  As an additional benefit you will receive a section 18A certificate and a B-BBEE letter of confirmation.
<br>
<br>
                        </div>
                        <p>*Please note that the details below are required in terms of section 18A of the Income Tax Act, 1962. </p>
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                            {!! Form::label('first_name', 'First Name') !!}
                            {!! Form::input('text', 'first_name', null, ['class' => 'form-control','v-model'=>'forms.subscription.first_name','v-on:blur'=>'validateForm']) !!}
                            @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                            {!! Form::label('last_name', 'Last Name') !!}
                            {!! Form::input('text', 'last_name', null, ['class' => 'form-control','v-model'=>'forms.subscription.last_name','v-on:blur'=>'validateForm']) !!}
                            @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('email')) has-error @endif">
                            {!! Form::label('email', 'Email Address') !!}
                            {!! Form::input('text', 'email', null, ['class' => 'form-control','v-model'=>'forms.subscription.email','v-on:blur'=>'validateForm']) !!}
                            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('company_name')) has-error @endif">
                            {!! Form::label('company_name', 'Company Name') !!}
                            {!! Form::input('text', 'company_name', null, ['class' => 'form-control','v-model'=>'forms.subscription.company_name','v-on:blur'=>'validateForm']) !!}
                            @if ($errors->has('company_name')) <p class="help-block">{{ $errors->first('company_name') }}</p> @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('cell')) has-error @endif">
                            {!! Form::label('cell', 'Cellphone Number') !!}
                            {!! Form::input('text', 'cell', null, ['class' => 'form-control','v-model'=>'forms.subscription.cell','v-on:blur'=>'validateForm']) !!}
                            @if ($errors->has('cell')) <p class="help-block">{{ $errors->first('cell') }}</p> @endif
                        </div>
                    </div>
                    
                    
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('amount')) has-error @endif">
                            {!! Form::label('amount', 'Amount') !!}
                            
                            
                            {!! Form::number( 'amount', null, ['class' => 'form-control','v-model'=>'forms.subscription.amount','v-on:blur'=>'validateForm','v-on:change'=>'selectEft']) !!}
                            @if ($errors->has('amount')) <p class="help-block">{{ $errors->first('amount') }}</p> @endif
                        </div>
                    </div>
                    
                    
                    
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('address')) has-error @endif">
                            {!! Form::label('address', 'Address') !!}
                            {!! Form::textarea('Address', null, ['class' => 'form-control', 'rows' => "3", 'cols' => '20','v-model'=>'forms.subscription.address','v-on:blur'=>'validateForm']) !!}
                            @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
                        </div>
                    </div>
                    <div class="panel-body">

                        <div>
                            <label class="col-md-4 control-label">Select one of the following:</label>
                            <div class="col-md-6">
                
                                <label class="radio">
                                    <input type="radio" v-model="forms.subscription.paymentOption" value="cc">
                                    <i></i> Credit Card
                                </label>
                
                                <label class="radio">
                                    <input type="radio" v-on:click="selectEft" v-model="forms.subscription.paymentOption" value="eft" >
                                    <i></i> Instant EFT
                                </label>
                
                                <label class="radio" v-if="user.availableWallet != 0">
                                    <input type="radio" v-model="forms.subscription.paymentOption" value="wallet">
                                    <i></i> U-Wallet
                                </label>
                
                            </div>
                        </div>
                    </div>
                    @include('pages.billing.billing')
                   
                    <div>
                        For more information on the programme and to get your organisation involved in igniting transformation in the tax profession through dedicated tax training and skills development,
                        <strong> Please contact Vanessa Fox, Head of Transformation and Development via email <a href="mail:vfox@taxfaculty.ac.za">vfox@taxfaculty.ac.za</a></strong>
                    </div>
                </app-subscriptions-donation>
                </div>
            </div>

        </div>
    </div>
</section>

{{--<section>--}}
    {{--<div class="container">--}}
        {{--<div class="row">--}}

            {{--<div class="col-md-12">--}}
                {{--<div class="col-md-6">--}}
                    {{--<img style="border: 1px solid #e3e3e3; padding: 5px; margin: 5px" src="https://imageshack.com/a/img923/9448/CUjSAA.png" class="portfolio-item development" alt="" width="47%">--}}
                    {{--<img style="border: 1px solid #e3e3e3; padding: 5px; margin: 5px" src="https://imageshack.com/a/img923/8536/ctlOqH.png" class="portfolio-item development" alt="" width="47%">--}}
                    {{--<hr>--}}
                    {{--<img style="border: 1px solid #e3e3e3; padding: 5px; margin: 5px" src="https://imageshack.com/a/img922/4592/dPClFt.png" class="portfolio-item development" alt="" width="47%">--}}
                    {{--<img style="border: 1px solid #e3e3e3; padding: 5px; margin: 5px" src="https://imageshack.com/a/img924/6024/bZR8zt.png" class="portfolio-item development" alt="" width="47%">--}}
                    {{--<br>--}}
                {{--</div>--}}
                {{--<div class="col-md-6">--}}
                    {{--<h4>{{ config('app.name') }} Mobile App! <br> <small>To find the app search for “<strong>{{ config('app.name') }}</strong>”</small></h4>--}}

                    {{--<p>Download our mobile app and start watching your webinars on any smart devices!</p>--}}
                    {{--<ol>--}}
                        {{--<li>Download the Mobile App.</li>--}}
                        {{--<li>Login with your {{ config('app.name') }} email address and password.</li>--}}
                        {{--<li>Start watching your latest webinars.</li>--}}
                        {{--<li>Download webinars to your device.</li>--}}
                        {{--<li>Never miss an upcoming event again.</li>--}}
                    {{--</ol>--}}

                    {{--<p>The app is now available on Google Play and Apple Store!</p>--}}
                    {{--<img src="https://imageshack.com/a/img924/9505/TzXbUL.jpg" width="8%" alt="Apple Store">--}}
                    {{--<img src="https://imageshack.com/a/img924/2204/QAYTls.png" width="8%" alt="Google Play">--}}

                    {{--<hr>--}}

                    {{--<h4>Google Play</h4>--}}
                    {{--<img src="https://imageshack.com/a/img921/2697/o0WyFz.png" alt="" width="20%">--}}
                {{--</div>--}}
            {{--</div>--}}

        {{--</div>--}}
    {{--</div>--}}
{{--</section>--}}

@endsection