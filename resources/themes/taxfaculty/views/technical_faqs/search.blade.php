@extends('app')

@section('content')
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

<section class="page-header hidden-print">
    <div class="container">
        <h1>Frequently asked questions (FAQ)</h1>
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
                                <li><a href="{{ route('resource_centre.technical_faqs.index') }}">FAQs</a></li>
                                <li class="active">Search</li>
                            </ol>
                        </li>
                    </ol>
                </li>
            </ol>
        </div>
    </div>
</section>

<section class="with-banner">
    <div class="row mix-grid">
        <div class="container">
            @include('technical_faqs.search_section')
            <div class="row" style="margin-top:20px;">
                <div class="col-md-12">
                    @foreach($faq as $f)
                        <div class="panel panel-default mix">
                            <div class="panel-heading">
                                <a href="{{ route('resource_centre.technical_faqs.view_solutions', $f->slug) }}">{{ $f->question }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>   
    </div>
</section>
@endsection