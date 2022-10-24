@extends('admin.layouts.master')

@section('title', 'Pricing Group')
@section('description', 'All Available Pricing Groups')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-12">
                @if(count(\App\Blog\Category::all()))
                    <a href="{{ route('admin.pricing_group.create') }}" class="btn btn-success"><i class="fa fa-newspaper-o"></i> New Pricing Group</a>
                @endif
                <hr>
                <table class="table table-striped table-hover">
                    <thead>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Minimum User</th>
                        <th>Maximum User</th>
                        <th>Is Active</th>
                        <th></th>
                        <th></th>
                    </thead> 
                    <tbody> 
                            
                    @if(count($pricing_groups))
                        @foreach($pricing_groups as $pricing_group)
                            <tr style="height: 50px">
                                <td>{{ str_limit($pricing_group->name, 100) }}</td>
                                <td>{{ $pricing_group->price }}</td>
                                <td>{{ $pricing_group->min_user }}</td>
                                <td>{{ $pricing_group->max_user }}</td>
                                <td>
                                    @if($pricing_group->is_active == 1)
                                        Yes
                                    @else
                                        No
                                    @endif 
                                </td>

                               <td><a href="{{ route('admin.pricing_group.edit', $pricing_group->id) }}" class="btn btn-sm btn-secondary btn-circle" style="background-color: #21679b; border-color: #21679b; color: white"><i class="fa fa-pencil"></i></a></td>
                               <td><a href="{{ route('admin.pricing_group.destroy', $pricing_group->id) }}" class="btn btn-sm btn-danger btn-circle confirm-delete"  onclick="return confirm('Are you sure to delete this item?')"><i class="fa fa-close"></i></a></td>

                              
                            </tr>
                        @endforeach
                    @else
                         <tr>
                            <td colspan="6">There are no news articles at this point</td>
                        </tr>
                    @endif
                    </tbody>
                </table> 
                {!! $pricing_groups->appends(Input::except('page'))->render() !!}
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