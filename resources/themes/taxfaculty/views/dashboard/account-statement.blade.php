@extends('app')

@section('title', 'My Account Statement')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">Account Statement</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('styles')
    <style>
        @media print {
            .print-statement{
                margin-top: -60px!important;
            }
            @page {
                margin: 0.5cm;
            }
            html, body {
                margin: -20px 0px 0px;
                padding: 20px 10px 10px;
                font-size: 9.5pt;
            }
        }
    </style>
@endsection

@section('content')
<section>
    <div class="container">
        @include('dashboard.includes.sidebar')

        <div class="col-lg-9 col-md-9 col-sm-8">

            <div class="print-statement">
                <div class="visible-print">
                    <div class="text-center">
                        <h4>Account Statement <br> <i><small>{{ \Carbon\Carbon::now()->toDateString() }}</small></i></h4>
                    </div>
                </div>

                <div class="pull-right">
                    <a href="javascript:window.print()" class="pull-right btn btn-success hidden-print"><i class="fa fa-print"></i> Print Page</a>
                    <a href="{{ route('dashboard.send_statement', $user) }}" onclick="send(this)" class="pull-right btn btn-info hidden-print"><i class="fa fa-send"></i> Send Via Email</a>
                </div>
                <table class="visible-print">
                    <tr>
                        <td width="400px" class="pull-left" style="text-align: left; padding: 5px">
                            @if($user->profile->company)
                                {{ $user->profile->company }}<br>
                                {{ $user->profile->tax }}<br>
                            @else
                                {{ $user->first_name }} {{ $user->last_name }} <br>
                                {{ $user->id_number }}<br>
                                {{ $user->email }}<br>
                                {{ $user->cell }}<br>
                            @endif

                            @if(count($user->addresses))
                                @if($user->addresses->contains('type', 'work'))
                                    {{ $user->addresses->where('type', 'work')->first()->line_one }} <br>
                                    {{ $user->addresses->where('type', 'work')->first()->line_two }} <br>
                                    {{ $user->addresses->where('type', 'work')->first()->city }} <br>
                                    {{ $user->addresses->where('type', 'work')->first()->area_code }}
                                @else
                                    {{ $user->addresses->first()->line_one }} <br>
                                    {{ $user->addresses->first()->line_two }} <br>
                                    {{ $user->addresses->first()->city }} <br>
                                    {{ $user->addresses->first()->area_code }}
                                @endif
                            @endif
                        </td>
                        <td width="100px"></td>
                        <td width="500px"><img style="width: 100%" src="{{ Theme::asset('img/logo.png') }}" alt=""></td>
                    </tr>
                </table>
                <table class="table table-bordered">
                    <thead>                    
                        <th class="center">Date</th>
                        <th>Type</th>
                        <th>Reference</th>
                        <th class="text-right">Dr</th>
                        <th class="text-right">Cr</th>
                    </thead>
                    <tbody>
                    @if(count($user->transactions))
                        @foreach($user->transactions->sortBy('date') as $transaction)
                            <tr class="
                            {{ ($transaction->tags == 'Payment') ? 'success' : '' }}
                            {{ ($transaction->tags == 'Discount') ? 'info' : '' }}
                            {{ ($transaction->tags == 'Cancellation') ? 'danger' : '' }}
                                    ">                                
                                <td>{{ $transaction->date->toFormattedDateString() }}</td>                                
                                <td>
                                    {{ $transaction->tags }}
                                </td>
                                <td class="hidden-print">
                                    <a target="_blank" href="{{ route('invoices.show',$transaction->invoice->id) }}">#{{ $transaction->invoice->reference }}</a>
                                </td>
                                <td class="visible-print">
                                    {{ $transaction->invoice->reference }}
                                </td>
                                <td class="text-right">{{ ($transaction->type == 'debit') ? $transaction->amountAsCurrency() : '-' }}</td>
                                <td class="text-right">{{ ($transaction->type == 'credit') ? $transaction->amountAsCurrency() : '-' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">
                                No recorded transactions.
                            </td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>

                    <tr>
                        <td colspan="4" class="text-right">
                            <b>Account Balance:</b>
                        </td>
                        <td class="text-right">
                            <b>{{ money_format('%.2n', $user->transactions->where('type', 'debit')->sum('amount') - $user->transactions->where('type', 'credit')->sum('amount')) }}</b>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
</section>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#cpd_date').datepicker();
        });

        function send(this1)
        {
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Sending..`;
            this1.click();
        }
    </script>
@endsection