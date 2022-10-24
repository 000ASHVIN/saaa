<div class="modal fade" id="edit_address" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Edit your address</h4>
            </div>

            <div class="modal-body">
                {!! Form::open() !!}
                {!! Form::label('address_line_1','Address Line 1') !!}
                {!! Form::input('text', 'address_line_1', null, ['class' => 'form-control']) !!}

                {!! Form::label('address_line_2','Address Line 2') !!}
                {!! Form::input('text', 'address_line_2', null, ['class' => 'form-control']) !!}

                {!! Form::label('country_select', 'Country') !!}
                {!! Form::select('country_select', array(
                    '0' => 'Please Select....',
                    'south_africa' => 'South Africa',
                    'south_america' => 'South America',
                ), null, ['class' => 'form-control']) !!}

                {!! Form::label('city','City') !!}
                {!! Form::input('text', 'city', null, ['class' => 'form-control']) !!}

                {!! Form::label('postal_code','Postal Code') !!}
                {!! Form::input('text', 'postal_code', null, ['class' => 'form-control']) !!}
                {!! Form::close() !!}
            </div>

            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>