<div class="modal fade" id="links-popup-product-{{$product->id}}" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Links for {{ $product->detailedTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    @if(count($links))
                        @foreach($links as $link)
                            <div class="col-md-12">
                                <a href="{{ $link->url }}" target="_blank" style="white-space: inherit;"
                                   class="btn btn-primary btn-block text-left">
                                    <i class="fa fa-download"></i>&nbsp;Download {{ $link->name }}
                                </a>
                                <br>
                            </div>
                        @endforeach
                    @else
                        <div class="col-md-12">
                            <div class="alert alert-info">There are no links to display at the moment.</div>
                        </div>
                    @endif
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
