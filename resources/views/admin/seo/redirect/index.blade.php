@extends('admin.layouts.master')

@section('title', 'Redirect')
@section('description', 'Show Redirect Rules')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div class="container-fluid container-fullw">
        <div class="row">
            <table id="plans" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <th>Old Url</th>
                    <th>New Url</th>
                    <th>Status</th>
                    <th>View</th>
                    <th>remove</th>
                </thead>
                <tbody>
                    @if($redirect->count())
                    @foreach($redirect as $redirects)
                        <tr>
                            <td>{{ $redirects->old_url }}</td>
                            <td>{{ $redirects->new_url }}</td>
                            <td>{{ $redirects->status }}</td>
                          
                            <td>
                                <a href="{{ route('admin.redirect.show', [($redirects->id ? : 0)]) }}" class="btn btn-sm btn-info btn-squared btn-o"><i class="fa fa-eye"></i> View</a>
                            </td>
                            <td>
                                <a href="{{ route('redirect.remove', [($redirects->id ? : 0)]) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> remove</a>
                            </td>
                        </tr>
                    @endforeach
                    @else
                    <tr> <td colspan="4"
                        No Data found
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            @if(count($redirect))
                    {!! $redirect->render() !!}
                @endif
           <div class="row">
           <div class="col-md-12">
            <a href="{{ route('admin.redirect.create') }}" class="btn btn-wide btn-success">Add New Redirect Rule</a>
           </div>
           </div>
        </div>
    </div>
@stop
@section('scripts')
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

    <script src="/admin/assets/js/index.js"></script>
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

    </script>
@stop