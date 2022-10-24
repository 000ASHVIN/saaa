@extends('app')

@section('title', 'Account Suspended')
@section('content')
    <section>
        <div class="container">
            <p>Dear {{ $user->first_name }} {{ $user->last_name }}</p>
            <p><strong>Account Suspended: {{ date_format(\Carbon\Carbon::parse($user->suspended_at), 'd F Y') }}</strong></p>
            <p>We hereby regret to inform you that your account is now suspended due to non-payment of invoices.</p>
            <p>
                If you have any queries regarding your outstanding balance please contact our accounts department on 010 593 0466 or send an email to
                <a href="mailto:payments@accountingacademy.co.za">payments@accountingacademy.co.za</a>.
                Please note that continued non-payment of your account will result in further legal action being taken.
            </p>

            {{--@if($user->subscribed('cpd'))--}}
                {{--<hr>--}}
                {{--<p class="text-danger"><strong>CPD Subscription Termination</strong></p>--}}
                {{--<p>Kindly note that your subscription was terminated due to non payment of your subscription invoices. You are still liable to settle the unpaid subscription invoices and thereafter you would be able to sign up for a subscription again.</p>--}}
            {{--@endif--}}
            {{--<hr>--}}

            <p><strong>A list of all your unpaid invoices can be found below: </strong></p>
            <table class="table table-hover table-striped">
                <thead>
                <th>Type</th>
                <th>Invoice Date</th>
                <th>Invoice reference</th>
                <th>Invoice balance</th>
                <th></th>
                </thead>
                <tbody>
                    @if(count($user->overdueInvoices()) >= 1)
                        @foreach($user->overdueInvoices() as $invoice)
                            <tr>
                                <td><div class="label label-default">{{ ucfirst($invoice->type) }}</div></td>
                                <td>{{ date_format($invoice->created_at, 'd F Y') }}</td>
                                <td>{{ $invoice->reference }}</td>
                                <td>{{ money_format('%.2n', $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount')) }}</td>
                                <td><a href="{{ route('invoices.settle', $invoice->id) }}" target="_blank" class="btn-sm btn-primary">Settle</a></td>
                            </tr>
                        @endforeach
                    @elseif($user->force_suspend == true)
                        @foreach($user->invoices->where('status', 'unpaid') as $invoice)
                            <tr>
                                <td>{{ date_format($invoice->created_at, 'd F Y') }}</td>
                                <td>{{ $invoice->reference }}</td>
                                <td>{{ money_format('%.2n', $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount')) }}</td>
                                <td><a href="{{ route('invoices.settle', $invoice->id) }}" target="_blank" class="btn-sm btn-primary">Settle</a></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Outstanding Invoices found on your account, Please contact support.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </section>
@endsection