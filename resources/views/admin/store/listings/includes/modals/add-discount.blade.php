{!! Former::open()->route('admin.listings.assign-discount',$listing->id)->id('add-discount-form')->method('POST') !!}
{!! Former::select('discount_id','Discount')->options($discounts) !!}
{!! Former::close() !!}