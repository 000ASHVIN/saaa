@extends('app')

@section('title')

@stop

@section('content')
    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <div class="display-table">
            <div class="display-table-cell vertical-align-middle">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="box-static box-border-top padding-30">
                                <div class="box-title margin-bottom-30">
                                    <h2 class="size-20">Setup your debit order</h2>
                                    <p>Please complete the following fields in order to proceed with your debit order
                                        setup for your account.</p>
                                </div>

                                {!! Form::model($user, ['method' => 'post', 'route' => ['dashboard.store_debit_order', $user]]) !!}

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                                                {!! Form::label('first_name', 'First Name') !!}
                                                {!! Form::input('text', 'first_name', null, ['class' => 'form-control', 'disabled' => true]) !!}
                                                @if ($errors->has('first_name'))
                                                    <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                                            </div>

                                            <div class="form-group @if ($errors->has('id_number')) has-error @endif">
                                                {!! Form::label('id_number', 'ID Number') !!}
                                                {!! Form::input('text', 'id_number', null, ['class' => 'form-control required']) !!}
                                                @if ($errors->has('id_number'))
                                                    <p class="help-block">{{ $errors->first('id_number') }}</p> @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                                                {!! Form::label('last_name', 'Last Name') !!}
                                                {!! Form::input('text', 'last_name', null, ['class' => 'form-control', 'disabled' => true]) !!}
                                                @if ($errors->has('last_name'))
                                                    <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
                                            </div>

                                            <div class="form-group @if ($errors->has('cell')) has-error @endif">
                                                {!! Form::label('cell', 'Cell') !!}
                                                {!! Form::input('text', 'cell', null, ['class' => 'form-control required']) !!}
                                                @if ($errors->has('cell'))
                                                    <p class="help-block">{{ $errors->first('cell') }}</p> @endif
                                            </div>
                                        </div>
                                    </div>

                                <hr>

                                @if(count($user->debit))
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group @if ($errors->has('bank')) has-error @endif">
                                                {!! Form::label('bank', 'Bank') !!}
                                                {!! Form::input('text', 'bank', $user->debit->bank, ['class' => 'form-control']) !!}
                                                @if ($errors->has('bank'))
                                                    <p class="help-block">{{ $errors->first('bank') }}</p> @endif
                                            </div>

                                            <div class="form-group @if ($errors->has('branch_name')) has-error @endif">
                                                {!! Form::label('branch_name', 'Branch Name') !!}
                                                {!! Form::input('text', 'branch_name', $user->debit->branch_name, ['class' => 'form-control']) !!}
                                                @if ($errors->has('branch_name'))
                                                    <p class="help-block">{{ $errors->first('branch_name') }}</p> @endif
                                            </div>

                                            <div class="form-group">
                                                {!! Form::label('type', 'Account Type') !!}
                                                {!! Form::select('type', [
                                                    null => 'Please Select',
                                                    'savings' => 'Savings Account',
                                                    'cheque' => 'Cheque Account',
                                                    'other' => 'Other'
                                                ],$user->debit->type, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group @if ($errors->has('account_number')) has-error @endif">
                                                {!! Form::label('account_number', 'Account Number') !!}
                                                {!! Form::input('text', 'account_number', $user->debit->number, ['class' => 'form-control']) !!}
                                                @if ($errors->has('account_number'))
                                                    <p class="help-block">{{ $errors->first('account_number') }}</p> @endif
                                            </div>

                                            <div class="form-group @if ($errors->has('branch_code')) has-error @endif">
                                                {!! Form::label('branch_code', 'Branch Code') !!}
                                                {!! Form::input('text', 'branch_code', $user->debit->branch_code, ['class' => 'form-control']) !!}
                                                @if ($errors->has('branch_code'))
                                                    <p class="help-block">{{ $errors->first('branch_code') }}</p> @endif
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
                                                ],$user->debit->billable_date, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group @if ($errors->has('bank')) has-error @endif">
                                                {!! Form::label('bank', 'Bank') !!}
                                                {!! Form::input('text', 'bank', null, ['class' => 'form-control']) !!}
                                                @if ($errors->has('bank'))
                                                    <p class="help-block">{{ $errors->first('bank') }}</p> @endif
                                            </div>

                                            <div class="form-group @if ($errors->has('branch_name')) has-error @endif">
                                                {!! Form::label('branch_name', 'Branch Name') !!}
                                                {!! Form::input('text', 'branch_name', null, ['class' => 'form-control']) !!}
                                                @if ($errors->has('branch_name'))
                                                    <p class="help-block">{{ $errors->first('branch_name') }}</p> @endif
                                            </div>

                                            <div class="form-group">
                                                {!! Form::label('type', 'Account Type') !!}
                                                {!! Form::select('type', [
                                                    null => 'Please Select',
                                                    'savings' => 'Savings Account',
                                                    'cheque' => 'Cheque Account',
                                                    'other' => 'Other'
                                                ],null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group @if ($errors->has('account_number')) has-error @endif">
                                                {!! Form::label('account_number', 'Account Number') !!}
                                                {!! Form::input('text', 'account_number', null, ['class' => 'form-control']) !!}
                                                @if ($errors->has('account_number'))
                                                    <p class="help-block">{{ $errors->first('account_number') }}</p> @endif
                                            </div>

                                            <div class="form-group @if ($errors->has('branch_code')) has-error @endif">
                                                {!! Form::label('branch_code', 'Branch Code') !!}
                                                {!! Form::input('text', 'branch_code', null, ['class' => 'form-control']) !!}
                                                @if ($errors->has('branch_code'))
                                                    <p class="help-block">{{ $errors->first('branch_code') }}</p> @endif
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
                                                ],null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif


                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="radio">
                                            <input type="radio" name="payment_option" value="debit" checked="checked">
                                            <i></i>
                                            R{{ (number_format($user->balanceInRands() / 9, 2)) }}
                                            P/M for 9 Months.
                                        </label>

                                        {!! Form::hidden('amount', (number_format($user->balanceInRands() / 9, 2))) !!}

                                        <hr>
                                        <a class="btn btn-primary" href="{{ URL::previous() }}"><i class="fa fa-arrow-left"></i>
                                            Change payment option</a>
                                        <a class="btn btn-warning" target="_blank" href="{{ route('dashboard.invoices') }}">View My Invoices</a>
                                        {!! Form::submit('Complete Process', ['class' => 'btn btn-primary pull-right']) !!}
                                        {!! Form::close() !!}
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop