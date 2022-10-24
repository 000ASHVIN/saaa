<div id="sms_create" class="modal fade modal-aside horizontal right">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-send"></i> Send a new SMS</h4>
            </div>
            <div class="modal-body">
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

                <div class="row">
                    <div class="col-md-6">
                        <div v-if="! message || message.length <= 20"><button class="form-control btn btn-info disabled"><i class="fa fa-send"></i> Send SMS</button></div>
                        <button v-else class="btn form-control btn-success" onclick="send(this)"><i class="fa fa-send"></i> Send SMS</button>
                    </div>

                    <div class="col-md-6">
                        <a class="close" style="width: 100%; font-size: 14px; font-weight: 400; line-height: 1.42857143; text-align: center; white-space: nowrap; background-color: #ff0000b8; opacity: 1; color: white; padding: 6px 12px; border-radius: 4px;" data-dismiss="modal"><i class="fa fa-close"></i> Cancel Sending</a>
                    </div>
                </div>

                {!! Form::close() !!}

                <hr>
                <p><strong>Previous SMS's that was sent to this client.</strong></p>
                <hr>

                <div style="max-height: 350px; overflow-x: auto">
                    <table class="table striped table bordered">
                        <thead>
                            <th>From</th>
                            <th>Number</th>
                            <th>Message</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                        @if(count($member->smses))
                            @foreach($member->smses as $sms)
                                <tr>
                                    <td><a href="#" style="color: #5b5b60" data-toggle="tooltip" title="" data-placement="top" data-original-title="{{ $sms->from_name }}">{{ str_limit($sms->from_name, 10) }}</a></td>
                                    <td>{{ $sms->number }}</td>
                                    <td><a href="#" style="color: #5b5b60" data-toggle="tooltip" title="" data-placement="top" data-original-title="{{ $sms->message }}">{{ str_limit($sms->message, 20) }}</a></td>
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
</div>