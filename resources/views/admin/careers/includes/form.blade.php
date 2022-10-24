                <div class="form-group">
                    {!! Form::label('department_id','Please select department') !!}
                    <select name="departments_id" class="form-control">
                        @foreach($departments as $department)
                            <option value="{{$department->id}}">{{$department->title}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    {!! Form::label('available','Is this job available? ') !!}
                    {!! Form::select('available', [
                        '1' => 'This postition is available',
                        '0' => 'This position has been taken.'
                    ], null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('title','Job Title') !!}
                    {!! Form::input('text','title', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('period','Job Period') !!}
                    {!! Form::select('period', [
                    '0' => 'Please select the period',
                    'Full Time' => 'Full Time',
                    'Part Time' => 'Part Time',
                    'Contact'   => 'Contract'
                    ], null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('location','Location') !!}
                    {!! Form::select('location', [
                    '0' => 'Please select the location',
                    'Johannesburg' => 'Johannesburg',
                    'Pretoria' => 'Pretoria'
                    ], null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('description','Job Description') !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('personality','Personality') !!}
                    {!! Form::input('text','personality', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('skills','Required Skills') !!}
                    {!! Form::input('text','skills', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::submit($SubmitButton, ['class' => 'btn btn-default']) !!}
                </div>