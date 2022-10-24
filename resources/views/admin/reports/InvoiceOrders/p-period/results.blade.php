@extends('admin.layouts.master')

@section('title', 'Outstanding P.P')
@section('description', 'Outstanding Per Period')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        @include('admin.reports.InvoiceOrders.p-period.includes.form')
    </div>

    <div class="container-fluid container-fullw padding-bottom-10">
        <div class="col-md-1"></div>
        <div class="col-md-2">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <span class="fa-stack fa-2x"> <i class="fa fa-file fa-stack-2x text-primary"></i> <i class="fa fa-money fa-stack-1x fa-inverse"></i> </span>
                    <h4 style="margin-top: 10px" class="StepTitle">Orders</h4>
                    <p class="text-small">
                        R{{ number_format($totalOrders->sum('total'), 2) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <span class="fa-stack fa-2x"> <i class="fa fa-file fa-stack-2x text-primary"></i> <i class="fa fa-money fa-stack-1x fa-inverse"></i> </span>
                    <h4 style="margin-top: 10px" class="StepTitle">Payments</h4>
                    <p class="text-small">
                        R{{ number_format($payments->sum('total') - $discounts->sum('amount'), 2) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <span class="fa-stack fa-2x"> <i class="fa fa-file fa-stack-2x text-primary"></i> <i class="fa fa-money fa-stack-1x fa-inverse"></i> </span>
                    <h4 style="margin-top: 10px" class="StepTitle">Discounts</h4>
                    <p class="text-small">
                        R{{ number_format($discounts->sum('amount'), 2) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <span class="fa-stack fa-2x"> <i class="fa fa-file fa-stack-2x text-primary"></i> <i class="fa fa-money fa-stack-1x fa-inverse"></i> </span>
                    <h4 style="margin-top: 10px" class="StepTitle">Cancellations</h4>
                    <p class="text-small">
                        R{{ number_format($cancellations->sum('total'), 2) }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <span class="fa-stack fa-2x"> <i class="fa fa-file fa-stack-2x text-primary"></i> <i class="fa fa-money fa-stack-1x fa-inverse"></i> </span>
                    <h4 style="margin-top: 10px" class="StepTitle">Outstanding</h4>
                    <p class="text-small">
                        R{{ number_format($totalOrders->where('status', 'unpaid')->sum('total'), 2) }}
                    </p>
                </div>
            </div>
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

        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@stop