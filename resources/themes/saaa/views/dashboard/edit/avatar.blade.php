@extends('app')

@section('title', 'Edit Avatar')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li hreff="#">Edit</li>
                        <li class="active">Avatar</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                @include('dashboard.edit.nav')
                <div class="margin-top-20"></div>

                {!! Form::open(['files' => 'true', 'route' => 'dashboard.edit.avatar']) !!}

                    <div class="form-group">

                        <div class="row">
                            <div class="col-md-3 col-sm-4">
                                <div class="thumbnail">
                                    <img class="img-responsive" src="{{ $user->avatar }}" alt="Image"
                                         onError="this.onerror=null;this.src='https://accountingacademy.co.za/assets/frontend/images/default_avatar.jpg';"
                                    >
                                </div>
                            </div>

                            <div class="col-md-9 col-sm-8">
                                <div class="sky-form nomargin">
                                    <label class="label">Select file</label>
                                    <label for="file" class="input input-file">
                                        <div class="button">
                                            <input type="file"  name="avatar" onchange="this.parentNode.nextSibling.value = this.value">Browse
                                        </div><input type="text" readonly="">
                                    </label>
                                </div>



                                {{--<a href="#" class="btn btn-danger btn-xs noradius"><i class="fa fa-times"></i> Remove Avatar</a>--}}
                                {{--<div class="clearfix margin-top-20">--}}
                                    {{--<span class="label label-info">NOTE! </span>--}}
                                    {{--<p>--}}
                                       {{--Please note that all images will be resized after uploading <br>--}}
                                       {{--The dimensions of the image will be 400 x 400.--}}
                                   {{--</p>--}}
                                {{--</div>--}}

                                <div class="margiv-top10">
                                  {!! Form::submit('Upload Image', ['class' => 'btn btn-primary']) !!}
                              </div>
                            </div>
                        </div>
                    </div>



                {!! Form::close() !!}

            </div>
        </div>
    </section>
@stop