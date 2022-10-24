@extends('admin.layouts.master')

@section('title', 'Photos')
@section('description', 'Upload: '.ucfirst($folder->title))

@section('styles')
    <style>
        .tooltip-inner{
            background-color: #e3e3e3;
            border: 1px solid #e3e3e3;
        }

        .tooltip-inner img{
            max-width: 400px; !important;
            width: 100%;
        }

        .popover img{
            max-width:100%;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid container-fullw">
        <div class="col-md-offset-3">
            <div class="col-md-6 text-center">
                <div id="message"></div>
                <div id="bussy"></div>

                <div class="alert alert-warning">Please <strong>do not</strong> close this window when uploading your images, Should you close this window you upload will fail!
                    The allowed Mimes:png,gif,jpeg</div>

                <div class="panel panel-white">
                    <div class="panel-heading border-light">
                        <h4 class="panel-title"><i class="fa fa-image"></i> Upload <span class="text-bold">Multiple Images</span></h4>
                    </div>

                    <div class="panel-body">
                        <form action="upload" id="upload" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <input v-model="file" type="file" name="file[]" multiple class="form-control">
                            </div>

                            <div class="form-group text-left">
                                <a href="{{ route('admin.folders.index') }}" class="btn btn-info fileinput-button"><i class="fa fa-arrow-left"></i> Back</a>
                                <button v-if="file" id="uploadButton" class="btn btn-success fileinput-button"><i class="glyphicon glyphicon-plus"></i> Add files...</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid container-fullw">
        <table class="table table-striped">
            <thead>
                <th></th>
                <th>Folder</th>
                <th width="5%">Image Uploaded</th>
                <th width="5%"></th>
            </thead>
            <tbody>
                @if(count($photos))
                    @foreach($photos as $photo)
                        <tr>
                            <td><a data-toggle="tooltip" title="<img src='{{ asset('storage/'.$photo->url) }}' />">View Photo</a></td>
                            <td>{{ ucfirst($photo->folder->title) }}</td>
                            <td><div class="btn btn-sm btn-success"><i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($photo->created_at)->diffForHumans() }}</div></td>
                            <td>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['admin.gallery.destroy', $photo->id]]) !!}
                                    <button class="btn btn-sm btn-danger"><i class="fa fa-close"></i> Remove</button>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">This folder does not have any photos..</td>
                    </tr>
                @endif
            </tbody>
        </table>

        {!! $photos->render() !!}
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script>
        var form = document.getElementById('upload');
        var request = new XMLHttpRequest();

        form.addEventListener('submit', function(e){
            e.preventDefault();
            var formdata = new FormData(form);

            request.open('post', '/admin/gallery/save/upload/{{ $folder->slug }}');
            request.addEventListener("load", transferComplete);
            request.send(formdata);
            document.getElementById('bussy').innerHTML = "<div class=\"alert alert-info\"><i class=\"fa fa-spinner fa-spin\"></i> We are uploading your files, Please wait...</div>";
            $('#uploadButton').addClass('disabled');
            document.getElementById('uploadButton').innerHTML = "<i class=\"fa fa-spinner fa-spin\"></i> Uploading..."
        });

        function transferComplete(data){
            response = JSON.parse(data.currentTarget.response);
            if(response.success){
                document.getElementById('message').innerHTML = "<div class=\"alert alert-success\"><i class='fa fa-check'></i> Successfully Uploaded Files!</div>";
                document.getElementById('bussy').innerHTML = "";
                location.reload();
            }
            if(response.error){
                document.getElementById('message').innerHTML = "<div class=\"alert alert-danger\">Invalid File Formats, Please check and try again!</div>";
                document.getElementById('bussy').innerHTML = "";
                location.reload();
            }
        }

        $('a[data-toggle="tooltip"]').tooltip({
            animated: 'fade',
            placement: 'bottom',
            html: true
        });
    </script>
@stop