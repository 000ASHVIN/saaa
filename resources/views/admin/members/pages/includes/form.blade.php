<div class="row">
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

           
           <div class="form-group" style="margin-top:20px">
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="submit">{{$button}}</button>
          </div>
          </div>
