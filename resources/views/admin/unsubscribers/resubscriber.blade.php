@extends('admin.layouts.master')

@section('title', 'Resubscribers')
@section('description', '')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-left" style="margin-bottom: 10px">
                        {{-- <a href="{{ route('admin.industries.create') }}" class="btn btn-info">New Industry</a> --}}
                    </div>
                    <div class="text-right" style="margin-bottom: 10px">
                        <a href="{{ route('admin.resubscribers.report') }}" class="btn btn-info">Export Report</a>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped" id="unsubscribers">
                        <thead>
                            <th>Email</th>
                            <th>Unsubscribed from all marketing emails</th>
                            <th>Subscribed Types</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="/admin/assets/js/index.js"></script>
    <script>
        $('#unsubscribers').DataTable({
            serverSide: true,
            ajax: '/admin/resubscribers/list',
            rowId: 'id',
            columns: [
                {data: 'email'},
                {data: 'unsubscribe_all', orderable: false},
                {data: 'subscribed_types', orderable: false},
            ],
            'processing': true,
            'language': {
                'loadingRecords': '&nbsp;',
                'processing': '<div class="spinner"></div>'
            }
        });
    </script>
@endsection