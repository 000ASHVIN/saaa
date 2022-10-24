<div class="modal fade" id="new_address" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Add New Address</h4>
            </div>

            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'route' => 'dashboard.edit.addresses.create']) !!}

                {!! Form::label('type','Please select your address type') !!}
                {!! Form::select('type', array(
                    ''           => 'Please Select....',
                    'physical'   => 'Physical Address',
                    'postal'     => 'Postal Address',
                    'billing'    => 'Billing Address',
                    'work'       => 'Work Address',
                    'additional' => 'Additional Address',
                ), null, ['class' => 'form-control']) !!}

                {!! Form::label('line_one','Address Line 1') !!}
                {!! Form::input('text', 'line_one', null, ['class' => 'form-control']) !!}

                {!! Form::label('line_two','Address Line 2') !!}
                {!! Form::input('text', 'line_two', null, ['class' => 'form-control']) !!}

                {!! Form::label('province', 'Province') !!}
                {!! Form::select('province', array_merge(['' => 'Please select....'],App\Province::provincesByCode), null, ['class' => 'form-control']) !!}

                {!! Form::label('country', 'Country') !!}
                {!! Form::select('country', array_merge(['' => 'Please select....'],App\Country::countriesByCode), null, ['class' => 'form-control']) !!}

                {!! Form::label('city','City') !!}
                {!! Form::input('text', 'city', null, ['class' => 'form-control']) !!}

                {!! Form::label('area_code','Area Code') !!}
                {!! Form::input('text', 'area_code', null, ['class' => 'form-control']) !!}

            </div>

            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="submit">Add Address</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>