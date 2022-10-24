@extends('admin.layouts.master')

@section('title', 'News Authors')
@section('description', 'All Authors Available')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin.authors.create') }}" class="btn btn-success"><i class="fa fa-user-plus"></i> New Author</a>
                <hr>
                <table class="table table-striped table-hover">
                    <thead>
                        <th>Name</th>
                        <th>Posts</th>
                        <th>Edit</th>
                    </thead>
                    <tbody>
                    @if(count($authors))
                        @foreach($authors as $author)
                            <tr style="height: 50px">
                                <td>{{ $author->name }}</td>
                                <td>{{ count($author->posts) }}</td>
                                <td><a href="{{ route('admin.authors.edit', $author->id) }}" class="label label-info">Edit</a></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">There are no news authors at this point</td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                {!! $authors->render() !!}
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