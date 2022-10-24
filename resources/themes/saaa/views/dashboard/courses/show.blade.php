@extends('app')

@section('title', 'My Courses')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">My Courses</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('styles')
    <style>
        .grades .header {
            margin-top: 20px;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="row">
                    <div class="col-md-12">

                        <div class="border-box text-center" style="padding: 20px; margin-bottom: 20px; background-color: rgba(0,0,0,0.05)">
                            <h4>{{ $course->title }}</h4>
                            <small>Course Links & Files</small>
                            <hr>
                            <a href="{{ route('courses.show', $course->reference) }}" class="btn btn-primary btn-sm">Go to My Campus</a>
                        </div>

                        @if($course->link)
                            <a href="{{ $course->link }}" target="_blank" class="btn btn-primary">Click Here to Access the iLearn course platform</a>
                            <hr>
                        @endif

                        <table class="table table-striped">
                            <thead>
                                <th>Name</th>
                                <th>Link</th>
                            </thead>
                            <tbody>
                                @if(count($course->links))
                                    @foreach($course->links as $link)
                                        <tr>
                                            <td>{{ $link->name }}</td>
                                            <td><a target="_blank" href="{{ $link->url }}">{{ $link->url }}</a></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2">There are no links available for this course</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>

                        <div class="grades">
                            <div class="header">
                                <h4 class="title">Assessment Grades:</h4>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <th>Name</th>
                                    <th>Grades</th>
                                    <th>Percentage</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @if($courseGrades && count($courseGrades['gradeitems']))
                                        @foreach($courseGrades['gradeitems'] as $gradeitem)

                                            @if ($gradeitem['itemmodule'] == 'quiz')
                                                <tr>
                                                    <td>{{ $gradeitem['itemname'] }}</td>
                                                    @if ($gradeitem['gradedatesubmitted'])
                                                        <td>{{ (int)$gradeitem['gradeformatted'] }} / {{ $gradeitem['grademax'] }}</td>
                                                        <td>{{ $gradeitem['percentageformatted'] }}</td>
                                                        <td><a href="{{ env('MOODLE_BASE_URL').'/grade/report/user/index.php?id='.$courseGrades['courseid']}}" target="_blank" class="btn btn-primary btn-xs">View</a></td>
                                                    @else
                                                        <td colspan="2" style="text-align: center;"><span class="btn btn-info btn-xs" style="cursor: unset;">not attempted yet</span></td>
                                                        <td><a href="{{ env('MOODLE_BASE_URL').'/mod/quiz/view.php?id='.$gradeitem['cmid']}}" target="_blank" class="btn btn-primary btn-xs">Start</a></td>
                                                    @endif
                                                    
                                                </tr>
                                            @endif
                                            
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">There are no grades available for this course</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop