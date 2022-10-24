@extends('admin.layouts.master')

@section('title', 'Payments')
@section('description', 'View Payments that was allocated on each day')

@section('content')
    <div class="container-fluid container-fullw bg-white">
        @include('admin.payments.includes.form')

        <div class="col-md-12">
            <div class="row">
                <table class="table">
                    <thead>
                    <th>User</th>
                    <th>Payment Date</th>
                    <th>Amount</th>
                    <th>Payment description</th>
                    
                    <th class="text-center">Payment Method</th>
                    <th class="text-center">Invoice Number</th>
                    <th class="text-center">Invoice Balance</th>
                    <th>Status</th>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>
                                @if($transaction->user)
                                    <a href="{{ route('admin.members.show', $transaction->user->id) }}" target="_blank">
                                        {{ ucfirst($transaction->user->first_name) }}
                                        {{ ucfirst($transaction->user->last_name) }}
                                    </a>
                                @else
                                    <a href="#">{{ $transaction->invoice->user->first_name }} <span class="label label-info">Account Removed</span></a>
                                @endif
                            </td>
                            <td>{{ date_format($transaction->date, 'Y/m/d') }}</td>
                            <td>{{ money_format('%.2n', $transaction->amount) }}</td>
                            <td>{{ $transaction->description }}</td>
                            
                            <td class="text-center">
                                {{ ($transaction->method) }}
                            </td>
                            <td class="text-center"><a href="{{ route('invoices.show',$transaction->invoice->id) }}" target="_blank">{{ $transaction->ref }}</a></td>
                            <td class="text-center">
                            {{ money_format('%.2n', $transaction->invoice->sub_total - $transaction->invoice->transactions->where('type', 'credit')->sum('amount')) }}
                            </td>
                            <td>{{ ucfirst($transaction->invoice->status) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {!! $transactions->render() !!}

    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/assets/admin/vendor/moment/moment.min.js"></script>
    <script type="text/javascript" src="/assets/admin/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script>
        $(function() {
            $('.daterange').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
            });
        });
    </script>
@endsection