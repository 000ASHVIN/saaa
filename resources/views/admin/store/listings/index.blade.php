@extends('admin.layouts.master')

@section('title', 'Store')
@section('description', 'Listings')

@section('content')
    <br>
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('admin.listings.create') }}" class="btn btn-info pull-right"><i class="fa fa-pencil"></i> Create a new listing</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            @if(count($listings))
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Listing</th>
                            <th width="10%">Products</th>
                            <th width="2%"></th>
                            <th width="2%"></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($listings as $listing)
                        <tr>
                            <td>{{ ucfirst($listing->title) }}</td>
                            <td>{{ count($listing->products) }}</td>
                            <td><a class="btn btn-sm btn-info" href="{{ route('admin.listings.edit',$listing->id) }}"><i class="fa fa-pencil"></i></a></td>
                            <td>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['admin.listings.destroy', $listing->id]]) !!}
                                <button class="btn btn-sm btn-danger"><i class="fa fa-close"></i></button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <hr>
                {!! $listings->render() !!}
            @else
                <p>There are no listings</p>
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