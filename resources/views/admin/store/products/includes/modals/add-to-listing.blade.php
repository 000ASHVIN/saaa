{!! Former::open()->route('admin.products.assign-listing',$product->id)->id('add-to-listing-form')->method('POST') !!}
{!! Former::select('listing_id','Listing')->options($listings) !!}
{!! Former::close() !!}
