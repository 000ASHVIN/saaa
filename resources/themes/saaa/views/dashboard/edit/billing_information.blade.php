@extends('app')

@section('title', 'Edit Billing Information')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li hreff="#">Edit</li>
                        <li class="active">Billing</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                @include('dashboard.edit.nav')
                <div class="margin-top-20"></div>
                {!! Form::model(auth()->user(), ['method' => 'post', 'route' => 'dashboard.edit.billing_information.update']) !!}

                <div class="form-group">
                    {!! Form::label('bank', 'Bank Name') !!}
                    {!! Form::input('text','bank',auth()->user()->debit? auth()->user()->debit->bank : null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('type', 'Account Type') !!}
                    {!! Form::select('type', [
                        null => 'Please Select',
                        'savings' => 'Savings Account',
                        'cheque' => 'Cheque Account',
                        'other' => 'Other'
                    ],auth()->user()->debit? auth()->user()->debit->type : null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('branch_code', 'Branch Code') !!}
                    {!! Form::input('text', 'branch_code', auth()->user()->debit? auth()->user()->debit->branch_code : null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('number', 'Account Number') !!}
                    {!! Form::input('text', 'number', auth()->user()->debit? auth()->user()->debit->number : null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('branch_name', 'Branch Name') !!}
                    {!! Form::input('text', 'branch_name', auth()->user()->debit? auth()->user()->debit->branch_name : null, ['class' => 'form-control']) !!}
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
                    ],auth()->user()->debit? auth()->user()->debit->billable_date : null, ['class' => 'form-control']) !!}
                </div>

                <div class="margiv-top10">
                    <button class="btn btn-primary" type="submit" onclick="spin(this)";><i class="fa fa-check"></i> Save Changes</button>
                </div>
                {!! Form::close() !!}
            </div>

        </div>
    </section>
@stop