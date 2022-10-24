<div id="assign_link_{{$link->id}}" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Link</h4>
            </div>
            <div class="modal-body">
                {!! Form::model($link, ['method' => 'post', 'route' => ['admin.product.link.update', $link->id]]) !!}
                    @include('admin.store.products.includes.forms.link_form', ['submit' => 'Update Link'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>