@extends('admin.layouts.master')

@section('title', 'System Categories')
@section('description', 'All Categories')

@section('content')
<section>
<div class="container-fluid container-fullw padding-bottom-10 bg-white">
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success"><i class="fa fa-user-plus"></i> New Category</a>
            <hr>
         
                <div class="panel-group" id="accordion">
                @if(count($categorys))
                    @foreach($categorys as $cat)
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#{{ $cat->id }}" style="text-transform: capitalize">{{ $cat->slug }}</a>

                                    <div class="label pull-right"><a href="{{ route('admin.categories.edit', $cat->slug) }}" class="label label-info">Edit</a>
                                    
                                       
                                    
                                    </div>
                                    <div class=" label pull-right">
                                        {!! Form::open(['method' => 'POST', 'route' => 'admin.categories.destroy']) !!}
                                        <input type="hidden" name="hdn_category_id" value="{{ $cat->id }}" >
                                        <button class="btn btn-sm btn-danger" style="margin-top:-8px;">Delete</button>
                                    {!! Form::close() !!}
                                    </div>
                                    <div class="label label-no-plan pull-right">Categories: {{ count($cat->childCategory()) }}</div>
                                </h4>
                            </div>
                        
                            <div id="{{ $cat->id }}" class="collapse">
                                <div class="">
                                @if(count($cat->childCategory()))
									@foreach($cat->childCategory() as $category)
										@include('admin.blog.categories.category')
									@endforeach	 
                                    
                                @else
                                        <h4>No category</h4>
                                @endif           
                                </div>
                            </div>
                        </div>
                        @endforeach 
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-success"><i class="fa fa-user-plus"></i> New Category</a>
                <hr>
                <table class="table table-striped table-hover">
                    <thead>
                        <th>Title</th>
                        <th>Posts</th>
                        <th>Edit</th>
                    </thead>
                    <tbody>
                    @if(count($categories))
                        @foreach($categories as $category)
                            <tr style="height: 50px">
                                <td>{{ $category->title }}</td>
                                <td>{{ count($category->posts) }}</td>
                                <td><a href="{{ route('admin.categories.edit', $category->slug) }}" class="label label-info">Edit</a></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">There are no news categories at this point</td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                {!! $categories->render() !!}
            </div>
        </div>
    </div> -->

@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop