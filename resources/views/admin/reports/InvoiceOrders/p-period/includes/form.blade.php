@if(isset($from))
    {!! Form::open(['method' => 'post', 'route' => ['admin.reports.payments.export_orders_results_outstanding_p_p', isset($from), isset($to)]]) !!}
        {!! Form::hidden('from', $from) !!}
        {!! Form::hidden('to', $to) !!}
        <button typeof="submit" style="float: right;" class="btn btn-info" onclick="spin(this)"><i class="fa fa-download"></i> Export Results</button>
    {!! Form::close() !!}
@endif

<br>
<br>

{!! Form::open(['method' => 'post', 'route' => 'admin.reports.payments.results_orders_outstanding_p_p']) !!}
<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('from')) has-error @endif">
            {!! Form::label('from', 'Invoices From') !!}
            {!! Form::input('text', 'from', (isset($from)) ? $from : null , ['class' => 'form-control is-date']) !!}
            @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('to')) has-error @endif">
            {!! Form::label('to', 'Invoices To') !!}
            {!! Form::input('text', 'to', (isset($to)) ? $to : null, ['class' => 'form-control is-date']) !!}
            @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
        </div>
    </div>
</div>
<button typeof="submit" class="btn btn-success" onclick="spin(this)">View Results</button>
{!! Form::close() !!}

