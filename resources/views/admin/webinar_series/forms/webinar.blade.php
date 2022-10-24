<div class="panel-heading">
        <div class="col-sm-12 col-md-8">
            <h4>Webinars</h4>
        </div>
        <div class="col-sm-12 col-md-4 text-right">
            @if($edit)
                <button id="add-webinar-to-series-button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#add-to-event-venue-modal">
                    <i class="fa fa-link"></i>&nbsp;Add Webinar
                </button>
            @endif
        </div>
    </div>
    <div class="panel-body webinar_list">
        @if($edit)
            @if(count($series_webinars) > 0)
                <ul class="list-group sortable">
                    @foreach($series_webinars as $series_webinar)
                        <li class="list-group-item row" style="margin-bottom: 10px; padding:10px 0px;" data-id="{{ $series_webinar->id }}" id="webinar_{{ $series_webinar->id }}">
                            <div class="col-md-10">
                                {{ $series_webinar->title }}
                                <br>
                                {{-- <small>{{ ucfirst($series_webinar->category) }}</small> --}}
                            </div>
                            <div class="col-md-2 text-right">
                                <div class="btn-group" role="group">
                                    <a href="javascript:removeWebinar('webinar_{{ $series_webinar->id }}');"
                                    class="btn btn-default btn-xs">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="javascript:void(0);" class="btn btn-xs btn-default">
                                        <i class="glyphicon glyphicon-sort sortable-handle"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                @if($edit)
                    <p>This series does not have any webinars.</p>
                @endif
            @endif
        @else
            <p>First create the series to add webinars.</p>
        @endif
    </div>
</div> 
