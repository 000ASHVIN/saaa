@extends('admin.layouts.master')

@section('title', 'Outstanding Orders P.P')
@section('description', 'Outstanding Per Period')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        @include('admin.reports.InvoiceOrders.p-period.includes.form')
    </div>
@endsection

@section('scripts')
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

        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@endsection