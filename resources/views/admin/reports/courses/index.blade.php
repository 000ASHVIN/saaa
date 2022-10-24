@extends('admin.layouts.master')

@section('title', 'Course Export')
@section('description', 'All Course Reports')

@section('content')

<section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped" id="courses">
                        <thead>
                            <th>title</th>
                            <th>Start date</th>
                            <th>End Date</th>
                            <th>Actions</th>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{ $course->title }}</td>
                                    <td>{{ $course->start_date }}</td>
                                    <td>{{ $course->end_date }}</td>
                                    <td><a href="{{ route('admin.reports.extract_course_report',$course->id) }}" class="btn btn-primary">get Report</a></td>
                                </tr>
                            @endforeach
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script src="/js/app.js"></script>
<script>
    jQuery(document).ready(function () {
            Main.init();
        });
</script>
@stop