@extends('app')

@section('content')
    @section('title')
        Frequently asked questions (FAQ)
    @stop

    @section('breadcrumbs')
        {!! Breadcrumbs::render('faq') !!}
    @stop
<style>
    section#slider input {
        color:#000000;
        background-color:#FFFFFF;
        box-shadow: 4px 4px 7px #8e8e8e !important;
    }

    .search-header{
        background-image: url("/assets/themes/saaa/img/wod_search_pic.png") ;
        background-repeat: no-repeat;
        background-position: center center;
        background-size: cover;
        position: relative;
        height: 350px;
        /* text-align: center; */
    }

    section#slider {
        top: 25%;
        bottom: 25%;
        left: 15%;
        right: 15%;
        position: absolute;
        background-color:transparent;
    }

    .search-box {
        /* background-color: #8cc03c; */
        border-bottom: 0px;
    }

    .strip {
        text-align: center;
        bottom: 0px;
        position: absolute;
        background-color: #8cc03c;
        color: #FFFFFF;
        width: 100%;
        padding: 10px 0px;
    }
    section#slider form .btn {
        margin-top: 14px;
    }

    section#slider .btn-default {
        background-color: #8cc03c;
        /* box-shadow: 7px 7px 5px #8cc03c; */
    }

    section#slider .btn-default:hover {
        background-color: #8cc03c;
    }

    @media only screen and (max-width:768px){ 
        .search-text {
            margin-bottom: 0px !important;
        }
        .price-wrapper {
            margin-bottom: 0px !important;
        }
        section div.row>div{margin-bottom: 5px;}
    }

    section.with-banner {
        padding-top: 40px;
    }
</style>
<?php 
$i=0;
?>
 
 <section class="with-banner">
    <div class="row mix-grid">
        <div class="container">
            @include('faq.search_section')
            <div class="row" style="margin-top:20px;">
                    <div class="col-md-12">
                        @if(count($faq))
                            @foreach($faq as $f)
                                <div class="panel panel-default mix">
                                    <div class="panel-heading"><a href="{{ route('faq.general_faqs.view_solutions', $f->id) }}">{{ $f->question }}</a></div>
                                </div>
                            @endforeach
                        @else
                        <label>We did not find any faq matching this search criteria</label>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection