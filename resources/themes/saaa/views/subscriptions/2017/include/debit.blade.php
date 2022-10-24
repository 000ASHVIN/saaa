<?php 
$user = App\Users\User::with('debit')->where('id',auth()->user()->id)->first();

?>
<div class="modal fade" id="Debit_order_button" role="basic" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog" style="background: white;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Debit Order Details</h4>
            </div>

            <div class="modal-body">
                <div>
                <app-my-billing-screen :user="{{ $user }}" :bank_codes="{{ json_encode(getBankBranches()) }}" inline-template>
                    <input v-model="payment_method" value="debit_order" type="hidden" v-init:payment_method="debit_order"  />
                    
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div v-if="user.debit"">
                        <label class="radio">
                            <input type="radio" v-model="payment_update" value="0">
                            <i></i> Use existing Debit order details 
                        </label>
                
                        <label class="radio" >
                            <input type="radio" v-model="payment_update" value="1">
                            <i></i> Update debit order
                        </label>
                    </div>
                      

                        <div class="form-group" >
                            <!-- <label for="payment_method">Please select your payment method</label> -->
                            <!-- <select name="payment_method" id="payment_method" class="form-control payment_method"
                                v-model="payment_method">
                                <option @if($user->payment_method == "") selected @endif value="">Please select..
                                </option>
                                <option @if($user->payment_method == "debit_order") selected @endif
                                    value="debit_order">Debit Order</option>
                                <option @if($user->payment_method == "credit_card") selected @endif
                                    value="credit_card">Credit Card</option>

                                @if($user->subscribed('cpd') && $user->subscription('cpd')->plan->interval == 'year')
                                <option @if($user->payment_method == "eft") selected @endif value="eft">Electronic funds
                                    transfer (EFT)</option>
                                @elseif(! $user->subscribed('cpd'))
                                <option @if($user->payment_method == "eft") selected @endif value="eft">Electronic funds
                                    transfer (EFT)</option>
                                @endif
                            </select> -->
                        </div>

                        
                        <div v-if="payment_method == 'debit_order' && payment_update != '0'" >


                        <div class="panel panel-default" v-if="payment_method == 'debit_order' && !waiting_on_otp">
                            <div class="panel-heading"><i class="fa fa-building"></i> Debit Order Details</div>
                            <div class="panel-body">

                                <div class="form-group @if ($errors->has('account_holder')) has-error @endif">
                                    {!! Form::label('account_holder', 'Account Holder') !!}
                                    {!! Form::input('text', 'account_holder', auth()->user()->debit?
                                    auth()->user()->debit->account_holder : null, ['class' => 'form-control', 'v-model'
                                    => 'forms.debit_order.debit.account_holder']) !!}
                                    @if ($errors->has('account_holder')) <p class="help-block">
                                        {{ $errors->first('account_holder') }}</p> @endif
                                </div>

                                <div class="form-group">
                                    <label for="type_of_account">Account Type</label>
                                    <select name="type_of_account" v-model="forms.debit_order.debit.type_of_account"
                                        class="form-control">
                                        <option value="company" @if(auth()->user()->debit &&
                                            auth()->user()->debit->type_of_account == 'company') selected @endif>Company
                                            Account</option>
                                        <option value="personal" @if(auth()->user()->debit &&
                                            auth()->user()->debit->type_of_account == 'personal') selected
                                            @endif>Personal Account</option>
                                    </select>
                                </div>

                                <div class="form-group @if ($errors->has('id_number')) has-error @endif"
                                    v-if="forms.debit_order.debit.type_of_account == 'personal'">
                                    {!! Form::label('id_number', 'ID Number') !!}
                                    {!! Form::input('text', 'id_number', auth()->user()->id_number?
                                    auth()->user()->id_number : null, ['class' => 'form-control', 'v-model' =>
                                    'forms.debit_order.debit.id_number']) !!}
                                    @if ($errors->has('id_number')) <p class="help-block">
                                        {{ $errors->first('id_number') }}</p> @endif
                                </div>

                                <div class="form-group @if ($errors->has('registration_number')) has-error @endif"
                                    v-if="forms.debit_order.debit.type_of_account == 'company'">
                                    {!! Form::label('registration_number', 'Company Registration Number') !!}
                                    {!! Form::input('text', 'registration_number', auth()->user()->debit?
                                    auth()->user()->debit->registration_number : null, ['class' => 'form-control',
                                    'v-model' => 'forms.debit_order.debit.registration_number']) !!}
                                    @if ($errors->has('registration_number')) <p class="help-block">
                                        {{ $errors->first('registration_number') }}</p> @endif
                                </div>

                                <div class="form-group">
                                    {!! Form::label('bank_temp', 'Bank Name') !!}
							        {!! Form::select('bank_temp', getBankBranches(), auth()->user()->debit? auth()->user()->debit->branch_code : null, ['class' => 'form-control', 'v-model' => 'forms.debit_order.debit.bank_temp', 'v-on:change'=>'bankNameChanged()']) !!}
							        {!! Form::input('hidden','bank',auth()->user()->debit? auth()->user()->debit->bank : null, ['class' => 'form-control', 'v-model' => 'forms.debit_order.debit.bank','readonly' => 'true']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('type', 'Account Type') !!}
                                    {!! Form::select('type', [
                                    null => 'Please Select',
                                    'savings' => 'Savings Account',
                                    'cheque' => 'Cheque Account',
                                    'transmission' => 'Transmission',
                                    'other' => 'Other'
                                    ],auth()->user()->debit? auth()->user()->debit->type : null, ['class' =>
                                    'form-control', 'v-model' => 'forms.debit_order.debit.type']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('branch_code', 'Branch Code') !!}
                                    {!! Form::input('text','branch_code',auth()->user()->debit? auth()->user()->debit->branch_code : null, ['class' => 'form-control', 'v-model' => 'forms.debit_order.debit.branch_code','readonly' => 'true']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('number', 'Account Number') !!}
                                    {!! Form::input('text', 'number', auth()->user()->debit?
                                    auth()->user()->debit->number : null, ['class' => 'form-control', 'v-model' =>
                                    'forms.debit_order.debit.number']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('branch_name', 'Branch Name') !!}
                                    {!! Form::input('text', 'branch_name', auth()->user()->debit?
                                    auth()->user()->debit->branch_name : null, ['class' => 'form-control', 'v-model' =>
                                    'forms.debit_order.debit.branch_name']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('billable_date', 'Debit Date') !!}
                                    {!! Form::select('billable_date', [
                                    null => 'Please Select',
                                    '2' => '2',
                                    '15' => '15',
                                    '20' => '20',
                                    '25' => '25',
                                    '28' => '28',
                                    ],auth()->user()->debit? auth()->user()->debit->billable_date : null, ['class' =>
                                    'form-control', 'v-model' => 'forms.debit_order.debit.billable_date']) !!}
                                </div>
                            </div>

                            <div class="panel-footer">
                                <button type="submit" class="btn btn-sm btn-primary"
                                    @click.prevent="UpdateDebitOrderDetails" :disabled="busy" ;>
                                    <i class="fa fa-check"></i> Save Banking Details
                                </button>
                            </div>
                        </div>

                        <div class="panel panel-default" v-if="payment_method == 'debit_order' && waiting_on_otp">
                            <div class="panel-heading">
                                One-Time-Pin (OTP)
                            </div>
                            <div class="panel-body">

                                <div class="alert alert-info">
                                    <p>
                                        <strong>Please note</strong> We've just sent an SMS to your cellphone, please
                                        enter the OTP number below in order to complete your registration.
                                    </p>
                                </div>

                                <form class="form-horizontal" role="form">
                                    <div class="form-group" :class="{'has-error': forms.debit_order.errors.has('otp')}">
                                        <label for="otp" class="col-md-4 control-label">OTP</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="otp" data-stripe="otp"
                                                v-model="forms.debit_order.debit.otp">

                                            <button @click.prevent="resendOtp"
                                                class="btn btn-primary btn-sm pull-right">Resend OTP</button>

                                            <span class="help-block" v-show="forms.debit_order.errors.has('otp')">
                                                <strong>@{{ forms.debit_order.errors.get('otp') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="panel-footer">
                                <button type="submit" class="btn btn-sm btn-primary"
                                    @click.prevent="UpdateDebitOrderDetails" :disabled="busy" ;>
                                    <i class="fa fa-check"></i> Save Banking Details
                                </button>
                            </div>
                        </div>

                       
                    </div>
                    </div>
                    <button v-if="payment_update == 0" type="submit" class="btn btn-sm btn-primary"
                    data-dismiss="modal"  :disabled="busy" ;>
                    <i class="fa fa-check"></i> Save
                </button>
                </app-my-billing-screen>
            </div>
            </div>
        </div>
    </div>
</div>