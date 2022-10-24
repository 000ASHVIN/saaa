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
                <div class="row">
                    <div class="col-md-12">
 
                        <!--<webinar-order-template :webinars="{{ $videos->toJson() }}" inline-template>
                            {!! Form::open(['method' => 'post', 'route' => ['order_event_for_user', $member->id]]) !!}
                           
                            <fieldset>
                                <legend>
                                    Select Event
                                </legend>
                                <label for="webinar_id" style="width: 100%">
                                    <select  v-model="selectedWebinarId" name="webinar_id"
                                            id="webinar_id" style="width: 100%">
                                        <option v-for="webinar in webinars" value="@{{ webinar.id }}">@{{ webinar.title }}</option>
                                    </select>
                                </label>
                            </fieldset>

                            </div>
                            <div v-if="selectedWebinarId">{!! Form::submit('Generate PO', ['class' => 'btn btn-primary']) !!}</div>
                            
                            {!! Form::close() !!}
                        </webinar-order-template>-->

                             {!! Form::open(['method' => 'post', 'route' => ['order_webinars_on_demand_for_user', $member->id]]) !!}
                           
                            <fieldset>
                                <legend> 
                                    Select Videos
                                </legend>
                                    {!! Form::label('videos', 'videos') !!}
                                    {!! Form::select('videos[]', $videos, null, ['class' => 'form-control select2', 'multiple'=>true]) !!}
                            </fieldset>

                            </div>
                            {!! Form::submit('Generate Webinar Order', ['class' => 'btn btn-primary']) !!}
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/assets/themes/saaa/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Profile.init();
        });
        $('.select2').select2();
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop