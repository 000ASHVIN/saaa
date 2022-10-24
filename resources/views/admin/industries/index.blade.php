@extends('admin.layouts.master')

@section('title', 'Industries')
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
                        <a href="{{ route('admin.industries.create') }}" class="btn btn-info">New Industry</a>
                    </div>
                    <div class="text-right" style="margin-bottom: 10px">
                        {{-- <a href="{{ route('admin.rewards.report') }}" class="btn btn-info">Export Report</a> --}}
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table table-striped" id="rewards">
                        <thead>
                            <th>Title</th>
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
                    ajax: '/admin/industries/list',
                    rowId: 'id',
                    columns: [
                        {data: 'title'},                        
                        {
                            data: "id",
                            "searchable": false,
                            "sortable": false,
                            "render": function (id, type, full, meta) {
                                return '<a href="/admin/industries/show/' + id + '" class="btn btn-sm btn-secondary btn-circle" style="background-color: #21679b; border-color: #21679b; color: white"><i class="fa fa-pencil"></i></a>';
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