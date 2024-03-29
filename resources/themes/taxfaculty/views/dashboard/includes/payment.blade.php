<div class="modal fade" id="payment" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Settle your account</h4>
            </div>

            <div class="modal-body">
                {!! Form::open() !!}
                <div class="toggle-content" style="display: block;">

                    <div class="row">
                        <div class="col-md-12">
                            {!! Form::label('payment_name','Name on Card *') !!}
                            {!! Form::input('text','payment_name', null ,['class' => 'form-control required', 'autocomplete' => 'off']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::label('cc_number','Credit Card Number *') !!}
                            {!! Form::input('text','cc_number', null, ['class' => 'form-control required', 'autocomplete' => 'off']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::label('cc_exp_month','Card Expiration *') !!}
                                <div class="row nomargin-bottom">
                                    <div class="col-lg-6 col-sm-6">
                                        {!! Form::select('cc_exp_month', array(
                                            '0'  => 'Month',
                                            '01'  => '01 - January',
                                            '02'  => '02 - February',
                                            '03'  => '03 - March',
                                            '04'  => '04 - April',
                                            '05'  => '05 - May',
                                            '06'  => '06 - June',
                                            '07'  => '07 - July',
                                            '08'  => '08 - August',
                                            '09'  => '09 - September',
                                            '10'  => '10 - October',
                                            '11'  => '11 - November',
                                            '12'  => '12 - December',
                                        ), null, ['class' => 'form-control pointer required']) !!}
                                    </div>

                                    <div class="col-lg-6 col-sm-6">
                                        {!! Form::select('cc_exp_year', array(
                                            '0'  => 'Year',
                                            '2015'  => '2015',
                                            '2016'  => '2016',
                                            '2017'  => '2017',
                                            '2018'  => '2018',
                                            '2019'  => '2019',
                                            '2020'  => '2020',
                                            '2021'  => '2021',
                                            '2022'  => '2022',
                                            '2023'  => '2023',
                                            '2024'  => '2024',
                                            '2025'  => '2025',
                                        ), null, ['class' => 'form-control pointer required']) !!}
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 form-group">
                            {!! Form::label('cc_cvv','CVV2 *') !!}
                            {!! Form::input('text', 'cc_cvv', null, ['class' => 'form-control required', 'autocomplete' => 'off', 'maxlength' => '4' ]) !!}
                        </div>
                    </div>
                </div>

                <div style="display: block;">
                    <span class="clearfix">
                        <span class="pull-right">R {{ $user->invoices->sum('sub_total') }}.00</span>
                        <strong class="pull-left">Subtotal:</strong>
                    </span>

                    <span class="clearfix">
                        <span class="pull-right">R {{ $user->invoices->sum('discount') }}.00</span>
                        <span class="pull-left">Discount:</span>
                    </span>
                    <hr>

                    <span class="clearfix">
                        <span class="pull-right size-20">R {{ $user->invoices->sum('total') }}.00</span>
                        <strong class="pull-left">TOTAL:</strong>
                    </span>
                    <hr>

                    <button class="btn btn-primary btn-lg btn-block size-15"><i class="fa fa-mail-forward"></i> Settle Account Now</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
