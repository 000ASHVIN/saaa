@extends('admin.layouts.master')

@section('title', 'All Presenters')
@section('description', 'This list will display all presenters on our system')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

@endsection

@section('content')
    <br>
    <div class="row">
        <div class="panel-white col-sm-12">
            <meta name="csrf-token" content="{{ csrf_token() }}" />

            <br>
            <table class="table table-striped table-hover">
                <thead>
                    <th></th>
                    <th>Position</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Events</th>
                    <th>Edit</th>
                    <th>Remove</th>
                </thead>
                <tbody class="sortable" data-entityname="presenters">
                @foreach ($presenters as $presenter)
                    <tr data-itemId="{{{ $presenter->id }}}">
                        <td class="sortable-handle"><span class="glyphicon glyphicon-sort"></span></td>
                        <td class="id-column">{{{ $presenter->position }}}</td>
                        <td>{{ $presenter->name }}</td>
                        <td>{{ str_limit($presenter->title, 30) }}</td>
                        <td><div class="label label-info">{{ $presenter->events->count() }}</div></td>
                        <td><a class="btn btn-primary btn-sm" href="{{ route('admin.presenters.edit', $presenter->id)  }}">Edit | Update</a></td>
                        <td>
                            {!! Form::open(['method' => 'DELETE', 'route' => ['admin.presenters.destroy', $presenter->id]]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {!! $presenters->render() !!}
        </div>
    </div>
@stop
@section('scripts')
    <script src="/assets/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        var changePosition = function(requestData){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                'url': '/admin/sort',
                'type': 'POST',
                'data': requestData,
                'success': function(data) {
                    if (data.success) {
                        swal({
                            title: "Success!",
                            text: "Your position has been saved!",
                            showConfirmButton: false,
                            timer: 1000,
                            type: "success"
                        })
                    } else {
                        console.error(data.errors);
                    }
                },
                'error': function(){
                    console.error('Something wrong!');
                }
            });
        };

        $(document).ready(function(){
            var $sortableTable = $('.sortable');
            if ($sortableTable.length > 0) {
                $sortableTable.sortable({
                    handle: '.sortable-handle',
                    axis: 'y',
                    update: function(a, b){

                        var entityName = $(this).data('entityname');
                        var $sorted = b.item;

                        var $previous = $sorted.prev();
                        var $next = $sorted.next();

                        if ($previous.length > 0) {
                            changePosition({
                                parentId: $sorted.data('parentid'),
                                type: 'moveAfter',
                                entityName: entityName,
                                id: $sorted.data('itemid'),
                                positionEntityId: $previous.data('itemid')
                            });
                        } else if ($next.length > 0) {
                            changePosition({
                                parentId: $sorted.data('parentid'),
                                type: 'moveBefore',
                                entityName: entityName,
                                id: $sorted.data('itemid'),
                                positionEntityId: $next.data('itemid')
                            });
                        } else {
                            console.error('Something wrong!');
                        }
                    },
                    cursor: "move"
                });
            }
        });
    </script>
@stop