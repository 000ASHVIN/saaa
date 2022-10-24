@extends('admin.layouts.master')

@section('title', 'Wallet Transactions')
@section('description', 'All Wallet Transactions')

@section('content')
    <br>
    <div class="row">
        <div class="col-md-12">
            {!! Form::open() !!}
               <div class="form-inline center">
                   <h4>Export Wallet Transactions</h4>
                   <hr>
                   <input type="text" name="from" class="form-control is-date"> -
                   <input type="text" name="to" class="form-control is-date"> -
                   <select name="type" class="form-control">
                       <option value="">Please Select..</option>
                       <option value="debit">Debits</option>
                       <option value="credit">Credits</option>
                   </select> -

                   <select name="category" class="form-control">
                       <option value="">Please Select..</option>
                       <option value="store">Online Store</option>
                       <option value="event">Events</option>
                       <option value="payment">Payments</option>
                       <option value="subscription">Subscriptions</option>
                   </select>
                   <span id="exportDiv">
                       <button id="submitButton" type="submit" class="btn btn-o btn-primary" onclick="spin(this)">Export Transactions</button>
                   </span>
               </div>
            {!! Form::close() !!}
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <th>Full Name</th>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Method</th>
                    <th>Amount</th>
                </thead>
                <tbody>
                    @if(count($transactions))
                        @foreach($transactions as $transaction)
                            <tr>
                                <td style="text-transform: capitalize;">{{ $transaction->wallet->user->full_name() }}</td>
                                <td>{{ date_format($transaction->created_at, 'd F Y') }}</td>
                                <td>{{ $transaction->invoice_reference }}</td>
                                <td style="text-transform: capitalize;">{{ $transaction->type }}</td>
                                <td style="text-transform: capitalize;">{{ $transaction->category }}</td>
                                <td style="text-transform: capitalize;">{{ $transaction->method }}</td>
                                <td>R{{ number_format($transaction->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">No Transactions available</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {!! $transactions->render() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
            $('#exportDiv').load(document.URL +  ' #submitButton');
        }
    </script>
@stop