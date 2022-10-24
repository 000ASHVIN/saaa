<div class="panel panel-default">
    <div class="panel-heading">
        <div class="col-sm-12 col-md-8">
            <h4>Details</h4>
        </div>
    </div>
    <div class="panel-body">
        @if(!$edit)
            {!! Former::open()->route('admin.products.store') !!}
        @else
            {!! Former::open()->route('admin.products.update',$product->id) !!}
            {!! Former::populate($product) !!}
        @endif
        {!! Former::text('topic','Topic') !!}
        {!! Former::text('year','Year')->value(date('Y')) !!}
        {!! Former::text('title','Title') !!}
        {!! Former::text('price','Price') !!}
        {!! Former::checkbox('is_physical','')->text('Is physical') !!}
        {!! Former::text('cpd_hours','CPD Hours')->value(0) !!}
        {!! Former::text('stock','Stock')->value(0) !!}
        {!! Former::checkbox('allow_out_of_stock_order','')->text('Allow orders when stock is less than 1')->check('allow_out_of_stock_order') !!}
        @if(!$edit)
            {!! Former::submit('Create Product')->addClass('btn-primary') !!}
        @else
            {!! Former::submit('Update Product')->addClass('btn-primary') !!}
        @endif
        {!! Former::close() !!}
    </div>
</div>