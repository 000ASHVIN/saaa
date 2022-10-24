@extends('admin.layouts.master')

@section('title', 'Email Lists')
@section('description', 'Email List Export')

@section('content')
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ route('admin.exports.all_webinar_tickets') }}" class="btn btn-default"><span
                                class="fa fa-list"></span> Download all users that has purchased any Webinar</a>
                </div>
                <div class="form-group">
                    <a href="{{ route('admin.exports.all_cpd_members_with_invoice') }}" class="btn btn-default"><span
                                class="fa fa-list"></span> Download All CPD Members with subscription invoice</a>
                </div>
                <div class="form-group">
                    <a href="{{ route('admin.exports.all_store_order_members') }}" class="btn btn-default"><span
                                class="fa fa-list"></span> Download all users that has purchased any Store Products</a>
                </div>
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