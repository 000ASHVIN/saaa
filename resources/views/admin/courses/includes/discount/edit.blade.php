<div id="{{$code->id}}_discount_create" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Discount</h4>
            </div>

            <div class="modal-body">
                {!! Form::model($code, ['method' => 'post', 'route' => ['admin.course.discount.update', $code->code]]) !!}
                    @include('admin.courses.includes.discount.form', ['submit' => 'Update Discount'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>