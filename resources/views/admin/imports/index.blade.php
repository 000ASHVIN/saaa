@extends('admin.layouts.master')

@section('title', 'Imports')
@section('description', $provider->title)

@section('content')
    <br>
    <div class="row">
        <div class="col-md-12">
            @if(count($provider->imports))
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Records</th>
                            <th>Status</th>
                            <th>Report</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($provider->imports as $import)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.import.provider.import',[$provider->id,$import->id]) }}">
                                        {{ $import->title }}
                                    </a>
                                </td>
                                <td>{{ count($import->data) }}</td>
                                <td>
                                    @if($import->completed_successfully)
                                        <div class="label label-success">Successfully Imported</div>
                                    @else
                                        <div class="label label-danger">Error</div>
                                    @endif
                                </td>
                                <td>
                                    <a href="#"><span class="label label-info">View Report</span></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                <hr>
            @else
                <p>There are no imports</p>
            @endif
        </div>
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop