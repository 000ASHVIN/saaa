<div class="panel panel-default">
    @if(!$edit)
        <div class="panel-heading">
            <div class="col-sm-12 col-md-8">
                <h4>Recordings</h4>
            </div>
            <div class="col-sm-12 col-md-4 text-right">
                
            </div>
        </div>
    @endif
    <div class="panel-body">
        @if($edit)
            <button id="add-to-event-venue-button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#add-to-event-venue-modal">
                <i class="fa fa-link"></i>&nbsp;Add
                recording
            </button>
            <br/><br/>
            @if(count($video->recordings) > 0)
                <ul class="list-group">
                    @foreach($video->recordings as $recording)
                        @if($recording->pricing)
                        <li class="list-group-item" style="margin-bottom: 10px">
                            {{ $recording->pricing->event->name }}
                            <br>
                            <small>{{ ucfirst($recording->pricing->name) }}</small>
                            <div class="btn-group pull-right" role="group">
                                <a href="{{ route('admin.recordings.destroy',$recording->id) }}"
                                   class="btn btn-default btn-xs">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </li>
                        @endif
                    @endforeach
                </ul>
            @else
                @if($edit)
                    <p>This video is not in any recordings.</p>
                @endif
            @endif
        @else
            <p>First create the video to add recordings.</p>
        @endif
    </div>
</div>