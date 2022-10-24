<div class="panel panel-default">
    <div class="panel-heading">
        <div class="col-sm-12 col-md-8">
            <h4>Discounts</h4>
        </div>
        <div class="col-sm-12 col-md-4 text-right">
            @if($edit)
                <button id="add-discount-button" class="btn btn-xs btn-primary"><i class="fa fa-link"></i>&nbsp;Add
                    discount
                </button>
            @endif
        </div>
    </div>
    <div class="panel-body">
        @if($edit)
            @if(count($discounts) > 0)
                <ul class="list-group">
                    @foreach($discounts as $discount)
                        <li class="list-group-item">
                            {{ $discount->detailedTitle }}
                            <div class="btn-group pull-right" role="group">
                                <a href="{{ route('admin.listings.unassign-discount',[$listing->id,$discount->id]) }}"
                                   class="btn btn-default btn-xs">
                                    <i class="fa fa-chain-broken"></i>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                @if($edit)
                    <p>This product does not have any discounts.</p>
                @endif
            @endif
        @else
            <p>First create the listing to add discounts.</p>
        @endif
    </div>
</div>