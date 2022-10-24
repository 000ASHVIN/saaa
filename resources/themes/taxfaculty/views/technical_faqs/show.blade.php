@extends('app')

@section('content')
<style>
    
.search-form {
    padding: 10px;
    background-color: rgba(0, 0, 0, 0.05);
    /* border: 1px solid #e3e3e3; */
    border-radius: 15px;
  }
  
  input.form-control.event-title-filter.text-center {
    border: none !important;
    border-radius: 10px;
  }
  
  button.btn.btn-primary.find_course {
    border: none !important;
    border-radius: 10px;
    padding: 0 48px 0px 48px;
    margin-top: 15px;
  }
   .search-form .form-control {
        margin-top: 15px  !important;
        margin-bottom: 15px !important;
    }
  
  button.btn.btn-primary.download_brochure.form-control {
    border: none !important;
    border-radius: 6px;
  }
  
  a.btn.btn-primary.form-control.change-color {
    border: none !important;
    border-radius: 6px;
    padding: 7px 18px 0 18px;
  }
  
  .brochure-popup-background {
    border-radius: 25px;
  }

  .faq-container{
        margin : 0 5% 0 5%;
    }

    .panel-heading[data-toggle="collapse"]:after {
        font-family: 'Glyphicons Halflings';
        content: "\e258"; /* "play" icon */
        position: absolute;
        color: #8cc03c;
        font-size: 18px;
        line-height: 22px;
        right: 20px;
        top: calc(50% - 10px);

    /* rotate "play" icon from > (right arrow) to down arrow */
        -webkit-transform: rotate(-90deg);
        -moz-transform:    rotate(-90deg);
        -ms-transform:     rotate(-90deg);
        -o-transform:      rotate(-90deg);
        transform:         rotate(-90deg);

    }
    .panel-heading[data-toggle="collapse"].collapsed:after {
        /* rotate "play" icon from > (right arrow) to ^ (up arrow) */
        -webkit-transform: rotate(90deg);
        -moz-transform:    rotate(90deg);
        -ms-transform:     rotate(90deg);
        -o-transform:      rotate(90deg);
        transform:         rotate(90deg);
    }
    .btn-link.panel-heading:hover {
        text-decoration:none;
    }
    .card .card {
        margin-top: 10px;
        margin-bottom: 0px;
    }

    
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

    /* Accordians styling */
    .faq-accordians .btn.accordion-toggle{
        white-space: normal;
        color: #8cc03c; 
        font-weight:bold; 
        font-size:15px; 
        width: 100%; 
        height:auto;
        text-align: left;
        padding: 17px 30px 17px 15px; 
        text-decoration:none;
    }

    .faq-accordians .btn.accordion-toggle::after {
        right: 12px;
    }

    .faq-accordians .card-header {
        background-color: #f5f5f5!important; 
        box-shadow: #b7b7b7 0px 1px 3px;
    }

</style>

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
                                    <li class="active">Technical Frequently Asked Questions</li>
                                </ol>
                            </li>
                        </ol>
                    </li>
                </ol>
            </div>
        </div>
    </section> 

    @if(auth()->check() && auth()->user()->ViewResourceCenter())

        <section class="with-banner">
            <div class="row mix-grid">
                <div class="container">
                    @include('technical_faqs.search_section')
                    <div class="row" style="margin-top:20px;"> 
                        <div class="col-md-4 faq-accordians">
                            @foreach($arrCategories as $category)
                                <?php
                                    $level = 0;
                                ?>
                                @include('technical_faqs.accordian')
                            @endforeach
                        </div>

                        <div class="col-md-8 faq_sections">
                            @if(count($faq))
                                @foreach($faq as $f)
                                    <div class="panel panel-default faq_question filter_result faq_cat_{{$f->sub_folder}} 
                                    @if($f->categories->count())
                                    {{ implode(" ", @$f->categories->pluck('slug')->toArray()) }} @endif  mix">
                                        <div class="panel-heading"><a href="{{ route('resource_centre.technical_faqs.view_solutions', $f->slug) }}">{{ $f->question }}</a></div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
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
                            <a class="btn btn-default" href="/subscription_plans"><i class="fa fa-arrow-right"></i> View Packages</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    @endif
    
@endsection


@section('scripts')
    <script>
        
        $(document).ready(function(){
            setTimeout(function(){     
                // $('.faq_sections .panel-default').each(function(){
                //     $(this).css('display','none');
                // })
                $('.faq_question').each(function(){
                    $(this).css('display','block');
                })
                }, 
            1000);

            $('.custom_filter').on('click', function(e){
                $('.filter_result').hide();
                var filter = $(this).data('filter');
                var arrFilters = filter.split(" ");
                for(i=0; i<arrFilters.length; i++) {
                    if(arrFilters[i]!="") {
                        $('.filter_result.' + arrFilters[i]).show();
                    }
                }
            });
        });
    </script>
@endsection