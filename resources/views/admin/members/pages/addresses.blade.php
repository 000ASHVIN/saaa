@extends('admin.layouts.master')
@section('title', $member->first_name . ' ' . $member->last_name)
@section('description', 'User Profile')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <style>
        .daterangepicker{
            z-index:99999!important;
        }
    </style>
@stop

@section('content')

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                <a class="btn btn-default"  href="{{ route('member.addresses.create',['member'=>$member->id]) }}">Create</a>
                @if(count($member->addresses))
                    @foreach($member->addresses->chunk(3) as $chunk)
                        <div class="row">
                            @foreach($chunk as $address)
                                <div class="col-md-4">
                                    <div class="panel panel-white">
                                        <div class="panel-heading border-light">
                                            <h2 class="panel-title">
                                                <i class="fa fa-map-marker"></i> {{ ucwords($address->type) }} Address
                                                <div class="pull-right">
                                                    @if($address->primary)
                                                        <a data-toggle="tooltip" data-placement="top" title="" data-original-title="Primary Address">
                                                            <i class="fa fa-star"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </h2>

                                        </div>
                                        <div class="panel-body">
                                <span class="block">
                                    {{ ucwords($address->line_one) }} <br>
                                    {{ ucwords($address->line_two) }} <br>
                                    {{ ucwords($address->city) }} <br>
                                    {{ ucwords($address->province) }} <br>
                                    {{ ucwords($address->country) }} <br>
                                    {{ $address->area_code }}
                                </span>
                                <div >
                                <a class="btn btn-default"  href="{{ route('member.addresses.edit',['member'=>$member->id,'id'=>$address->id]) }}">Edit</a>
                                </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info margin-bottom-30">
                                <strong>Member</strong> currently have no address listed.
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{--@include('admin.members.includes.statement.confirm')--}}
@stop

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script src="/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop