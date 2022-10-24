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
                    <div class="table-responsive">
                        <table class="table table-bordered table-vertical-middle">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Ticket</th>
                                <th>CPD Source</th>
                                <th width="20">Hours</th>
                                <th width="10"></th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(count($member->cpds))
                                @foreach($member->cpds as $cpd)
                                    <tr>
                                        <td>{{ $cpd->date->toFormattedDateString() }}</td>
                                        <td><a target="_blank" href="{{ route('ticket_edit', $cpd->ticket_id) }}">{{ ($cpd->ticket_id ? : "-") }}</a></td>

                                        <td>{{ $cpd->source }}</td>

                                        <td class="text-center">{{ $cpd->hours }}</td>

                                        {!! Form::open(['method' => 'Post', 'route' => ['dashboard.cpd.destroy', $cpd->id]]) !!}
                                        <td>
                                            <button type="submit" class="btn btn-xs btn-primary" onclick="spin(this)"><i class="fa fa-close"></i></button>
                                        </td>
                                        {!! Form::close() !!}
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">
                                        <p>Member currently have no CPD entries</p>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <script>
        $( document ).ready( function( )
        {$( '.delete' ).bootstrap_confirm_delete(
            {
                debug:    false,
                heading:  'Remove CPD Entry',
                message:  'Are you sure you want to delete this entry?',
                data_type:'post',
                callback: function ( event )
                {
                    var button = event.data.originalObject;
                    button.closest( 'form' ).submit();
                },
            }
        );
        } );
    </script>

    @include('admin.members.includes.spin')
@stop