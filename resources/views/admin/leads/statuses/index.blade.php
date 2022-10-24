@extends('admin.layouts.master')

@section('title', 'Lead Statuses')
@section('description', 'Lead Statuses')

@section('content')  

    <section>

        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <a href="{{ route('admin.leads.status.create') }}" class="btn btn-wide btn-success">Add New Status</a>
            <table class="table table-striped" id="sample-table-2" style="margin-top:20px;">
                <thead>
                    <th>#</th>
                    <th>Status</th>
                    <th>Leads Count</th>
                    <th>Actions</th>
                </thead> 
                <br>
                <tbody>
                @if(count($statuses)) 
                    <?php $i = $statuses->firstItem(); ?>
                    @foreach($statuses as $status)

                        <tr>
                            <td width="1px"><span class="label label-body"> {{ $i++ }} </span></td>

                            <td>{{ $status->name }}</td>

                            <td class="hidden-xs">
                                -
                            </td>

                            <td>
                                {!! Form::open(['method' => 'delete', 'route' => ['admin.leads.status.destroy', $status->id], 'onSubmit' => 'return confirm("Are you sure to delete?")']) !!}
                                    <a href="{{ route('admin.leads.status.edit', $status->id) }}" class="btn btn-xs btn-info">Update</a>
                                    {!! Form::submit('Delete', ['class' => 'btn btn-xs btn-danger']) !!}
                                {!! Form::close() !!}
                            </td>
 
                        </tr>
                        
                    @endforeach
                
                @else
                    <tr>
                        <td colspan="4" class="text-center">
                            No statuses available.
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            {!! $statuses->appends(request()->except('page'))->render() !!}
        </div>
    </section>
@stop

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop