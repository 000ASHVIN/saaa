<div id="change_debit_order_@{{ result.id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('bank', 'Bank Name') !!}
                    {!! Form::input('text', 'bank', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('type', 'Account Type') !!}
                    {!! Form::input('text', 'type', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('number', 'Account Number') !!}
                    {!! Form::input('text', 'number', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('branch_code', 'Branch Code') !!}
                    {!! Form::input('text', 'branch_code', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('branch_name', 'Branch Name') !!}
                    {!! Form::input('text', 'branch_name', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('peach', 'Migrated to Peach Payments ?') !!}
                    {!! Form::select('peach', [
                        '1' => 'Yes',
                        '0' => 'No'
                    ],null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group @if ($errors->has('otp')) has-error @endif">
                    {!! Form::label('otp', 'OTP (One Time Pin)') !!}
                    {!! Form::input('text', 'otp', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('otp')) <p class="help-block">{{ $errors->first('otp') }}</p> @endif
                </div>

                <div class="form-group">
                    {!! Form::label('billable_date', 'Debit Date') !!}
                    {!! Form::select('billable_date', [
                        null => 'Please Select',
                        '1' => '1',
                        '15' => '15',
                        '20' => '20',
                        '25' => '25',
                        '30' => '30',
                    ], null, ['class' => 'form-control']) !!}
                </div>

                @if (auth()->user()->hasRole('super'))
                    <div class="form-group">
                        {!! Form::label('skip_next_debit', 'Skip the next debit order') !!}
                        {!! Form::select(null, [
                            true => 'Skip next debit order',
                            false => 'Allow next debit order',
                        ],['class' => 'form-control']) !!}
                    </div>
                @endif

                <div class="form-group">
                    {!! Form::label('note', 'Please add your note!') !!}
                    {!! Form::textarea('note', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::hidden('has_been_contacted', true, ['class' => 'form-control']) !!}
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn btn-default" @click.prevent="updateDetails">Update</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>