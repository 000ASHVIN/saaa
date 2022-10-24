@extends('app')

@section('content')

<section class="page-header hidden-print">
    <div class="container">
        <h1>FAQs</h1>
    </div>
</section>

<section class="theme-color hidden-print" style="padding: 0px;">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb" style="padding: 0px">
                        <li class="active">
                            <ol class="breadcrumb">
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('resource_centre.home') }}">Resource Centre</a></li>
                                <li class="active">Technical FAQs</li>
                            </ol>
                        </li>
                    </ol>
                </li>
            </ol>
        </div>
    </div>
</section>

@if((auth()->user()->subscribed('cpd') && auth()->user()->subscription('cpd')->plan->price != '0') || (auth()->user()->hasCompany() && auth()->user()->hasCompany()->company && auth()->user()->hasCompany()->company->admin()->subscription('cpd') && auth()->user()->hasCompany()->company->admin()->subscription('cpd')->plan->price != '0'))

    <section>
        <div class="container">
            @foreach($folders->chunk(3) as $chunk)
                <div class="row">
                    @foreach($chunk as $folder)
                        <div class="col-md-4">
                            <div class="border-box margin-bottom-10" style="background-image: url('/assets/frontend/certificates/cpd/images/bg.jpg'); background-size: cover; text-align: center; min-height: 200px; display: -webkit-flex; -webkit-align-items: center; border: 1px solid #800000 ;">
                                <div class="center-block">
                                    <h4 style="vertical-align: center; width: 200px; color: #800000; min-height: 45px">{{ ucwords($folder->name) }}</h4>
                                    <hr>
                                    <div class="form-inline">
                                        <button style="background: white; color: #800000;" class="btn btn-primary">{{ count(\App\SolutionSubFolder::where('solution_folder_id', $folder->id)->get()) }} Files</button>
                                        <a href="{{ route('resource_centre.technical_faqs.show', $folder->id) }}" class="btn btn-primary">View Files <i class="fa fa-arrow-right"></i></a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
@else
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="border-box text-center" style="background-color: #fbfbfb; min-height:300px">
                        <i class="fa fa-lock" style="font-size: 12vh"></i>
                        <p>You do not have access to this page. <br> The Technical Resource Centre is part of the Designation CPD Subscription Packages.</p>
                        <a class="btn btn-primary" href="{{ route('resource_centre.home') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        <a class="btn btn-default" href="/cpd"><i class="fa fa-arrow-right"></i> View Packages</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
@endsection