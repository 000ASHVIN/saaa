<section id="slider" class="hidden-sm hidden-xs">

{{--<div class="flexslider" data-pauseOnHover="true">--}}
{{--<ul class="slides">--}}
{{--<li>--}}
{{--<a href="/cpd" target="_blank">--}}
{{--<img src="/assets/frontend/images/demo/content_slider/22-min.jpg" alt="Slide 2">--}}
{{--</a>--}}
{{--</li>--}}
{{--</ul>--}}
{{--</div>--}}

{{--<center style="background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg')">--}}
{{--<div style="background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg'); height: 400px; background-color: #000000; position:relative; top: 55px;">--}}
{{--<h4 style="color: red; line-height: 30px; font-size: 30px">BLACK FRIDAY..</h4>--}}
{{--<h5 style="color: #ffffff; line-height: 30px;">Find great deals and save up to 75%.</h5>--}}
{{--<div class="countdown bordered-squared theme-style" data-from="November 23, 2018 00:00:00"></div>--}}
{{--<a href="{{ route('bf') }}" style="background-color: red; border-color: red" class="btn btn-primary">Click here to view deals</a>--}}
{{--</div>--}}
{{--</center>--}}

<!--
        data-controlNav="thumbnails" 	- thumbnails navigation
        data-controlNav="true" 		- arrows navigation
        data-controlNav="false"		- no navigation
        data-arrowNav="false"		- no arrows navigation
        data-slideshowSpeed="7000"	- slideshow speed
        data-pauseOnHover="true"	- pause on mouse over
    -->

    <img src="/assets/themes/taxfaculty/img/slider.jpg" alt="Slide 2" style="width: 100%">
    {{--<div class="text-center" style="position: absolute; bottom: 113px; width: 100%; background-color: #1731757a;">--}}
    {{--<br>--}}
    {{--<h2 style="text-transform: capitalize">Get the answers your clients need</h2>--}}
    {{--<h4>Technical support for you and your firm through CPD subscriptions including, <br> <strong>FAQs, expert opinions, acts online</strong></h4>--}}
    {{--<div class="row text-center">--}}
    {{--<a style="margin-top: 10px" href="#" class="btn btn-default">Sign Up Now </a>--}}
    {{--</div>--}}
    {{--<br>--}}
    {{--</div>--}}

    <div class="search-right">
        {!! Form::open(['method' => 'post', 'route' => 'home']) !!}
            <div class="input-button">
                {!! Form::input('text', 'search', request()->search?request()->search:null, ['class' => 'form-control', 'placeholder' => 'What do you want to learn?', 'style' => 'text-align:center']) !!}
                <button onclick="spin(this)" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </div>
            
        {!! Form::close() !!}
    </div>

</section>

<div class="search-section hidden-md hidden-lg hidden-xl">
    <div class="callout-dark heading-arrow-top">
        <div class="btn btn-xlg btn-primary size-10 fullwidth nomargin bopadding noradius ">
            <span class="font-lato size-30">
                <span style="font-size: 20px"><a class="headerlinkspan" href="{{ url('/wod')}}">Resource Centre </a> |
                    <a class="headerlinkspan" href="{{ url('/subscription_plans')}}"> TaxCPD </a> |
                    <a class="headerlinkspan" href="{{ url('/courses')}}"> Professional Certificates </a> |
                    <a class="headerlinkspan" href="{{ url('/courses')}}"> Tax Qualifications </a>
                </span></span>
        </div>
    </div>
    
    @include('pages.search.search')
    
    <div class="padding-10 alternate"></div>
    
    <div class="text-center alternate">
        <a class="btn btn-primary btn-lg" href="/subscription_plans"><i class="fa fa-search"></i> View Options</a>
    </div>
</div>

{{-- <div class="padding-10 alternate"></div> --}}