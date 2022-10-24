<div id="assign_assessment" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign Assessment</h4>
            </div>
            <div class="modal-body">
                {!! Form::model($product, ['method' => 'post', 'route' => ['admin.product.assessment.store', $product->id]]) !!}
                    @include('admin.store.products.includes.forms.assessment_form', ['submit' => 'Save Link'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>