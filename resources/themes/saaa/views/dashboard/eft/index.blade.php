@extends('app')

@section('title')

@stop

@section('content')
    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <div class="display-table">
            <div class="display-table-cell vertical-align-middle">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">

                            <div class="box-static box-border-top padding-30 left">
                                <div class="box-title margin-bottom-30">
                                    <h2 class="size-20">EFT Payment</h2>
                                    <p>Thank you for choosing to pay via EFT. Please indicate below on which date you
                                        will be making payment. Please forward proof of payment to
                                        <a href="mailto: payments@accountingacademy.co.za">payments@accountingacademy.co.za</a></p>
                                    <hr>
                                    <P>
                                       <strong> BANKING DETAILS</strong> <br>
                                        Bank: ABSA Bank <br>
                                        Account Holder: SA Accounting Academy <br>
                                        Account Number: 4077695135 <br>
                                        Branch Code: 632005 <br>
                                        Amount: R{{ (number_format($user->balanceInRands(), 2)) }}
                                    </P>
                                </div>

                                {!! Form::open(['method' => 'post']) !!}
                                <div class="form-group @if ($errors->has('id_number')) has-error @endif">
                                    {!! Form::label('id_number', 'ID Number') !!}
                                    {!! Form::input('text', 'id_number', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('id_number')) <p
                                            class="help-block">{{ $errors->first('id_number') }}</p> @endif
                                </div>

                                <div class="form-group @if ($errors->has('date')) has-error @endif">
                                    {!! Form::label('date', 'Please select your date') !!}
                                    {!! Form::input('text', 'date', null, ['class' => 'form-control date']) !!}
                                    @if ($errors->has('date'))
                                        <p class="help-block">{{ $errors->first('date') }}</p> @endif
                                </div>

                                <a class="btn btn-primary" href="{{ URL::previous() }}">
                                    <i class="fa fa-arrow-left"></i>Change payment option
                                </a>
                                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            $('.date').datepicker();
        });
    </script>
@endsection