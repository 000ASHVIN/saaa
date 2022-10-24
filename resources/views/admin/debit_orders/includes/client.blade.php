<div>
   <div class="row">
       <div class="col-md-6">
           <div class="form-group">
               {!! Form::label('account_holder', 'Account Holder') !!}
               {!! Form::input('text', 'account_holder', null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.account_holder']) !!}
           </div>
       </div>
       <div class="col-md-6">
           <div class="form-group">
               {!! Form::label('bank', 'Bank') !!}
               {!! Form::input('text', 'bank', null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.bank']) !!}
           </div>
       </div>

       <div class="col-md-6">
           <div class="form-group">
               {!! Form::label('number', 'Account Number') !!}
               {!! Form::input('text', 'number', null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.number']) !!}
           </div>
       </div>

       <div class="col-md-6">
           <div class="form-group">
               {!! Form::label('branch_code', 'Branch Code') !!}
               {!! Form::input('text', 'branch_code', null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.branch_code']) !!}
           </div>
       </div>

       <div class="col-md-6">
           <div class="form-group">
               {!! Form::label('branch_name', 'Branch Name') !!}
               {!! Form::input('text', 'branch_name', null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.branch_name']) !!}
           </div>
       </div>

       <div class="col-md-6">
           <div class="form-group">
               {!! Form::label('type', 'Account Type') !!}
               {!! Form::select('type', [
                   'savings' => 'Savings Account',
                    'cheque' => 'Cheque Account',
                    'other' => 'Other'
               ],null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.type']) !!}
           </div>
       </div>

       <div class="col-md-6">
           <div class="form-group">
               {!! Form::label('type_of_account', 'Account Type') !!}
               {!! Form::select('type_of_account', [
                    'personal' => 'Personal',
                    'company' => 'Company',
               ],null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.type_of_account']) !!}
           </div>
       </div>

       <div class="col-md-6">
           <div v-show="forms.debitOrderForm.type_of_account == 'company'" class="form-group @if ($errors->has('registration_number')) has-error @endif">
               {!! Form::label('registration_number', 'Company Registration Number') !!}
               {!! Form::input('text', 'registration_number', null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.registration_number']) !!}
           </div>

           <div v-show="forms.debitOrderForm.type_of_account == 'personal'"class="form-group">
               {!! Form::label('id_number', 'ID Number') !!}
               {!! Form::input('text', 'id_number', null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.id_number']) !!}
           </div>
       </div>

       <div class="col-md-6">
           <div class="form-group">
               {!! Form::label('billable_date', 'Billable Date') !!}
               {!! Form::select('billable_date', [
                    '1' => '1',
                    '2' => '2',
                    '15' => '15',
                    '20' => '20',
                    '25' => '25',
                    '28' => '28',
                    '30' => '30',
               ],null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.billable_date']) !!}
               @if ($errors->has('billable_date')) <p class="help-block">{{ $errors->first('billable_date') }}</p> @endif
           </div>
       </div>

       <div class="col-md-6">
           <div class="form-group">
               {!! Form::label('peach', 'Migrate To Peach') !!}
               {!! Form::select('peach', [
                   true => 'Yes',
                   false => 'No',
               ],null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.peach']) !!}
               @if ($errors->has('peach')) <p class="help-block">{{ $errors->first('peach') }}</p> @endif
           </div>
       </div>

       <div class="col-md-12">
           <div class="form-group" v-if="forms.debitOrderForm.peach == 1">
               {!! Form::label('otp', 'OTP') !!}
               {!! Form::input('text', 'otp', null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.otp']) !!}
           </div>
       </div>

       @if (auth()->user()->hasRole('super'))
           <div class="col-md-12">
               <div class="form-group">
                   {!! Form::label('skip_next_debit', 'Skip the next debit order') !!}
                   {!! Form::select('skip_next_debit', [
                        '1' => 'Skip next debit order',
                        '0' => 'Allow next debit order',
                   ],null, ['class' => 'form-control', 'v-model' => 'forms.debitOrderForm.skip_next_debit']) !!}
                   @if ($errors->has('skip_next_debit')) <p class="help-block">{{ $errors->first('skip_next_debit') }}</p> @endif
               </div>
           </div>
       @endif

       <div class="col-md-12">
           <div class="form-group @if ($errors->has('note')) has-error @endif">
               {!! Form::label('note', 'Note') !!}
               {!! Form::textarea('note', null, ['class' => 'form-control', 'rows' => '5', 'v-model' => 'forms.debitOrderForm.note']) !!}
               @if ($errors->has('note')) <p class="help-block">{{ $errors->first('note') }}</p> @endif
           </div>
       </div>
   </div>

    <hr>

    <div class="form-group form-inline">
       <div class="pull-left">
           <button @click.prevent="sendOTP" class="btn btn-sm btn-warning"><i class="fa fa-send-o"></i> Send OTP</button>
       </div>
        <div class="pull-right">
            <button @click.prevent="updateDetails" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Update Details</button>
            <button @click.prevent="clearSelected" class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Cancel</button>
        </div>
    </div>
</div>