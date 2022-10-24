<div class="files">
    <div class="row">
        <div class="col-sm-12">
            {{--<div class="row">--}}
                {{--<div class="col-sm-12">--}}
                    {{--<h4>Files</h4>--}}
                {{--</div>--}}
            {{--</div>--}}
            @if(count($files) > 0)
                @foreach($files as $file)
                    <div class="file">
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="{{ $file->path }}" target="_blank"
                                   class="btn btn-default btn-block">
                                    <i class="fa fa-download"></i>&nbsp;{{ $file->name }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="row">
                    <div class="col-sm-12">
                        <p> No files available at the moment.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>