@if($category->childCategory()->count())
<div id="accordion{{ $category->id }}">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion{{ $category->id }}" href="#{{ $category->id }}"
                    style="text-transform: capitalize">{{ $category->slug }}</a>
                <div class="label pull-right"><a href="{{ route('admin.categories.edit', $category->slug) }}"
                        class="label label-info">Edit</a>


                </div>
                <div class=" label pull-right">
                    {!! Form::open(['method' => 'POST', 'route' => 'admin.categories.destroy']) !!}
                    <input type="hidden" name="hdn_category_id" value="{{ $category->id }}">
                    <button class="btn btn-sm btn-danger" style="margin-top:-8px;">Delete</button>
                    {!! Form::close() !!}
                </div>
                <div class="label label-no-plan pull-right">Categories: {{ count($category->childCategory()) }}</div>
            </h4>
        </div>
        <div id="{{ $category->id }}" class="collapse">
            <!-- <div class=""> -->
            @if(count($category->childCategory()))
            <table class="table">

                @foreach($category->childCategory() as $category)
                <tbody>
                    @if($category->childCategory()->count())
                    <tr>
                        <td colspan="2">

                            @include('admin.blog.categories.category')

                        <td>
                    </tr>
                    @else
                    <tr>
                        <td width="70%"> {{ $category->title }}</td>
                        <td><a href="{{ route('admin.categories.edit', $category->slug) }}"
                                class="label label-info">Edit</a>
                        <td>
                        <td>
                            {!! Form::open(['method' => 'POST', 'route' => 'admin.categories.destroy']) !!}
                            <input type="hidden" name="hdn_category_id" value="{{ $category->id }}">
                            <button class="btn btn-sm btn-danger">Delete</button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                    @endif
                </tbody>
                @endforeach
            </table>
            @else
            <h4>No category</h4>
            @endif
            <!-- </div> -->
        </div>
    </div>
    </div>



    @else


    <table class="table" width="100">

        <tbody>
            <tr>
                <td width="70%">{{ $category->title }}</td>
                <td><a href="{{ route('admin.categories.edit', $category->slug) }}"
                        class="label label-info pull-right">Edit</a>
                <td>
                <td>
                    {!! Form::open(['method' => 'POST', 'route' => 'admin.categories.destroy']) !!}
                    <input type="hidden" name="hdn_category_id" value="{{ $category->id }}">
                    <button class="btn btn-sm btn-danger">Delete</button>
                    {!! Form::close() !!}
                </td>

            </tr>
        </tbody>

    </table>
    @endif