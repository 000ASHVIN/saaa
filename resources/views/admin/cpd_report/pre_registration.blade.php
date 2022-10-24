@extends('admin.layouts.master')

@section('title', 'All Pre Registrations')
@section('description', 'This is all the members that has pre registered for cpd subscription with us.')
@section('styles')
    <!-- Data Tables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">

    <style>
        .dt-buttons{
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
    <section class="container-fluid container-fullw bg-white ng-scope">
        <br>
        <div class="container">
            <div class="panel-group" id="accordion">
                <table id="users" class="table table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>PI Cover</th>
                        <th>ID Number</th>
                        <th>Plan</th>
                        <th>Company</th>
                        <th>Professional Body</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            oTable = $('#users').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('admin.cpd_report.api_pre_registrations') }}",
                "columns": [
                    {data: 'first_name', name: 'first_name'},
                    {data: 'last_name', name: 'last_name'},
                    {data: 'email', name: 'email'},
                    {data: 'pi_cover', name: 'pi_cover'},
                    {data: 'id_number', name: 'id_number'},
                    {data: 'plan', name: 'plan'},
                    {data: 'company', name: 'company'},
                    {data: 'proffesional_body', name: 'proffesional_body'},
                ],
                dom: 'lfrtBibp'
                , buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
            });
        });
    </script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>

    <script src="/js/app.js"></script>
    <script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>

@stop