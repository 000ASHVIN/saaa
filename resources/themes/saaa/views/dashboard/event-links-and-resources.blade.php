@extends('app')

@section('title', 'Links & Resources')

@section('breadcrumbs')
<ol class="breadcrumb" style="padding: 0px">
    <li class="active">
        <ol class="breadcrumb" style="padding: 0px">
            <li class="active">
                <ol class="breadcrumb">
                    <li><a href="/dashboard">Dashboard</a></li>
                    <li class="active">{{ $ticket->event->name }}</li>
                </ol>
            </li>
        </ol>
    </li>
</ol>
@stop

@section('content')
<section>
    <div class="container">
        @include('dashboard.includes.sidebar')

        <div class="col-lg-9 col-md-9 col-sm-9">
            <ul class="nav nav-tabs nav-top-border">
                <li class="active"><a href="#recordings" data-toggle="tab">Recording(s)</a></li>
                <li><a href="#webinars" data-toggle="tab">Webinar(s)</a></li>

                @if($ticket->venue->type == 'face-to-face' || $ticket->venue->type == 'conference')
                <li><a href="#Venue" data-toggle="tab">Venue</a></li>
                @endif

                @if($ticket->pricing->cpd_verifiable != false)
                <li><a href="#cpd" data-toggle="tab">Certificate</a></li>
                @endif

                <li><a href="#assessments" id="assesment-tab" data-toggle="tab">Assessments</a></li>
                {{--<li><a href="#files" data-toggle="tab">Files</a></li>--}}
                <li><a href="#links" data-toggle="tab">Resources</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade in active" id="recordings">
                    @include('dashboard.includes.links-and-resources.recordings',['recordings' => $ticket->pricing->recordings])
                </div>

                <div class="tab-pane fade" id="webinars">
                    @if($ticket->venue->type == 'online')
                    @include('dashboard.includes.links-and-resources.webinars',['webinars' => $ticket->pricing->webinars])
                    @else
                    <p>The Webinar recording is not available.</p>
                    @endif
                </div>

                @if($ticket->venue->type == 'face-to-face' || $ticket->venue->type == 'conference')
                <div class="tab-pane fade" id="Venue">
                    @include('dashboard.includes.links-and-resources.venue',['venue' => $ticket->venue])
                </div>
                @endif

                <div class="tab-pane fade" id="cpd">

                    @if($hasCPD)
                    <div class="row">
                        @if($claimedCPD)
                        <div class="col-md-4">
                            <div class="border-box" style="text-align: center; background-color: #f2f2f2">
                                <i class="fa fa-2x fa-certificate"></i>
                                <h4>{{$cpd->hours}} hour(s) CPD awarded</h4>
                                <a target="_blank" href="{{route('dashboard.cpd.certificate',[$cpd->id])}}" class="btn btn-primary">
                                    <i class="fa fa-eye"></i> View Certificate
                                </a>
                            </div>
                        </div>
                        @elseif($canManuallyClaimCPD && $ticket->event->isPast())
                        <div class="col-md-4">
                            <div class="border-box" style="text-align: center; background-color: #f2f2f2">
                                <i class="fa fa-2x fa-certificate"></i>
                                <h4>{{ $ticket->pricing->cpd_hours }} hour(s) CPD</h4>
                                <?php
                                $count_passed = 0;
                                if ($ticket->event->assessments->count() > 0) {
                                    foreach ($ticket->event->assessments as $assessment) {
                                        if (!$assessment->hasPassed()) {
                                            $count_passed++;
                                        }
                                    }
                                }
                                ?>
                                @if(!$count_passed > 0)
                                <a href="#" class="claim-cpd btn btn-primary">
                                    Claim My CPD
                                </a>
                                @else
                                <a href="#assessments" data-toggle="tab" class="complete-assesment-btn btn btn-primary">
                                    Complete Assesment
                                </a>
                                @endif
                            </div>
                        </div>
                        @else
                        <div class="col-md-4">
                            <div class="border-box" style="text-align: center; background-color: #f2f2f2">
                                <i class="fa fa-2x fa-certificate"></i>
                                <h4>{{ $ticket->pricing->cpd_hours }} hour(s) CPD</h4>
                                <?php
                                $count_passed = 0;
                                if ($ticket->event->assessments->count() > 0) {
                                    foreach ($ticket->event->assessments as $assessment) {
                                        if (!$assessment->hasPassed()) {
                                            $count_passed++;
                                        }
                                    }
                                }
                                ?>
                                @if(!$count_passed > 0)
                                <a href="#" class="claim-cpd btn btn-primary">
                                    Claim My CPD
                                </a>
                                @else
                                <a href="#assessments" data-toggle="tab" class="complete-assesment-btn btn btn-primary">
                                    Complete Assesment
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif
                        @else
                        @if(!$canManuallyClaimCPD && count($attempts) == 0)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="border-box" style="text-align: center; background-color: #f2f2f2">
                                    <i class="fa fa-2x fa-certificate"></i>
                                    <h4>Please complete the assessment.</h4>
                                    <a href="#assessments" class="btn btn-primary btn-complete-assessment" data-toggle="tab">Assessments</a>
                                </div>
                            </div>
                        </div>
                        @elseif(!$canManuallyClaimCPD && count($attempts) > 0 && $assessmentCPD)
                        <div class="col-md-4">
                            <div class="border-box" style="text-align: center; background-color: #f2f2f2">
                                <i class="fa fa-2x fa-certificate"></i>
                                <h4>{{$assessmentCPD->hours}} hour(s) CPD awarded</h4>
                                <a target="_blank" href="{{route('dashboard.cpd.certificate',[$assessmentCPD->id])}}" class="btn btn-primary">
                                    <i class="fa fa-eye"></i> View Certificate
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="col-md-4">
                            <div class="border-box" style="text-align: center; background-color: #f2f2f2">
                                <i class="fa fa-2x fa-certificate"></i>
                                <h4>{{ $ticket->pricing->cpd_hours }} hour(s) CPD</h4>
                                Pending.
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    @endif
                    </div>
                </div>

                <div class="tab-pane fade" id="assessments">
                    @include('dashboard.includes.links-and-resources.assessments',['assessments' => $ticket->event->assessments])
                </div>

                <div class="tab-pane fade" id="files">
                    @include('dashboard.includes.links-and-resources.files',['files' => $ticket->event->files])
                </div>

                <div class="tab-pane fade" id="links">
                    @include('dashboard.includes.links-and-resources.links',['links' => $links])
                </div>
            </div>

            {{--<hr>--}}
            {{----}}
            {{--@if(! $ticket->user->subscribed('cpd'))--}}
            {{--<a href="{{ route('cpd') }}" class="btn btn-xlg btn-primary size-10 fullwidth nomargin bopadding noradius">--}}
            {{--<span class="font-lato size-30">Join CPD Today</span>--}}
            {{--<span class="block font-lato">Join our Monthly Legislation Update CPD Subscription package for as little as R250.00 P/M</span>--}}
            {{--</a>--}}
            {{--@endif--}}
        </div>
    </div>
</section>
@stop

@section('scripts')
<script type="text/javascript">
    $(document).on('click', '.claim-cpd', function(e) {
        e.preventDefault();
        var confirm_url = "{{route('dashboard.tickets.cpd.claim',[$ticket->id])}}";
        swal({
            title: "Claim CPD",
            html: true,
            text: "<p>Please confirm that you have watched the entire webinar or attended the event.</p>",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#5cb85c",
            confirmButtonText: "Confirm",
            cancelButtonText: "Close",
            closeOnConfirm: false,
            closeOnCancel: true
        }, function(isConfirm) {
            if (isConfirm) {
                window.location = confirm_url;
            }
        });
    });
    $(document).on('click', '.complete-assesment-btn', function(e) {
        e.preventDefault();
        $('#assesment-tab').click();
    });
</script>

<script>
    $(document).ready(function() {
        $('.download_webinar').on('click', function() {
            $('.modal').modal('hide');
            this.closest('form').submit();
            this.closest('form').reset();
        });

        $('.btn-complete-assessment').click(function() {
            $('ul.nav li').removeClass('active');
            $('ul.nav li#assessments_tab').addClass('active');
        })
    });
</script>

@if (request()->has('webinars'))
<script>
    $(document).ready(function() {
        $('ul.nav li').removeClass('active');
        $('ul.nav li#webinars_tab').addClass('active');

        $('.tab-content .tab-pane').removeClass('in active');
        $('.tab-content .tab-pane#webinars').addClass('in active');
    });
</script>
@endif
@stop