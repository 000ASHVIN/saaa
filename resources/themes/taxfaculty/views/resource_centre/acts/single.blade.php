@extends('app')

@section('content')

@section('styles')
    <style>
        .topichead{
            display: none;
        }
    </style>
@endsection

@section('title')
    <i class="fa fa-support"></i> ACT: {{ $act->title }}
@stop

<section>
    <div class="container">
        <div class="row">
            <h4>ACT: {{ $act->title }}</h4>
            {!! $act->content !!}
        </div>
    </div>
</section>

@endsection