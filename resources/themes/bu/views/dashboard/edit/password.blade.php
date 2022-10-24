@extends('app')

@section('title', 'Edit Password')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li hreff="#">Edit</li>
                        <li class="active">Password</li>
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

                <form action="{{ route('dashboard.edit.password') }}" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="control-label">Current Password</label>
                        <input name="current_password" type="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label">New Password</label>
                        <input name="new_password" type="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Re-type New Password</label>
                        <input name="new_password_confirmation" type="password" class="form-control">
                    </div>

                    <div class="margin-top10">
                        <button class="btn btn-primary"><i class="fa fa-check"></i> Change Password</button>
                    </div>

                </form>

            </div>
        </div>
    </section>
@stop