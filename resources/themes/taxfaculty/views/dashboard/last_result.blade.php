@extends('app')

@section('title', 'Assesment Last Result')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li><a href="{{ route('dashboard.tickets.links-and-resources',$ticket->id).'?recordings=1' }}">{{ $ticket->event->name }}</a></li>
                        <li class="active">{{ $assessment->title }}</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('styles')
<style type="text/css">
    td.last_result_question{
        overflow: hidden;
        /* width: 500px; */
        text-overflow: ellipsis; 
    }
    td > * {
        width: inherit;
    }
    .last_result_question .result {
        margin-top: 20px;
    }
    .certificate {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .alert {
        max-width:100px;
        padding:3.5px;
        margin-bottom: 0;
    }
    .alert.passed {
        color:#8cc03c;
    }
    </style>
@endsection

@section('content')

    <div class="container">
    <br>
        @include('dashboard.includes.sidebar')
        <div class="col-lg-9 col-md-9 col-sm-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="">
                        <div class="table-heading certificate">
                            <div>
                                @if ($result && $cpd)
                                    <span class="text-left">
                                        <a target="_blank" href="{{route('dashboard.cpd.certificate',[$cpd->id])}}" class="btn btn-primary">
                                            <i class="fa fa-eye"></i> View Certificate
                                        </a>
                                    </span>
                                @endif
                            </div>
                            
                            <h4 class="text-right" style="margin-bottom: 0">
                                @if ($result)
                                    <div class="alert alert-success passed">
                                        <strong style="font-weight: 600">Passed</strong> 
                                    </div>
                                @else
                                    <div class="alert alert-danger failed">
                                        <strong style="font-weight: 600">Failed</strong> 
                                    </div>
                                @endif
                                
                            </h4>
                        </div>
                        <table class="table table-bordered">

                            <thead>
                                <th>Index</th>
                                <th>Question</th>
                                <th>Selected Answer</th>
                            </thead>
                            <tbody>
                                @foreach($selectedAnswers as $key => $answer)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="last_result_question">{!! $answer['question'] !!}</td>
                                        <td class="last_result_question">
                                            <div>{{ $answer['option'] }}</div>
                                            <div class="result">Answer: {{ $answer['result'] }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection