<div class="modal fade" id="files-popup-product-{{$product->id}}" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Files for {{ $product->detailedTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            @if(count($files))
                                @foreach($files as $file)
                                    <div class="col-md-6">
                                        <a href="{{ $file->path }}" target="_blank" style="white-space: inherit;"
                                           class="btn btn-default btn-block text-left">
                                            <i class="fa fa-download"></i>&nbsp;{{ $file->name }}
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12">
                                    <div class="alert alert-info">There are no files to display at the moment.</div>
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
