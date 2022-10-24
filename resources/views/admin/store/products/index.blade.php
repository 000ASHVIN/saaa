@extends('admin.layouts.master')

@section('title', 'Store')
@section('description', 'Poducts')

@section('content')
    <br>
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Create a new product</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            @if(count($products))
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th width="10%">Price</th>
                        <th width="10%">Orders</th>
                        <th width="2%"></th>
                        <th width="2%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->detailedTitle }}</td>
                            <td>{{ currency($product->price) }}</td>
                            <td>{{ count($product->orders) }}</td>
                            <td><a class="btn btn-sm btn-info" href="{{ route('admin.products.edit',$product->id) }}"><i class="fa fa-pencil"></i></a></td>
                            <td>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['admin.products.destroy', $product->id]]) !!}
                                <button class="btn btn-sm btn-danger"><i class="fa fa-close"></i></button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <hr>
                {!! $products->render() !!}
            @else
                <p>There are no products</p>
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