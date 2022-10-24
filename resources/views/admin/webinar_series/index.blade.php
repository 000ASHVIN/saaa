@extends('admin.layouts.master')

@section('title', 'Webinar Series')
@section('description', 'All Webinar Series')

@section('content')
    <br>
      <div class="container-fluid container-fullw padding-bottom-10 bg-white">
      <div class="row">
        <div class="col-md-4">
            {!! Form::open(['method' => 'get', 'route' => 'admin.webinar_series.index']) !!}
            <div class="form-group @if ($errors->has('event_name')) has-error @endif">
                {!! Form::label('name', 'Search Series') !!}
                {!! Form::input('text', 'name', \Input::has('name')?\Input::get('name'):"", ['class' => 'form-control', 'placeholder' => 'Searching']) !!}
                @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
            </div>
        </div>

        <div class="col-md-4">
            <button class="btn btn-primary" onclick="spin(this)"  style="top: 22px;"><i class="fa fa-search" ></i> Search</button>
            {!! Form::close() !!}
        </div>
    </div>
            <hr>

    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('admin.webinar_series.create') }}" class="btn btn-primary pull-right">Add a series</a>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-12">
            @if(count($webinar_series))
                <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Discount %</th>
                            <th>Sales Price</th>
                            <th>Webinars</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($webinar_series as $series)
                        <tr>
                            <td>{{ $series->title }}</td>
                            <td>R{{ number_format($series->OriginalAmount, 2) }}</td>
                            <td>{{ number_format($series->discount, 2) }}%</td>
                            <td>R{{ number_format($series->amount, 2) }}</td>
                            <td>{{ $series->webinarsCount }}</td>
                            <td>
                                <a class="btn btn-xs btn-info" href="{{ route('admin.webinar_series.edit',$series->id) }}">
                                    <i class="fa fa-pencil"></i> Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <hr>
                {!! $webinar_series->appends(Input::except('page'))->render() !!}
            @else
                <p>There are no series</p>
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