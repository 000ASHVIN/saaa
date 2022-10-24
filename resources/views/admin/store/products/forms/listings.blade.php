<div class="panel panel-default">
    <div class="panel-heading">
        <div class="col-sm-12 col-md-8">
            <h4>Listings</h4>
        </div>
        <div class="col-sm-12 col-md-4 text-right">
            @if($edit)
                <button id="add-to-listing-button" class="btn btn-xs btn-primary"><i class="fa fa-link"></i>&nbsp;Add to listing</button>
            @endif
        </div>
    </div>
    <div class="panel-body">
        @if($edit)
            @if(count($listings) > 0)
                <ul class="list-group">
                    @foreach($listings as $listing)
                        <li class="list-group-item">
                            {{ $listing->title }}
                            <div class="btn-group pull-right" role="group">
                                <a href="{{ route('admin.products.unassign-listing',[$product->id,$listing->id]) }}"
                                   class="btn btn-default btn-xs">
                                    <i class="fa fa-chain-broken"></i>
                                </a>
                                <a href="{{ route('admin.listings.edit',$listing->id) }}"
                                   class="btn btn-default btn-xs">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                @if($edit)
                    <p>This product is not in any listings.</p>
                @endif
            @endif
        @else
            <p>First create the product to add listings.</p>
        @endif
    </div>
</div>