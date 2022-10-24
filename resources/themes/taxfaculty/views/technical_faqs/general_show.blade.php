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
                                    <li><a href="/faq">FAQs</a></li>
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
            <div class="row">
                <h4>{{ $question->question }}</h4>
                <hr>
                <p>{!! $question->answer !!}</p>

                <hr>
                <a href="/faq" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
        </div>
    </section>


@endsection