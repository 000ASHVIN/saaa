  
            <div class="col-md-12">
                <p>Note :- Please use url slug only like "webinars_on_demand/show/2020-budget-tax-update"</p>
                <div class="form-group @if ($errors->has('old_url')) has-error @endif">
                    {!! Form::label('old_url', 'Old URL') !!}
                    {!! Form::input('text', 'old_url', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('old_url')) <p class="help-block">{{ $errors->first('old_url') }}</p> @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group @if ($errors->has('new_url')) has-error @endif">
                    {!! Form::label('new_url', 'New URL') !!}
                    {!! Form::input('text', 'new_url', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('new_url')) <p class="help-block">{{ $errors->first('new_url') }}</p> @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group @if ($errors->has('status')) has-error @endif">
                    {!! Form::label('status', 'Status') !!}
                    {!! Form::select('status', [
                        301 => '301',
                        302 => '302'
                    ],null, ['class' => 'form-control']) !!}
                    @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::submit('Edit SEO URL', ['class' => 'btn btn-success']) !!}
                </div>
            </div>