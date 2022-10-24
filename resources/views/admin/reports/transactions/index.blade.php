@extends('admin.layouts.master')

@section('title', 'Extract Transactions')
@section('description', 'Extract a list of transactions for a specific time frame')

@section('content')

    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <p><strong>Export Summary</strong></p>
            <hr>
            <p>This report will provide the following: </p>
            <ul>
                <li>All Transactions from the selected from date to the selected to date.</li>
                <li>On submit this will provide extract with data.</li>
            </ul>
        </div>
    </div>

    <div class="container-fluid container-fullw padding-bottom-5"></div>

    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            {!! Form::open(['method' => 'post', 'route' => 'admin.reports.payments.post_extract_transactions']) !!}
            <div class="col-md-6">
                <div class="form-group @if ($errors->has('from')) has-error @endif">
                    {!! Form::label('from', 'Select From Date') !!}
                    {!! Form::input('text', 'from', null, ['class' => 'form-control is-date']) !!}
                    @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group @if ($errors->has('to')) has-error @endif">
                    {!! Form::label('to', 'Select To Date') !!}
                    {!! Form::input('text', 'to', null, ['class' => 'form-control is-date']) !!}
                    @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
                </div>
            </div>
            @if(isset($dataReport))
            <div class="row">
            <div class="col-md-12">
                    <h4>Summary report for transactions: </h4>
                    <table class="table table-hover">
                            <thead>
                                <th>Source</th>
                                <th>Total Invoiced</th>
                                <th>Total Cancellations</th>
                                <th>Total Discounts</th>
                                <th>Total Payments</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Subscription</strong></td>
                                    <td>{{$dataReport['SubscriptionInvoice']}}</td>
                                    <td>{{$dataReport['SubscriptionCancellations']}}</td>
                                    <td>{{$dataReport['SubscriptionDiscounts']}}</td>
                                    <td>{{$dataReport['SubscriptionPayments']}}</td>
                                </tr>
                                <tr>
                                        <td><strong>Events</strong></td>
                                        <td>{{$dataReport['EventInvoice']}}</td>
                                        <td>{{$dataReport['EventCancellations']}}</td>
                                        <td>{{$dataReport['EventDiscounts']}}</td>
                                        <td>{{$dataReport['EventPayments']}}</td>
                                </tr>
                                <tr>
                                        <td><strong>Store</strong></td>
                                        <td>{{$dataReport['StoreInvoice']}}</td>
                                        <td>{{$dataReport['StoreCancellations']}}</td>
                                        <td>{{$dataReport['StoreDiscounts']}}</td>
                                        <td>{{$dataReport['StorePayments']}}</td>
                                </tr>
                                <tr>
                                        <td><strong>Course</strong></td>
                                        <td>{{$dataReport['CourseInvoice']}}</td>
                                        <td>{{$dataReport['CourseCancellations']}}</td>
                                        <td>{{$dataReport['CourseDiscounts']}}</td>
                                        <td>{{$dataReport['CoursePayments']}}</td>
                                </tr>
                                <tr>
                                        <td><strong>Total</strong></td>
                                        <td>{{$dataReport['SubscriptionInvoice']+$dataReport['EventInvoice']+$dataReport['StoreInvoice']+$dataReport['CourseInvoice']}}</td>
                                        <td>{{$dataReport['SubscriptionCancellations']+$dataReport['EventCancellations']+$dataReport['StoreCancellations']+$dataReport['CourseCancellations']}}</td>

                                        <td>{{$dataReport['SubscriptionDiscounts']+$dataReport['EventDiscounts']+$dataReport['StoreDiscounts']+$dataReport['CourseDiscounts']}}</td>     
                                        <td>{{$dataReport['SubscriptionPayments']+$dataReport['EventPayments']+$dataReport['StorePayments']+$dataReport['CoursePayments']}}</td>                        
                                      
                                </tr>
                            </tbody>
                        </table>
            </div>
            </div>
            @endif
            <div class="col-md-12">
                <button type="submit" name="submit" value="export_report" class="btn btn-success">Extract Report</button>
                <button type="submit" name="submit" value="view_report" class="btn btn-success">View Summary Report</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            $('.is-date').datepicker;
        });
    </script>
@stop