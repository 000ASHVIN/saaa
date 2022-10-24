@extends('app')

@section('content')

@section('title')
    Frequently asked questions (FAQ)
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('faq') !!}
@stop
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
        color: #504953;
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
        /* background-color: #173175; */
        border-bottom: 0px;
    }

    .strip {
        text-align: center;
        bottom: 0px;
        position: absolute;
        background-color: #173175;
        color: #FFFFFF;
        width: 100%;
        padding: 10px 0px;
    }
    section#slider form .btn {
        margin-top: 14px;
    }

    section#slider .btn-default {
        background-color: #173175;
        /* box-shadow: 7px 7px 5px #173175; */
    }

    section#slider .btn-default:hover {
        background-color: #173175;
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
        color: #504953; 
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
<section class="with-banner">
    <div class="row mix-grid">
        <div class="container-fluid faq-container">
            @include('faq.search_section')
            <div class="row" style="margin-top:20px;"> 
                <div class="col-md-4 faq-accordians">
                    @if($arrCategories)
                        @foreach($arrCategories as $category)
                            <?php
                                $level = 0;
                            ?>
                            @include('faq.accordian')
                        @endforeach
                    @endif

                </div>

                <div class="col-md-8 faq_sections">
                    @if(count($faq))
                        @foreach($faq as $f)
                            <div class="panel panel-default filter_result faq_question faq_cat_{{$f->sub_folder}} 
                            @if($f->categories->count())
                            {{ implode(" ", @$f->categories->pluck('slug')->toArray()) }} @endif  mix">
                                <div class="panel-heading"><a href="{{ route('faq.general_faqs.view_solutions', $f->id) }}">{{ $f->question }}</a></div>
                            </div>
                        @endforeach
                    @else
                        <p>No FAQs Found</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

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

        })
    </script>
@endsection