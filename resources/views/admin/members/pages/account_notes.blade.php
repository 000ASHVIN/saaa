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
    <?php
        $user_has_access = userHasAccess(auth()->user());
    ?>
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group text-right">
                            <a href="#" class="btn btn-info" data-toggle="modal" data-target="#add_activity"><i class="fa fa-pencil"></i> Add Note</a>
                        </div>

                        <div class="panel panel-white" id="panel3">
                            <div class="panel-heading panel-default">
                                <label for="notes">Please select your notes.</label>
                                <select name="notes" id="notes" class="form-control" v-model="NoteSelected">
                                    <option value="" selected>Please select</option>
                                    @foreach($member->notes->groupBy('type') as $key => $value)
                                        <option value="{{$key}}">{{ str_replace('_', ' ', ucfirst($key)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="panel-body">
                                <div class="" v-if="NoteSelected == ''">
                                    <div class="alert alert-warning" style="margin-bottom: 0px;">
                                        <p>
                                            You have not selected anything.
                                        </p>
                                    </div>
                                </div>

                                @foreach($member->notes->groupBy('type') as $key => $value)
                                    <div v-if="NoteSelected == '{!! $key !!}'">
                                        @foreach($value as $note)
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4>
                                                        <div class="pull-left"><small><strong>Created by:</strong> {{ $note->logged_by }}</small></div>
                                                        <div class="pull-right"><small><strong>Created at:</strong> {{ $note->created_at->toFormattedDateString() }}</small> </div>
                                                    </h4>
                                                </div>
                                                <div class="panel-body" style="border-bottom: 1px solid #e3e3e3;">
                                                    <div>
                                                        <span class="label label-success">{!! ucfirst(str_replace('_', ' ', $note->type)) !!}</span>
                                                        @if($note->invoice)<span class="label label-info"><a href="{{ route('invoices.show', $note->invoice->id) }}" style="color: white!important;">#{{$note->invoice->reference}}</a></span>@endif
                                                        @if($note->invoice)<span class="label label-warning"><a href="#" style="color: white!important;">{{ ($note->commision_claimed ? " Claimed" : "Not Claimed" ) }}</a></span>@endif
                                                    </div>
                                                    <hr>
                                                    <p>{!! $note->description !!}</p>
                                                    <hr>
                                                    <p>
                                                        @if(auth()->user()->hasRole('super') || auth()->user()->hasRole('accounts-administrator'))
                                                            @if(auth()->user()->hasRole('super'))
                                                                <a class="btn btn-xs btn-primary" href="#" data-toggle="modal" data-target="#note_{{$note->id}}"><i class="fa fa-pencil"></i> Edit Note</a>
                                                                @if($user_has_access)
                                                                <a class="btn btn-xs btn-danger" href="{{ route('admin.notes.destroy', $note->id) }}"><i class="fa fa-close"></i> Delete Note</a>
                                                                @endif
                                                                @include('admin.members.includes.edit_note')
                                                            @endif

                                                            @if($note->type == 'payment_arrangement')
                                                                <a class="btn btn-xs btn-success" href="{{ ($note->completed ? "#" : route('admin.notes.complete', [$note->id, $member->id])) }}">{{ ($note->completed ? "PTP Completed" : "Complete Now") }}</a>
                                                            @endif
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                @include('admin.members.includes.add_activity')
            </div>
        </div>
    </div>

    {{--@include('admin.members.includes.statement.confirm')--}}
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Profile.init();
        });
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop