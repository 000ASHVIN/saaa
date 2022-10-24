@extends('app')

@section('title', $video->title)
@section('intro', 'Webinar On Demand Checkout')

@section('content')
    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg'); padding-top: 40px;">

        <webinar-on-demand-checkout :video="{{ $video }}"
                                    :user="{{ auth()->user()->load('cards') }}"
                                    :donations="{{ env('DONATIONS_AMOUNT') }}"
                                    inline-template>
            <div class="container">
                <div id="app">

                    <div class="col-md-3">
                        <div class="border-box"
                             style="background-color: white; margin-bottom: 15px; padding: 0px; min-height: 240px; border-color: #ffffff; -webkit-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); -moz-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75);">
                            <div class="ribbon" style="right: 13px;">
                                <div class="ribbon-inner" style="font-size: 10px">@{{ video.categories.title }}</div>
                            </div>
                            <img style="width: 100%!important; margin-bottom: 5px" src="/assets/frontend/images/webinar_on_demand_image.jpg" alt="...">
                            <div class="padding" style="padding: 15px; text-align: center">
                                <div class="w_title" style="min-height: 20px; font-weight: bold">
                                    <p class="text-left">>@{{ video.title }}</p>
                                    <p class="text-left">CPD Hours: @{{ video.hours }}</p>
                                    <p class="text-left">Price: R{{ number_format($video->amount, 2, ".", "") }}</p> 
                                    <p class="text-left">Tag: <span class="label label-default-blue">@{{ video.tag | capitalize }} Recording</span></p>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('webinars_on_demand.show', $video->slug) }}" class="btn btn-primary btn-block"><i class="fa fa-arrow-left"></i> Back</a>
                        <hr>
                        <div class="panel panel-default">
                            <div class="panel-heading">Cart Summary</div>
                            <table class="table">
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong>@{{ total | currency 'R'}}</strong></td>
                                </tr>
                            </table>
                        </div>

                        <div class="alert alert-info">
                            <p><strong>NOTE</strong></p>
                            <p>Once your purchase has been completed, the video will be available under the "My Webinars" tab within your profile.</p>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <div class="row">
                            @include('includes.donation', ['vif' => "donations", 'vmodel' => "forms.webinarCheckout.donations"])
                        </div>
                        <div class="row" v-if="total>0">
                            @include('webinars_on_demand.includes.billing_options')
                            @include('webinars_on_demand.includes.credit_card')
                            @include('webinars_on_demand.includes.eft')
                        </div>

                        {{-- Free Registrations --}}
                        <div class="row"
                                       v-if="total == 0">
                               @include('webinars_on_demand.includes.simple')
                        </div>
                    </div>
                </div>
            </div>
        </webinar-on-demand-checkout>
    </section>
@stop

@section('scripts')
    <script src="/assets/frontend/plugins/form.masked/jquery.maskedinput.js"></script>
@stop