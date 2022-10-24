@extends('app')

@section('title')
    Account Overview
@stop

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">Rewards</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('styles')
    <style type="text/css">
        .bootstrap-dialog-message form, .bootstrap-dialog-message .form-group {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="owl-carousel buttons-autohide controlls-over hidden-sm hidden-xs hidden-md"
                     data-plugin-options='{"singleItem": false, "items": "1", "autoPlay": true, "navigation": true, "pagination": false}'>
 
                    <blockquote class="text-center" style="background-color: rgb(250, 250, 250); border: 5px solid rgba(0,0,0,0.1); margin-bottom: 0px; margin-top: 0px; padding: 10px">
                        <div class="heading-title heading-dotted text-center" style="margin-bottom: 5px">
                            <h3 style="background-color: #323264; color: white; margin-bottom: 10px">Claim my Rewards</h3>
                        </div>
                        <p>Additional benefits of subscribing with SA Accounting Academy. CPD subscribers gain access to the following rewards:</p>
                    </blockquote>
                </div>

                @if(count($sponsorList))
                @foreach($sponsorList as $s)
                
                <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-bottom: 15px">
                    <div class="col-md-2">
                   
                    
                        <img src="{{ asset('storage/'. $s->logo) }}" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                    </div>  
                    <div class="col-md-10" style="text-align: left">
                        <h4>{{ $s->title }}</h4>
                        <p>{!! $s->short_description !!}</p>  
                        <a href="{{ route('rewards.show', $s->slug) }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                    </div>
                </div>
                @endforeach  
                @endif 

             

            </div>
        </div>
    </section>
@stop