<div class="form-group @if ($errors->has('account_holder')) has-error @endif">
    <label for="#">Account Name</label>
    {!! Form::input('text', 'account_holder', null, ['class' => 'form-control', 'v-model' =>'forms.search_debit_order.account_holder']) !!}
</div>

<div class="form-group @if ($errors->has('number')) has-error @endif">
    <label for="#">Bank Account Number</label>
    {!! Form::input('text', 'number', null, ['class' => 'form-control', 'v-model' =>'forms.search_debit_order.number']) !!}
    @if ($errors->has('number')) <p class="help-block">{{ $errors->first('number') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('status')) has-error @endif">
    <label for="#">Debit Order Status</label>
    {!! Form::select('status', [
         '' => 'Please Select..',
         true => 'Active',
         false => 'Inactive'
    ],null, ['class' => 'form-control', 'v-model' =>'forms.search_debit_order.status']) !!}
    @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('debit_date')) has-error @endif">
    <label for="#">Debit Date</label>
    {!! Form::select('debit_date', [
        '' => 'Please Select',
        '1' => '1',
        '2' => '2',
        '15' => '15',
        '20' => '20',
        '25' => '25',
        '28' => '28',
        '30' => '30',
    ],null, ['class' => 'form-control', 'v-model' =>'forms.search_debit_order.debit_date']) !!}
    @if ($errors->has('debit_date')) <p class="help-block">{{ $errors->first('debit_date') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('provider')) has-error @endif">
    <label for="#">Provider</label>
    {!! Form::select('provider', [
         '' => 'Please Select..',
         'peach' => 'Peach',
         'stratcol' => 'Stratcol'
    ],null, ['class' => 'form-control', 'v-model' =>'forms.search_debit_order.provider']) !!}
    @if ($errors->has('provider')) <p class="help-block">{{ $errors->first('provider') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('branch')) has-error @endif">
    <label for="#">Branch Code</label>
    {!! Form::input('text', 'branch', null, ['class' => 'form-control', 'v-model' =>'forms.search_debit_order.branch']) !!}
    @if ($errors->has('branch')) <p class="help-block">{{ $errors->first('branch') }}</p> @endif
</div>

<div class="form-group form-inline">
    <button class="btn btn-primary" @click.prevent="search"><i class="fa fa-search"></i> Search</button>
    <button class="btn btn-warning" @click.prevent="resetSearch"><i class="fa fa-search"></i> Reset Search</button>

    <button class="btn btn-success pull-right" @click.prevent="download_data"><i class="fa fa-download"></i> Download Results</button>
</div>