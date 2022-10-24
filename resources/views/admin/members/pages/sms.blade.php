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
                {!! Form::open(['method' => 'post', 'route' => ['member.sendSms', $member->id]]) !!}

                <div class="form-group @if ($errors->has('number')) has-error @endif">
                    {!! Form::label('number', 'Cellphone Number') !!}
                    {!! Form::input('text', 'number', ($member->cell)? (new \App\NumberValidator())->format($member->cell) : "", ['class' => 'form-control']) !!}
                    @if ($errors->has('number')) <p class="help-block">{{ $errors->first('number') }}</p> @endif
                </div>

                <div v-if="message.length == 160"><div class="alert alert-info">You have exceeded the maximum allowed amount of characters</div></div>

                <div class="form-group @if ($errors->has('message')) has-error @endif">
                    <div class="small pull-right">@{{ message.length }}/160 Max</div>
                    {!! Form::label('message', 'Your Message') !!}
                    {!! Form::textarea('message', null, ['class' => 'form-control', 'v-model' => 'message', 'maxlength' => '160']) !!}
                    @if ($errors->has('message')) <p class="help-block">{{ $errors->first('message') }}</p> @endif
                </div>

                <div v-if="! message || message.length <= 20"><button class="form-control btn btn-info disabled"><i class="fa fa-send"></i> Send SMS</button></div>
                <button v-else class="btn form-control btn-success" onclick="send(this)"><i class="fa fa-send"></i> Send SMS</button>

                {!! Form::close() !!}

                <hr>
                <p><strong>Previous SMS's that was sent to this client.</strong></p>
                <hr>

                <div style="max-height: 350px; overflow-x: auto">
                    <table class="table table-striped">
                        <thead>
                        <th>Date</th>
                        <th>From</th>
                        <th>Number</th>
                        <th>Message</th>
                        <th>Status</th>
                        </thead>
                        <tbody>
                        @if(count($member->smses))
                            @foreach($member->smses as $sms)
                                <tr>
                                    <td>{{ date_format($sms->created_at, 'd F Y') }}</td>
                                    <td><a href="#" style="color: #5b5b60" data-toggle="tooltip" title="" data-placement="top" data-original-title="{{ $sms->from_name }}">{{ $sms->from_name }}</a></td>
                                    <td>{{ $sms->number }}</td>
                                    <td><a href="#" style="color: #5b5b60" data-toggle="tooltip" title="" data-placement="top" data-original-title="{{ $sms->message }}">{{ str_limit($sms->message, 70) }}</a></td>
                                    @if($sms->status == 'OK')
                                        <td>
                                            <a href="#" style="color: #5b5b60" data-toggle="tooltip" title="" data-placement="top" data-original-title="Sent Successfully">
                                                <div class="label label-success"><i class="fa fa-check"></i></div></td>
                                        </a>
                                    @else
                                        <td>
                                            <a href="#" style="color: #5b5b60" data-toggle="tooltip" title="" data-placement="top" data-original-title="Error Sending">
                                                <div class="label label-error"><i class="fa fa-close"></i></div>
                                            </a>
                                        </td>

                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No SMS was sent.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop