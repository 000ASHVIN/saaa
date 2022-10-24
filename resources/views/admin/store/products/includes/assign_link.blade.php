<div id="assign_link" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign New Link</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'route' => ['admin.product.link.store', $product->id]]) !!}
                    @include('admin.store.products.includes.forms.link_form', ['submit' => 'Save Link'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>