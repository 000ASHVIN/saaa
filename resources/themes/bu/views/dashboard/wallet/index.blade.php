@extends('app')

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="heading-title heading-dotted text-center">
                    <h4>My <span>Personal U-Wallet</span></h4>
                </div>

                <div class="border-box">
                    <h4 style="text-align: center; font-weight: normal;">Your available funds in your U-Wallet is: <br> <small>Reference: <strong>{{ $user->wallet->reference }}</strong></small></h4>
                    <hr>
                    <p class="text-center"> R {{ number_format($user->wallet->amount, 2) }}</p>
                    <p class="text-center">
                        <small>
                            Kindly note that you can TopUp your U-Wallet by making an EFT payment with the reference: <strong>{{ $user->wallet->reference }}</strong>.
                                <br>
                            If incorrect reference was used, your payment will not be allocated.
                        </small>
                    </p>

                    @if(count($user->cards))
                        <hr>
                        <p style="text-align: center">
                            <a class="btn btn-primary" href="#" data-target="#walletTopup" data-toggle="modal">TopUp with Credit Card</a>
                        </p>
                        @include('dashboard.wallet.topup')
                    @endif
                </div>

                <hr>

                <div class="heading-title heading-dotted text-center">
                    <h4>Latest 10 Transactions</h4>
                </div>
                <table class="table table-striped">
                    <thead>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Type</th>
                        <th>Method</th>
                        <th>Category</th>
                        <th>Amount</th>
                    </thead>
                    <tbody>
                        @if(count($user->wallet->transactions))
                            @foreach($user->wallet->transactions()->orderBy('id', 'desc')->get()->take(9) as $transaction)
                                <tr >
                                    <td>{{ date_format($transaction->created_at, 'd F Y') }}</td>
                                    <td>{{ $transaction->invoice_reference }}</td>
                                    <td>{{ ucfirst($transaction->type) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $transaction->method)) }}</td>
                                    <td>{{ ucfirst($transaction->category) }}</td>
                                    <td>R {{ number_format($transaction->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">Your U-Wallet does not have any transactions available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <p style="text-align: center">
                    <a class="btn btn-default btn-block" href="{{ route('dashboard.wallet.export', $user->id) }}"><i class="fa fa-file-excel-o"></i> Export All Transactions</a>
                </p>

            </div>
        </div>
    </section>
@stop