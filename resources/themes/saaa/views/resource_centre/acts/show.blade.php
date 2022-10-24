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
    <i class="fa fa-graduation-cap"></i> {{ $actList->name }}
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
                        <li class="active">{{ $actList->name }}</li>
                    </ol>
                </li>
            </ol>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="list-group panel" id="acts">
                    @foreach($acts as $act)
                    @if(count($act->children))
                        <a href="#act-{{ $act->id }}" class="list-group-item text-white" style="background-color: #173175" data-toggle="collapse"><i class="fa fa-caret-down"></i> {{ $act->name }}</a>
                        <div class="collapse topgroup" id="act-{{ $act->id }}">
                            @foreach($act->children as $child)                                
                                @if(count($child->children))
                                    <a href="#act-{{ $child->id }}" class="list-group-item" style="background-color: #e3e3e3" data-toggle="collapse"><i class="fa fa-caret-down"></i> {{ $child->name }}</a>
                                    <div class="collapse" id="act-{{ $child->id }}">
                                        @foreach($child->children as $grandchild)
                                            @if(count($grandchild->children))
                                                <a href="#act-{{ $grandchild->id }}" class="list-group-item" style="background-color: #ececec" data-toggle="collapse"><i class="fa fa-caret-down"></i> {{ $grandchild->name }}</a>
                                                <div class="collapse" id="act-{{ $grandchild->id }}">
                                                    @foreach($grandchild->children as $grandgrandChild)
                                                        <a href="#" class="list-group-item" onclick="event.preventDefault(); toggleAct(`{{ $grandgrandChild->name }}`, `{{ $grandgrandChild->content }}`)">{{ $grandgrandChild->name }}</a>
                                                    @endforeach
                                                </div>
                                            @else
                                                <a href="#" class="list-group-item" onclick="event.preventDefault(); toggleAct(`{{ $grandchild->name }}`, `{{ $grandchild->content }}`)">{{ $grandchild->name }}</a>
                                            @endif                                            

                                        @endforeach
                                    </div>
                                @else
                                    <a href="#" class="list-group-item toplevel" onclick="event.preventDefault(); toggleAct(`{{ $child->name }}`, `{{ $child->content }}`)">{{ $child->name }}</a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <a href="#" class="list-group-item list-group-item" onclick="event.preventDefault(); toggleAct(`{{ $act->name }}`, `{{ $act->content }}`)">{{ $act->name }}</a>
                    @endif
                @endforeach
            </div>
            </div>

            <div class="col-md-7" id="content">
                <div class="panel panel-default">
                    <div class="panel-header">
                        <h4 class="panel-heading" id="act_title"></h4>
                    </div>
                    <div class="panel-body" id="act_body"></div>
                </div>                
            </div>
            
        </div>
    </div>
</section>

@endsection

@section('scripts')
    <script>
        $( document ).ready(function() {
            $(".toplevel:first").click();
            $(".topgroup:first").addClass('in');
        });


        function toggleAct(actName, actContent) {
            $("#act_title").html(actName);
            $("#act_body").html(actContent);
        }
    </script>
@endsection