@extends('admin.layouts.master')

@section('title', 'Careers')
@section('description', 'Show Available Jobs')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <th>Department</th>
                        <th>Job Title</th>
                        <th>Job Period</th>
                        <th>Job location</th>
                        <th>Job Placement</th>
                        <th>Edit Available Job</th>
                    </thead>
                    <tbody>
                        @foreach($departments as $department)
                            @foreach($department->jobs as $job)
                                <tr>
                                    <td>{{$department->title}}</td>
                                    <td>{{$job->title}}</td>
                                    <td>{{$job->period}}</td>
                                    <td>{{$job->location}}</td>
                                    <td>{{$job->created_at}}</td>
                                    <td><a href="/admin/job/edit/{{$job->slug}}">Edit This Job</a></td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
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