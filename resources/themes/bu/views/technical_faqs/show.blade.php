@extends('app')

@section('content')
    <section class="page-header hidden-print">
        <div class="container">
            <h1>{{ ucwords(\App\SolutionFolder::where('id', $folders->first()->solution_folder_id)->first()->name) }}</h1>
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
                                    <li><a href="{{ route('resource_centre.technical_faqs.index') }}">Technical Faqs</a></li>
                                    <li class="active">{{ ucwords(\App\SolutionFolder::where('id', $folders->first()->solution_folder_id)->first()->name) }}</li>
                                </ol>
                            </li>
                        </ol>
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            @foreach($folders->chunk(3) as $chunk)
                <div class="row">
                    @foreach($chunk as $folder)
                        <div class="col-md-4">
                            <div class="border-box margin-bottom-30" style="background-image: url('/assets/frontend/certificates/cpd/images/bg.jpg'); background-size: cover; text-align: center; min-height: 200px; display: -webkit-flex; -webkit-align-items: center; border: 1px solid #800000 ;">

                                <div class="center-block">
                                    <h4 style="vertical-align: center; width: 250px; color: #800000; min-height: 45px">
                                        {{ ucwords($folder->name) }}
                                    </h4>

                                    <h5 style="vertical-align: center; width: 250px; color: #800000; min-height: 45px">
                                        {{ str_limit($folder->description, 60) }}
                                    </h5>

                                    <hr>
                                    <div class="form-inline">
                                        <button style="background: white; color: #800000;" class="btn btn-primary">{{ count(\App\Solution::where('solution_sub_folder_id', $folder->sub_folder_id)->get()) }} Files</button>
                                        <a href="{{ route('resource_centre.technical_faqs.solutions', $folder->sub_folder_id) }}" class="btn btn-primary">View Files <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
@endsection