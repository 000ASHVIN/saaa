<div class="panel panel-default">
    <div class="panel-heading">
        <div class="col-sm-12 col-md-8">
            <h4>Details</h4>
        </div>
    </div>
    <div class="panel-body">
        @if(!$edit)
            {!! Former::open()->route('admin.listings.store') !!}
        @else
            {!! Former::open()->route('admin.listings.update',$listing->id) !!}
            {!! Former::populate($listing) !!}
        @endif
        {!! Former::text('title','Title') !!}
        {!! Former::select('category_id','Category')->options($categories) !!}
        {!! Former::textarea('description','Description')->class('ckeditor') !!}
        {!! Former::text('image_url','Image URL') !!}
        {!! Former::text('from_price', 'Price from') !!}
        {!! Former::select('discount_type', 'Discount type')->options(['percentage' => 'Percentage', 'amount' => 'Amount']) !!}
        {!! Former::select('is_active', 'Status')->options(['1' => 'Active', '0' => 'Inactive']) !!}
        {!! Former::text('discount','Discount')->value(0) !!}
        @include('admin.store.listings.forms.products',['products' => $products])
        @if(!$edit)
            {!! Former::submit('Create')->addClass('btn-primary') !!}
        @else
            {!! Former::submit('Update')->addClass('btn-primary') !!}
        @endif
        {!! Former::close() !!}
    </div>
</div>