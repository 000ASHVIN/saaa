@extends('app')

@section('title', 'My '.\Carbon\Carbon::now()->year.' CPD Events')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">My Events</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('styles')
<style>

    h4, h5 {
        overflow-wrap: break-word;
        word-wrap: break-word;
        hyphens: auto;
    }

    h5 {
        text-transform: capitalize;
    }

    .search-button {
        position: relative;
        top: 33px;
    }

    .modal-header button {
        position: relative;
        bottom: 22px;
    }

    .event-container-inner {
        max-width: 100%;
    }
    .events-btn-container {
        position: relative;
        top: 16px;
    }
    .upcoming-btn-container {
        position: relative;
        top: 38px;
    }
    .margin_price_hour{
        top: 10px;
    }
    .max-lines {
        display: block; /* or inline-block */
        text-overflow: ellipsis;
        word-wrap: break-word;
        overflow: hidden;
        max-height: 5.7em;
        line-height: 1.8em;
        }
        .category_rotate{
            background: #173175;;
            transform: rotate(-90deg);
        }
        /* Read More Button CSS */
            .read-more-state {
            display: none;
            }
            .read-more-target {
            opacity: 0;
            max-height: 0;
            font-size: 0;
            transition: .25s ease;
            }
            .read-more-state:checked ~ .read-more-wrap .read-more-target {
            opacity: 1;
            font-size: inherit;
            max-height: 999em;
            }
            .read-more-state ~ .read-more-trigger:before {
            content: 'Read More';
            }
            .read-more-state:checked ~ .read-more-trigger:before {
            content: 'Read Less';
            }
            .read-more-trigger {
            cursor: pointer;
            display: inline-block;
            padding: 0 .5em;
            color: #173175;
            font-size: .9em;
            line-height: 2;
            font-weight: bold;
            font-size: 15px;
            }
            /* End Read More Button CSS */

        /* Start new events view css */
        .new-events-view {
            min-height: 0px;
        }
        .new-events-view .btn-container .btn {
            min-width: 120px;
        }
        /* End new events view css */

        /* Filter Tab CSS*/
        .events-tab {
            list-style-type: none;
            padding: 0px;
            margin-bottom: 0px;
        }

        ul li.tablist {
            display: inline-block;
            margin-right: 4px;
        }

        ul li.tablist.active a {
            background-color: #173175;
            color: #ffffff;
            border: #173175 solid 1px;
        }
        /*End Filter tabs CSS*/

</style>
@stop

@section('content')
<style> 
    input.form-control.event-title-filter.text-center {
        border: none !important;
        border-radius: 10px;
    }

    .search-form .form-control {
        margin-top: 15px  !important;
        margin-bottom: 15px !important;
    }

    .search-form {
    padding: 20px;
    background-color: rgba(0, 0, 0, 0.05);
    /* border: 1px solid #e3e3e3; */
    border-radius: 15px;
  }

</style>
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="alert alert-bordered-dotted margin-bottom-30">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">x</span><span
                                class="sr-only">Close</span></button>
                    <h4><strong>Event Tickets & Resources</strong></h4>

                    <p>
                        Please click on any of the following events to view your available tickets. <br>
                        For the related links and resources to the event please click on "Links & Resources" and go the required tab.
                    </p>

                    <p>To Claim CPD, Please click on Events & Resources and then go to the CPD Tab</p>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12" id="myTab">
                    <ul class="nav nav-tabs nav-top-border">
                        <li class="{{ $activeTab=='my_events'?'active':'' }}"><a href="#my_events" data-toggle="tab">My Events</a></li>
                        <li class = "{{ $activeTab=='upcoming_events'?'active':'' }}"><a href="#upcoming_events" data-toggle="tab">Upcoming Events</a></li>
                    </ul>

                    <div class="tab-content">

                        <div class="tab-pane fade in {{ $activeTab=='my_events'?'active':'' }}" id="my_events">
                            @include('dashboard.includes.my_events_tickets')
                        </div>

                        <div class="tab-pane fade in {{ $activeTab=='upcoming_events'?'active':'' }}" id="upcoming_events">
                        @include('dashboard.includes.upcoming_events_tickets')

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script>
        jQuery(document).ready(function () {
            var activeTabName = $(".events-tab li.active a").attr("data-id");
            $('#past_upcoming').val(activeTabName);
            if(activeTabName == 'past_events')
            {
                $('.past_event_cpd').show();
            }else{
                $('.past_event_cpd').hide();
            }
            $(document).on('click','ul li.tablist',function(){
                var activeTabName = $(".events-tab li.active a").attr("data-id");
                $('#past_upcoming').val(activeTabName);
                if(activeTabName == 'past_events')
                {
                    $('.past_event_cpd').show();
                }else{
                    $('.past_event_cpd').hide();
                }
            });

            $('#upcoming_filter').on('change', function(){
                var upcoming_filter = $(this).val();
                $('#hdn_upcoming_filter').val(upcoming_filter);
                $('#frm_upcoming_search').submit();
            });

            $('#completed_filter').on('change', function(){
                if($(this).prop('checked')) {
                    $('#hdn_completed_filter').val('1');
                }
                else {
                    $('#hdn_completed_filter').val('0');
                }
                $('#event_year').val('');
                $('#frm_my_tickets').submit();
            });
            
        });
    </script>
@stop