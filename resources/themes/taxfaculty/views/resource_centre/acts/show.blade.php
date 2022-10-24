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
    <i class="fa fa-graduation-cap"></i> ACT: {{ $actList->title }}
@stop

<section class="page-header hidden-print">
    <div class="container">
        <h1>@yield('title', 'Page')</h1>
    </div>
</section>

<section class="theme-color hidden-print" style="padding: 0px;">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb" style="padding: 0px">
                        <li><a href="{{ route('resource_centre.home') }}">Resource Centre</a></li>
                        <li><a href="{{ route('resource_centre.legislation.index') }}">Legislation List</a></li>
                        <li class="active">{{ $actList->title }}</li>
                    </ol>
                </li>
            </ol>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="panel-group" id="acts">
                @foreach($acts as $act)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#acts" href="#act_{{ $act->id }}">{{ $act->title }}</a>
                            </h4>
                        </div>
                        <div id="act_{{ $act->id }}" class="panel-collapse {{ ($act->id == $acts->first()->id) ? 'in' : 'collapse' }}">
                            <div class="panel-body">
                                @if(count($act->children))
                                    <table class="table table-striped">
                                        <tbody>
                                        @foreach($act->children as $child)
                                            <tr>
                                                <td>{{ $child->title }}</td>
                                                <td class="text-right"><a href="{{ route('resource_centre.acts.single_show', $child->id) }}">View</a></td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    {!! $act->content !!}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{--<table class="table table-striped">--}}
                {{--<tbody>--}}
                {{--@foreach($act->children as $item)--}}
                    {{--<tr>--}}
                        {{--<td><a href="{{ route('resource_centre.acts.single_show', $item->id) }}">{{ $item->title }}</a></td>--}}
                        {{--<td>--}}
                            {{--@if($item->description)--}}
                                {{--<a href="{{ route('resource_centre.acts.single_show', $item->id) }}"><i class="fa fa-arrow-right"></i></a>--}}
                            {{--@endif--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                {{--@endforeach--}}
                {{--</tbody>--}}
            {{--</table>--}}

        </div>
    </div>
</section>

@endsection