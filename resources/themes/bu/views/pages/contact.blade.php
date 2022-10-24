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
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <h3>Drop us a line or just say Hello!</h3>

                    {!! Form::open(array('route' => 'contact_store', 'class' => 'form')) !!}

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
                                    'Technical Support' => 'Technical Support',
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

                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::submit('Send Message', ['class' => 'btn btn-primary']) !!}
                            </div>
                        </div>

                    {!! Form::close() !!}
                </div>


                <div class="col-md-4 col-sm-4">
                    <h2>Visit Us</h2>
                    <div id="map" class="height-400"></div>
                    <hr />

                    <p>
                        <span class="block"><strong><i class="fa fa-map-marker"></i> Address:</strong> Ground Floor, Block 3, Fourways Office Park, Cnr Roos and Fourways Boulevard, Fourways</span>
                        <br>
                        <span class="block"><strong><i class="fa fa-archive"></i> Postal address:</strong> Postnet Suite 1, Private bag X75, Bryanston, 2021</span>
                        <br>
                        <span class="block"><strong><i class="fa fa-phone"></i> Phone:</strong> <a href="tel:010 593 0466">010 593 0466</a></span>
                        <br>
                        <span class="block"><strong><i class="fa fa-envelope"></i> Email:</strong> <a href="mailto:support@accountingacademy.co.za">support@accountingacademy.co.za</a></span>
                    </p>

                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script type="text/javascript">

        jQuery(document).ready(function(){
            var map = new GMaps({
                div: '#map',
                lat: -26.0172793,
                lng: 28.003927599999997,
                scrollwheel: false
            });

            var marker = map.addMarker({
                lat: -26.0172793,
                lng: 28.003927599999997,
                title: 'SA Accounting Academy'
            });

        });

    </script>
@stop