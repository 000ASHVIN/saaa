@extends('app')

@section('content')

@section('title')
    Contact Us
@stop

@section('intro')
    Contact Head Office
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('Contact') !!}
@stop
    <section>
        <div class="container">
            @if(!(Carbon\Carbon::now()->startOfDay() >= Carbon\Carbon::parse('15 December 2021')->startOfDay() && Carbon\Carbon::now()->startOfDay() <= Carbon\Carbon::parse('2 January 2022')->endOfDay()))
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    {{-- <h3>Drop us a line or just say Hello!</h3> --}}
                    <h3>Need Help ?</h3>

                    <support-ticket-popup :loggedin="{{ $user?1:0 }}" :categories="{{ $supportCategories }}" :page_name="1" inline-template>
                        <div class="">
                                
                                {!! Form::open(['method' => 'post', 'route' => 'support_tickets_popup.store', 'id'=>'support_contact_ticket_popup_form', 'v-on:submit.prevent'=> 'checkForm()']) !!}
                                <div class="form-group" v-bind:class="{ 'has-error': errors.subject }">
                                    {!! Form::label('subject', 'Subject') !!}
                                    {!! Form::input('text', 'subject', null, ['class' => 'form-control', 'v-model' => 'forms.support_ticket.subject']) !!}
                                    <p class="help-block" v-if="errors.subject" v-html="errors.subject"></p>
                                </div>
              
                                <div class="panel panel-default" v-if="createNew" style="margin:15px 0px;">
                                  <div class="panel-body">
                                      <p class="text-center">
                                        We couldn't found any email record. Please create new account  <a href="/auth/register" target="_blank">Create an account </a>
                                      </p>
                                  </div>
                                </div>
              
                                <div class="form-group" v-if="!loggedin" v-bind:class="{ 'has-error': errors.support_email }">
                                  {!! Form::label('support_email', 'Email Address') !!}
                                  {!! Form::input('email', 'support_email', null, ['class' => 'form-control', 'v-model' => 'forms.support_ticket.support_email']) !!}
                                  <p class="help-block" v-if="errors.support_email" v-html="errors.support_email"></p>
                                </div>
              
                                <div class="form-group" v-if="!loggedin" v-bind:class="{ 'has-error': errors.mobile }">
                                    {!! Form::label('mobile', 'Mobile Number') !!}
                                    {!! Form::input('text', 'mobile', null, ['class' => 'form-control', 'v-model' => 'forms.support_ticket.mobile', 'id'=>'support_ticket_mobile']) !!}
                                    <p class="help-block" v-if="errors.mobile" v-html="errors.mobile"></p>
                                </div>
                                
                                <?php
                                  $category_type = [
                                    'general' => 'General'
                                  ];
                                  
                                  if($user) {
                                    $category_type['technical'] = 'Technical';
                                  }
                                ?>
                                <div class="form-group" v-if="0" v-bind:class="{ 'has-error': errors.type }">
                                  {!! Form::label('type', 'Type') !!}
                                  {!! Form::select('type', $category_type, $user?null:'general', 
                                    ['class' => 'form-control', 
                                      'placeholder'=>'Type', 
                                      'v-model' => 'forms.support_ticket.type',
                                      '@change' => 'typeChanged()'
                                    ]) 
                                  !!}
                                  <p class="help-block" v-if="errors.type" v-html="errors.type"></p>
                                </div>
              
                                <div class="form-group" v-bind:class="{ 'has-error': errors.tag }">
                                    {!! Form::label('tag', 'Ticket Category') !!}
                                    <select name="tag" class="form-control" v-model="forms.support_ticket.tag" placeholder="Ticket Category">
                                      <option :value="category.id" :selected="key==0" v-for="(key, category) in categoriesdd" v-html="category.title"></option>
                                    </select>
                                    <p class="help-block" v-if="errors.tag" v-html="errors.tag"></p>
                                </div>
              
                                <div class="form-group" v-bind:class="{ 'has-error': errors.description }">
                                    {!! Form::label('description', 'Ticket Description') !!}
                                    {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'support_ticket_summernote', 'v-model' => 'forms.support_ticket.description']) !!}
                                    <p class="help-block" v-if="errors.description" v-html="errors.description"></p>
                                </div>
              
                                {!! Form::submit('Submit Ticket', ['class' => 'btn btn-primary']) !!}
            
                                @if ($user && $user->ViewResourceCenter())
                                  <p style="margin-top:15px;">
                                    <a href="{{ route('support_ticket.create') }}">Click here</a> to Ask a Technical Question
                                  </p>
                                @endif
            
                                @if (!$user)
                                  <p style="margin-top:15px;">
                                    Ask a Technical Question is available to subscribers. <a href="{{ route('profession.plans_and_pricing') }}">Click here</a> to find out more
                                  </p>
                                @endif
                                {!! Form::close() !!}
                        </div>
                      </support-ticket-popup>

                    {{-- {!! Form::open(array('route' => 'contact_store', 'class' => 'form')) !!}

                        <div class="row">
                            <div class="col-md-4">
                                {!! Form::label('name','Full Name') !!}
                                {!! Form::input('text','name', null , ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::label('email','Email Address') !!}
                                {!! Form::input('text','email', null , ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::label('number','Phone') !!}
                                {!! Form::input('text','number', null , ['class' => 'form-control', 'maxlength' => '10']) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                {!! Form::label('subject','Subject') !!}
                                {!! Form::input('text','subject', null , ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-4">
                                {!! Form::label('department','Department') !!}
                                {!! Form::select('department',array(
                                    '0' => 'Please Select....',
                                    'Accounts Department' => 'Accounts Department',
                                    'Service Support' => 'Service Support',
                                    'Please Contact Me' => 'Please Contact Me',
                                ), null, ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('body_message','Message *') !!}
                                {!! Form::textarea('body_message', null , ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="row" >	
                            <div class="col-md-12">	
                            @if(env('GOOGLE_RECAPTCHA_KEY'))	
                                <div class="g-recaptcha"	
                                    data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}">	
                                </div>	
                            @endif	
                            </div>	
                         </div>

                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::submit('Send Message', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>

                    {!! Form::close() !!} --}}
                </div>


                <div class="col-md-4 col-sm-4">
                    <h2>Visit Us</h2>
                    <div id="map" class="height-400"></div>
                    <hr />

                    <p>
                        <span class="block"><strong><i class="fa fa-map-marker"></i> Address:</strong> Riverwalk Office Park, 41 Matroosberg road, Block A, Ground floor, Ashley Gardens, Pretoria</span>
                        <br>
                        <span class="block"><strong><i class="fa fa-archive"></i> Postal address:</strong> PO BOX 712, Menlyn Retail Park 0063</span>
                        <br>
                        <span class="block"><strong><i class="fa fa-phone"></i> Phone:</strong> <a href="tel:012 943 7002">012 943 7002</a></span>
                        <br>
                        <span class="block"><strong><i class="fa fa-envelope"></i> Email:</strong> {{ config('app.email') }}</span>
                    </p>

                </div>
            </div>
            @else
            <div class="row">
                <div class="col-xs-12" style="text-align: center">
                    <img src="{{ asset('assets\themes\taxfaculty\img\Office_closure_2021(web).jpg') }}" alt="" style="max-width: 100%; max-height: 100vh;">
                </div>
            </div>
            @endif
        </div>
    </section>

@endsection

@section('scripts')
    <script type="text/javascript">

        jQuery(document).ready(function(){
            var map = new GMaps({
                div: '#map',
                lat: -25.784122,
                lng: 28.267150,
                scrollwheel: false
            });

            var marker = map.addMarker({
                lat: -25.784122,
                lng: 28.267150,
                title: "{{ config('app.name') }}"
            });

        });

    </script>
@stop