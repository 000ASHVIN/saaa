@extends('admin.layouts.master')

@section('title', 'Agent Groups')
@section('description', 'Agent Groups')

@section('content')
    <section>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('admin.agent_groups.create') }}" class="pull-right btn btn-primary"><i class="fa fa-pencil"></i> Create New</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <th>Group ID</th>
                            <th>Group Name</th>
                            <th>Categories</th>
                            <th width="10%">Total Members</th>
                            <th width="2%"></th>
                            <th width="2%"></th>
                        </thead>
                        <tbody>
                            @if (count($agentGroups))
                                @foreach($agentGroups as $agentGroup)
                                    <tr>
                                        <td>{{ $agentGroup->id }}</td>
                                        <td>{{ $agentGroup->name }}</td>
                                        <td>{{ count($agentGroup->categories)?implode(', ',$agentGroup->categories->pluck('title')->toArray()):'-' }}</td>
                                        <td>{{ $agentGroup->agents->count() }}</td>
                                        <td><a href="{{ route('admin.agent_groups.edit', $agentGroup->id) }}" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a></td>
                                        <td>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['admin.agent_groups.destroy', $agentGroup->id]]) !!}
                                                <button class="btn btn-sm btn-danger"><i class="fa fa-close"></i></button>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                    <tr>
                                        <td colspan="5">
                                            No groups available.
                                        </td>
                                    </tr>
                            @endif
                        </tbody>
                    </table>
                    {!! $agentGroups->render() !!}
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop