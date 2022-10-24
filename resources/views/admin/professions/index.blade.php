@extends('admin.layouts.master')

@section('title', 'Membership Professions')
@section('description', 'All Professions')

@section('content')
    <section>
        <br>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <table class="table table-responsive table-striped">
                <thead>
                    <tr>
                        <td>Title</td>
                        <td class="text-center">Plans</td>
                        <td>Active</td>
                        <td>Tools</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($professions as $profession)
                        <tr>
                            <td>{{ $profession->title }}</td>
                            <td class="text-center">{{ $profession->plans->count() }}</td>
                            <td>
                                @if($profession->is_active)
                                    <span class="label label-success">
                                        Active
                                    </span>
                                @else
                                    <span class="label label-warning">
                                        Not Active
                                    </span>
                                @endif
                            </td>
                            <td><a href="{{ route('admin.professions.edit', $profession->slug) }}" class="btn btn-xs btn-info">Edit | Update</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <a href="{{ route('admin.professions.create') }}" class="btn btn-wide btn-success">Add New Profession</a>
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