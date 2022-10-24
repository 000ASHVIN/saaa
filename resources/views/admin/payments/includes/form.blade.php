{!! Form::open(['Method' => 'post', 'route' => 'admin.payments.payments_per_day']) !!}

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! form::label('from', 'Please select a from date') !!}
            {!! Form::input('text', 'from', null, ['class' => 'form-control daterange']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! form::label('to', 'Please select a to date') !!}
            {!! Form::input('text', 'to', null, ['class' => 'form-control daterange']) !!}
        </div>
    </div>
</div>

<div class="form-group">
    {!! form::label('type', 'Please select a Type') !!}
    {!! Form::select('type', [
        '' => 'Please Select',
        'instant_eft' => 'Instant EFT',
        'eft' => 'Offline EFT',
        'cc' => 'Credit Card',
        'debit' => 'Debit Order',
        'cash' => 'Cash',
        'snap_scan' => 'Snap Scan',
    ], null, ['class' => 'form-control', 'id' => 'date-range']) !!}
</div>

<div class="form-group">
    {!! Form::submit('View Results', ['class' => 'btn btn-default']) !!}
</div>

{!! Form::close() !!}