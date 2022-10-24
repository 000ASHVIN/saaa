
@extends('admin.layouts.master')

@section('title', 'Promo Codes')
@section('description', 'All Promo Codes')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div class="container-fluid container-fullw bg-white ng-scope">

       <div class="row">
           <div class="col-md-12">
               <div class="pull-right">
                   <a href="{{ route('admin.event.promo_codes.download') }}" class="btn btn-sm btn-primary"><i class="fa fa-download"></i> Download Coupon Report</a>
               </div>
           </div>
       </div>

        <hr>

        <table class="table table-bordered table-striped table-hover promo-codes" id="promo-codes">
            <thead>
                <th>Event</th>
                <th>Code</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Discount</th>
            </thead>
            <tfoot>
                <th>Event</th>
                <th>Code</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Discount</th>
            </tfoot>
            <tbody>
            @foreach($events as $event)
                @foreach($event->promoCodes as $promoCode)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $promoCode->code }}</td>
                        <td>{{ number_format($promoCode->discount_amount) }}</td>
                        <td>{{ $promoCode->discount_type }}</td>
                        <td>{{ $promoCode->description }}</td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>

    </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            $('#promo-codes').DataTable();
        });
    </script>
@endsection