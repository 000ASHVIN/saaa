@extends('app')

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="heading-title heading-dotted text-center">
                    <h4>Overdue <span>Invoices</span></h4>
                </div>

                <h5>To make arrangements for payment, please contact us at
                    {{ config('app.email') }}
                    or call us on 010 593 0466.</h5>

                <div class="row">
                    <div class="col-md-12">
                        <form action="#" method="get" style="margin-top: 0px!important; margin-bottom: 0px!important; ">
                            <div class="row">
                                <div class="col-md-4 col-lg-offset-8">

                                    <div class="form-group clearfix" style="margin-bottom: 0px!important;">
                                        <div class="form-group has-feedback">
                                            <input class="form-control" id="system-search" name="q"
                                                   placeholder="Search for Invoice" required="">
                                            <span class="fa fa-search form-control-feedback"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-list-search">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice Number</th>
                                <th>Balance Due</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($invoices))
                                @foreach($invoices as $invoice)
                                    <tr style="display: table-row;">
                                        <td>
                                            {{ $invoice->created_at->toFormattedDateString() }}
                                        </td>
                                        <td>
                                            {{ $invoice->reference }}
                                        </td>
                                        <td>
                                            R {{ $invoice->balance }}
                                        </td>
                                        <td>
                                            @if($invoice->status == 'paid')
                                                <span class="label label-success">Paid</span>
                                            @else
                                                <span class="label label-danger">{{ ucwords($invoice->status) }}</span>
                                            @endif
                                        </td>
                                        <th>
                                            <a target="_blank" href="/invoices/view/{{ $invoice->id }}"
                                               class="btn btn-primary btn-xs">View</a>
                                            <a href="/invoices/settle/{{ $invoice->id }}"
                                               class="btn btn-success btn-xs">Settle</a>
                                        </th>
                                    </tr>
                                @endforeach
                            @else
                                <tr style="display: table-row;">
                                    <td colspan="5">
                                        <p>You currently don't have any invoices.</p>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>


@stop

@section('scripts')
    <script>
        $(document).ready(function () {
            var activeSystemClass = $('.list-group-item.active');

            //something is entered in search form
            $('#system-search').keyup(function () {
                var that = this;
                // affect all table rows on in systems table
                var tableBody = $('.table-list-search tbody');
                var tableRowsClass = $('.table-list-search tbody tr');
                $('.search-sf').remove();
                tableRowsClass.each(function (i, val) {

                    //Lower text for case insensitive
                    var rowText = $(val).text().toLowerCase();
                    var inputText = $(that).val().toLowerCase();
                    if (inputText != '') {
                        $('.search-query-sf').remove();
                        tableBody.prepend('<tr class="search-query-sf"><td colspan="6"><strong>Searching for: "'
                                + $(that).val()
                                + '"</strong></td></tr>');
                    }
                    else {
                        $('.search-query-sf').remove();
                    }

                    if (rowText.indexOf(inputText) == -1) {
                        //hide rows
                        tableRowsClass.eq(i).hide();

                    }
                    else {
                        $('.search-sf').remove();
                        tableRowsClass.eq(i).show();
                    }
                });
                //all tr elements are hidden
                if (tableRowsClass.children(':visible').length == 0) {
                    tableBody.append('<tr class="search-sf"><td class="text-muted" colspan="6">No entries found.</td></tr>');
                }
            });
        });
    </script>
@stop