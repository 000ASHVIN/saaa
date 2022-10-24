@extends('admin.layouts.master')

@section('title', 'Store')
@section('description', 'Categories')

@section('content')
    <section>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('admin.store_categories.create') }}" class="pull-right btn btn-primary"><i class="fa fa-pencil"></i> Create New</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <th>Category title</th>
                            <th width="10%">Display in Store</th>
                            <th width="10%">Total listings</th>
                            <th width="2%"></th>
                            <th width="2%"></th>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->title }}</td>
                                    <td>{{ ($category->active ? "Yes" : "No") }}</td>
                                    <td>{{ $category->listings->count() }}</td>
                                    <td><a href="{{ route('admin.store_categories.edit', $category->id) }}" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i></a></td>
                                    <td>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['admin.store_categories.destroy', $category->id]]) !!}
                                            <button class="btn btn-sm btn-danger"><i class="fa fa-close"></i></button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $categories->render() !!}
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop