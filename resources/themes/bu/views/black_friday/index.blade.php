@extends('app')

@section('title', 'Signup for CPD Subscription')

@section('content')
    <section id="slider" class="hidden-sm hidden-xs">
        <center style="background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg')">
            <div style="background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg'); height: 420px; background-color: #000000; position:relative; top: 55px;">
                <h4 style="color: red; line-height: 30px; font-size: 30px">BLACK FRIDAY COUNT DOWN</h4>
                <h5 style="color: #ffffff; line-height: 30px;">Existing CPD Subscribers can save up to 22% on Draftworx this Friday 23 November 2018 </h5>
                <div class="countdown bordered-squared theme-style" data-from="November 24, 2018 00:00:00"></div>
                <a href="/draftworx" style="margin-bottom: 10px; background-color: red"; class="btn btn-red">Pre-order</a>
                <p style="font-weight: bold">Pre-order to get ahead of the queue. <br> Limited stock available!</p>

            </div>
        </center>
    </section>

    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <div class="container">
            <div class="col-md-12">
                <div class="heading-title heading-dotted text-center">
                    <h3 style="background-color: #800000; color: white">Draftworx for CPD Subscribers</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-xs-12" style="margin-bottom: 20px;">
                <div class="col-md-4 col-md-offset-4">
                    <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);padding: 10px 10px; min-height: 330px">
                        <h4 style="font-size: 40px;">
                            <small style="color: red; font-weight: bold; text-decoration: line-through; font-size: 18px!important;">
                                <sup style="    font-size: 14px; line-height: 60px;">R</sup>5246.00</small>
                            <br>
                            <sup>R</sup>4459.00<em>/1st year</em>
                        </h4>
                        <div style="min-height: 44px;">
                            <h5 style="color: #547698; font-size: 13px">DRAFTWORX</h5>
                            <br>
                            <small style="color: red; font-weight: bold">Save <sup>R</sup> R787</small>
                        </div>

                        <hr>
                        <p>Automate financial statements <br> and working papers</p>
                        <hr>

                        <div class="form-group">
                            <a href="/draftworx" class="btn btn-3d btn-primary" style="text-transform: uppercase; font-size: 14px">
                                Pre-order
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Section>
@endsection