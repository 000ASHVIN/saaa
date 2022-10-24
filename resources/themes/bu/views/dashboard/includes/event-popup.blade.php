<?php $webinars = $ticket->pricing->webinars; ?>
<div class="modal fade" id="event-popup" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="margin-bottom-1 text-center">
                            Links and resources
                        </h3>
                        <br>
                        @if($ticket->venue->type == 'online')
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Webinars / Recordings</h4>
                                </div>
                            </div>
                            @if($webinars && count($webinars) > 0)
                                @foreach($webinars as $webinar)
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if($webinar->type == 'live')
                                                <a href="{{ $webinar->url }}" target="_blank"
                                                   class="btn btn-primary btn-block">
                                                    <i class="fa fa-desktop"></i>Join Webinar
                                                </a>
                                            @elseif($webinar->type == 'recording')
                                                <a href="{{ $webinar->url }}" target="_blank"
                                                   class="btn btn-primary btn-block">
                                                    <i class="fa fa-desktop"></i>View Recording
                                                </a>
                                            @endif
                                        </div>

                                        @if($webinar->passcode)
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon">Password</span>
                                                    <input style="text-align: center; font-weight: bold;" type="text"
                                                           class="form-control" value="{{ $webinar->passcode }}">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>Webinar links not yet available.</p>
                                    </div>
                                </div>
                            @endif
                            <hr>
                        @endif

                        @if($ticket->venue->type == 'face-to-face' || $ticket->venue->type == 'conference')
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Venue</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p>
                                        <strong>{{ $ticket->venue->name }}</strong>
                                        <br>{{ $ticket->venue->address_line_one }}
                                        @if($ticket->venue->address_line_two != '')
                                            <br>{{ $ticket->venue->address_line_two }}
                                        @endif
                                        @if($ticket->venue->city != '')
                                            <br>{{ $ticket->venue->city }}
                                        @endif
                                        @if($ticket->venue->area_code != '')
                                            &nbsp;({{ $ticket->venue->area_code }})
                                        @endif
                                        @if($ticket->venue->province != '')
                                            <br>{{ $ticket->venue->province }}
                                        @endif
                                        @if($ticket->venue->country != '' && $ticket->venue->country != 'South Africa')
                                            <br>{{ $ticket->venue->country }}
                                        @endif
                                    </p>
                                </div>
                                {{--image and embed here--}}
                            </div>

                            <hr>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <h4>Files</h4>
                            </div>
                        </div>
                        <div class="row">
                            @if(count($ticket->files) > 0)
                                @foreach($ticket->files as $file)
                                    <div class="col-md-6">
                                        <a href="{{ $file->path }}" target="_blank"
                                           class="btn btn-default btn-block">
                                            <i class="fa fa-download"></i>&nbsp;{{ $file->name }}
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-6">
                                    <p> No files available at the moment.</p>
                                </div>
                            @endif
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <h4>Links</h4>
                            </div>
                        </div>
                        <div class="row">
                            @if(count($ticket->links) > 0)
                                @foreach($ticket->links as $link)
                                    <div class="col-md-6">
                                        <a href="{{ $link->url }}" target="_blank"
                                           class="btn btn-default btn-block">
                                            <i class="fa fa-link"></i>&nbsp;{{ $link->name }}
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-6">
                                    <p> No links available at the moment.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
