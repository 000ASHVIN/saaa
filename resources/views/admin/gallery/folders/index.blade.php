@extends('admin.layouts.master')

@section('title', 'Gallery')
@section('description', 'Folders')

@section('content')
    <seection>
        <div class="container-fluid container-fullw bg-white">
            <div class="row">
                <a href="{{ route('admin.folders.create') }}" class="btn btn-success pull-right"><i class="fa fa-pencil"></i> Create new</a>
                <table class="table table-striped">
                    <thead>
                        <th>Folder</th>
                        <th>Date</th>
                        <th>Photos</th>
                        <th width="5%"></th>
                        <th width="3%"></th>
                        <th width="5%"></th>
                    </thead>
                    <tbody>
                    @if(count($folders))
                        @foreach($folders as $folder)
                            <tr>
                                <td>{{ ucfirst($folder->title) }}</td>
                                <td>{{ \Carbon\Carbon::parse($folder->date)->diffForHumans() }}</td>
                                <td><div class="btn btn-success btn-sm"><i class="fa fa-photo"></i> {{ count($folder->photos) }} Photos</div></td>
                                <td><div class="btn btn-primary btn-sm"><a style="color: white" href="gallery/upload/{{ $folder->slug }}"><i class="fa fa-photo"></i> Add New Photos</a></div></td>
                                <td><div class="btn btn-primary btn-sm"><a style="color: white" href="{{ route('admin.folders.edit', $folder->slug) }}"><i class="fa fa-pencil"></i> Edit</a></div></td>
                                <td>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['admin.folders.destroy', $folder->slug]]) !!}
                                        <button class="btn btn-danger btn-sm" onclick="spin(this)" style="color: white" href="#"><i class="fa fa-close"></i> Remove</button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                    <tr>
                        <td colspan="6">No Folders has been created..</td>
                    </tr>
                    @endif
                    </tbody>
                </table>

                {!! $folders->render() !!}
            </div>
        </div>
    </seection>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
        $('.is-date').datepicker;

        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Removing..`;
        }
    </script>
@stop