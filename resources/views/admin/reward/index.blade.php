@extends('admin.layouts.master')

@section('title', 'Online Rewards')
@section('description', 'Show Available rewards')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-right" style="margin-bottom: 10px">
                        <a href="{{ route('admin.rewards.report') }}" class="btn btn-info">Export Report</a>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped" id="rewards">
                        <thead>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact No</th>
                            <th>Product</th>
                            <th></th>
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
                $('#rewards').DataTable({
                    serverSide: true,
                    ajax: '/admin/rewards/list',
                    rowId: 'id',
                    columns: [
                        {data: 'name'},
                        {data: 'email'},
                        {data: 'contact_number'},
                        {data: 'product'},
                        
                        
                        
                        
                        {
                            data: "id",
                            "searchable": false,
                            "sortable": false,
                            "render": function (id, type, full, meta) {
                                return '<a href="/admin/rewards/show/' + id + '" class="btn btn-sm btn-secondary btn-circle" style="background-color: #21679b; border-color: #21679b; color: white"><i class="fa fa-pencil"></i></a>';
                            }
                        },
                    ],
                    'processing': true,
                    'language': {
                        'loadingRecords': '&nbsp;',
                        'processing': '<div class="spinner"></div>'
                    }
                });
            </script>
@endsection