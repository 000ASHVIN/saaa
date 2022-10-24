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

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="border-box">
                            <table class="table table-hover">
                                <thead>
                                    <th>Name</th>
                                    <th>Reference</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th class="text-right"></th>
                                </thead>
                                <tbody>
                                @if(count($courses))
                                    @foreach($courses as $course)
                                        <tr style="display: table-row;">
                                            <td>
                                                {{ str_limit($course->title, 30) }}
                                            </td>
                                            <td>
                                                {{ $course->reference }}
                                            </td>
                                            <td>
                                                {{ date_format($course->start_date, 'd F Y') }}
                                            </td>
                                            <td>
                                                {{ date_format($course->end_date, 'd F Y') }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('dashboard.courses.show', $course->reference) }}" class="btn btn-primary btn-xs">Show</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">You have no courses available.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="text-left">
                            {!! $courses->render() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@stop