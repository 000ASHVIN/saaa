@extends('app')

@section('title', 'Links & Resources')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">{{ $video->title }}</li>
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

                    @if($video->hours > 0)
                        <li><a href="#cpd" data-toggle="tab">CPD</a></li>
                    @endif

                    <li><a href="#assessments" data-toggle="tab">Assessments</a></li>
                    <li><a href="#links" data-toggle="tab">Files</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade in active" id="recordings">
                        @include('dashboard.includes.video-links-and-resources.recordings',['recordings' => []])
                    </div>
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
                                @elseif($video)
                                    <div class="col-md-4">
                                        <div class="border-box" style="text-align: center; background-color: #f2f2f2">
                                            <i class="fa fa-2x fa-certificate"></i>
                                            <h4>{{ $video->hours }} hour(s) CPD</h4>
                                            <a href="#" class="claim-cpd btn btn-primary">
                                                Claim My CPD
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="assessments">
                        @include('dashboard.includes.video-links-and-resources.assessments',['assessments' => $video->assessments])
                    </div>

                    <div class="tab-pane fade" id="links">
                        @include('dashboard.includes.video-links-and-resources.links',['links' => $links])
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).on('click', '.claim-cpd', function (e) {
            e.preventDefault();
            var confirm_url = "{{route('dashboard.video.cpd.claim',[$video->id])}}";
            swal({
                title: "Claim CPD",
                html: true,
                text: "<p>Please confirm that you have watched the entire video.</p>",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#5cb85c",
                confirmButtonText: "Confirm",
                cancelButtonText: "Close",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    window.location = confirm_url;
                }
            });
        });
    </script>
    <script>
        jQuery(document).ready(function () {
            $(document)on('click','.wod_video_play',function(){
                var video_id = $(this).closest('.col-md-4').find('#video_id').val();
                $.ajax({
                            type: "POST",
                            // cache: false,
                            // async: false,
                            url: '{{ route('webinars_on_demand.play') }}',
                            data:  {'_token':'{{ csrf_token() }}', 'video_id':video_id },
                            error: function (xhr, settings, exception) {
                                //alert('The update server could not be contacted.');
                            },
                            success: function (res) {
                            //    console.log(res);
                            }
                        });
            });
        });
        </script>

    <script>
        $(document).ready(function () {
            $('.download_webinar').on('click', function () {
                $('.modal').modal('hide');
                this.closest('form').submit();
                this.closest('form').reset();
            });
        });
    </script>
@stop