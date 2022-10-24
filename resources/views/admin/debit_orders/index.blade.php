@extends('admin.layouts.master')

@section('title', 'All Debit Order Details')
@section('description', 'This is all the debit order details that we currently have on record')


@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <debit-order-admin inline-template>
        <section class="container-fluid container-fullw bg-white ng-scope">
                <div class="col-md-12">
                    <div class="panel panel-white">
                        <div class="panel-heading">
                            Search for debit orders
                        </div>
                        <div class="panel-body">
                            @include('admin.debit_orders.includes.search')
                        </div>
                    </div>
                    <hr>
                    <div v-if="selectedResult">
                        <div class="panel panel-white" style="background-color: #f1f1f1">
                            <div class="panel-heading">
                                Client Details
                            </div>
                            <div class="panel-body">
                                @include('admin.debit_orders.includes.client')
                            </div>
                        </div>

                        <div class="alert alert-danger" v-for="error in forms.debitOrderForm.errors">
                            <strong>@{{ error }}</strong>
                        </div>
                    </div>
                </div>
        </section>

    <section v-if="search_results.data">
        <br>
        <p><span class="label label-success"><strong> Total Debit Orders that matches that search criteria: @{{ search_results_count }}</strong></span></p>
        <br>
        <table class="table">
            <thead>
                <th>Acc</th>
                <th>Bank</th>
                <th>Branch</th>
                <th>Code</th>
                <th>Date</th>
                <th>Renews</th>
                <th>Name</th>
                <th>Email</th>
                <th>Cell</th>
                <th>OTP</th>
                <th></th>
            </thead>
            <tbody>
                <tr v-for="result in search_results.data" :key="result.id">
                    <td>@{{ result.number }}</td>
                    <td>@{{ result.bank }}</td>
                    <td>@{{ result.branch_name }}</td>
                    <td>@{{ result.branch_code }}</td>
                    <td>@{{ result.billable_date }}</td>
                    <td>@{{ result.user.subscriptions[0].ends_at | formatDate}}</td>
                    <td>@{{ result.user.first_name | capitalize }} @{{ result.user.last_name | capitalize  }}</td>
                    <td>@{{ result.user.email }}</td>
                    <td>@{{ result.user.cell }}</td>
                    <td>
                        <span v-if="result.otp">@{{ result.otp }}</span>
                        <span v-else> - </span>
                    </td>
                    <td><a href="@{{ result.id }}" @click.prevent="selectResult(result)">Update</a></td>
                </tr>
            </tbody>
        </table>
        <div v-if="search_results.data.length > 0">
            <pagination :limit="4" :data="search_results" @pagination-change-page="fetch"></pagination>
        </div>
    </section>
    </debit-order-admin>
@stop

@section('scripts')
    <script src="/assets/themes/saaa/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop