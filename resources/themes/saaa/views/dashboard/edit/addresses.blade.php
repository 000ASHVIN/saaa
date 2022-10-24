@extends('app')

@section('title', 'Edit Addresses')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li hreff="#">Edit</li>
                        <li class="active">Addresses</li>
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

                <div class="row">
                    <div class="col-md-12">
                        @if(count($user->addresses))
                            @foreach($user->addresses->chunk(3) as $chunk)
                                <div class="row">
                                    @foreach($chunk as $address)
                                        <div class="col-md-4">
                                            <div class="panel panel-default margin-bottom-20">
                                                <div class="panel-heading panel-heading-transparent">
                                                    <h2 class="panel-title"><i class="fa fa-map-marker"></i> {{ ucwords($address->type) }} Address</h2>
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
                                                </div>
                                                <div class="panel-footer">
                                                    <div class="row">
                                                        <div class="col-md-7">
                                                            @if($address->primary)
                                                                <a data-toggle="tooltip"
                                                                   data-placement="top"
                                                                   title="This is your Primary Address"
                                                                        >
                                                                    <i class="fa fa-star"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                        <div class="col-md-5" style="text-align: right">
                                                            @if(! $address->primary)
                                                            {!! Form::open(['route' => ['dashboard.edit.addresses.set_primary', $address->id], 'style' => 'display: inline; margin-bottom: 0px;']) !!}
                                                                <button class="margin-right-10"
                                                                   data-toggle="tooltip"
                                                                   data-placement="top"
                                                                   title="Set Address as Primary"
                                                                   type="submit"
                                                                        >
                                                                    <i class="fa fa-check"></i>
                                                                </button>
                                                                {!! Form::close() !!}
                                                            @endif

                                                            {!! Form::open(['method' => 'delete', 'route' => ['dashboard.edit.addresses.delete', $address->id], 'style' => 'display: inline; margin-bottom: 0px;']) !!}
                                                                <button data-toggle="tooltip"
                                                                       data-placement="top"
                                                                       title="Delete Address"
                                                                       type="submit" 
                                                                            >
                                                                    <i class="fa fa-close"></i>
                                                                </button>
                                                            {!! Form::close() !!}
                                                        </div>
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
                                        <strong>You</strong> currently have no addresses, Please click "add new" to start adding your addresses
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- @include('dashboard.edit.includes.edit_address') --}}
                        @include('dashboard.edit.includes.new_address')
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="right">
                            <br>
                            <a href="#" class="btn btn-primary" data-target="#new_address" data-toggle="modal">Add New</a>
                        </div>
                    </div>
                </div>

            </div>

            </div>

        </div>
    </section>
@stop