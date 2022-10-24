@extends('admin.layouts.master')

@section('title', 'Online Courses')
@section('description', 'Show Available Courses')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped" id="courses">
                        <thead>
                            <th>title</th>
                            <th>Max students</th>
                            <th>Start date</th>
                            <th>End Date</th>
                            <th>Students</th>
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
                $('#courses').DataTable({
                    serverSide: true,
                    ajax: '/admin/courses/list',
                    rowId: 'id',
                    columns: [
                        {data: 'title'},
                        {data: 'max_students'},
                        {data: 'start_date'},
                        {data: 'end_date'},
                        {data: 'students',"searchable": false},
                        {
                            data: "id",
                            "searchable": false,
                            "sortable": false,
                            "render": function (id, type, full, meta) {
                                return '<a href="/admin/courses/show/' + id + '" class="btn btn-sm btn-secondary btn-circle" style="background-color: #21679b; border-color: #21679b; color: white"><i class="fa fa-pencil"></i></a>';
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