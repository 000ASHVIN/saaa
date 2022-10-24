@extends('admin.layouts.master')

@section('title', 'Assessments')
@section('description', 'All')

@section('content')
    <br>
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('admin.assessments.create') }}" class="btn">Create a new assessment</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            @if(count($assessments))
                <table class="table">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($assessments as $assessment)
                        <tr>
                            <td>{{ $assessment->title }}</td>
                            <td><a class="btn btn-xs btn-info"
                                   href="{{ route('admin.assessments.edit',$assessment->id) }}"><i
                                            class="fa fa-pencil"></i></a></td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <hr>
                {!! $assessments->render() !!}
            @else
                <p>There are no assessments</p>
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