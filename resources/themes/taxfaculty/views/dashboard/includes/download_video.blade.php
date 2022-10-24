<div id="webinar{{$recording->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span style="color: red">Warning</span>: Video Privacy </h4>
            </div>
            {!! Form::open(['method' => 'Post', 'route' => ['dashboard.download_webinar', $recording->id]]) !!}
            {!! Form::hidden('video_id', $recording->video_id) !!}
            {!! Form::hidden('webinar_title', \App\Video::find($recording->video_id)->title) !!}

            <div class="modal-body">
                <p>
                    I {{ auth()->user()->first_name }} {{ auth()->user()->last_name }} with id
                    number {{ auth()->user()->profile->id_number }}
                    hereby declare that I will not redistribute {{ \App\Video::find($recording->video_id)->title }} Webinar and will only
                    continue watching this video on my personal / work
                    computer.
                </p>

                <p>
                    This video file will be tracked based upon IP Addresses and if any redistribution takes place, SA
                    Accounting Academy will issue a R1,000.00 fine that will be payable immediately and your account
                    will be discontinued.
                </p>

                <label><input name="privacy_policy" type="checkbox"> I understand and agree with the privacy policy</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success download_webinar">Proceed</button>
            </div>
            {!! Form::close() !!}
        </div>

    </div>
</div>