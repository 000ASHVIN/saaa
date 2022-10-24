<div id="statement_sending_{{$member->id}}" class="modal fade horizontal" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Send Message with Statement</h4>
            </div>
            <div class="modal-body">
                <p>
                    If you would like to add a message to the client, please type in your message below. If no message was added the account statement will be sent in HTML format, else the statement will be sent in. PDF
                </p>

                <hr>
                {!! Form::open(['method' => 'post', 'route' => ['send_statement', $member]]) !!}
                <div class="form-group @if ($errors->has('message')) has-error @endif">
                    {!! Form::label('message', 'Message') !!}
                    <textarea name="message" cols="30" rows="10" v-model="message" class="form-control"></textarea>
                    @if ($errors->has('message')) <p class="help-block">{{ $errors->first('message') }}</p> @endif
                </div>

                <hr>

                <div v-show="message">
                    <p><strong>Email Preview...</strong></p>
                    <hr>
                    <p>Dear {{ $member->first_name }}</p>
                    <p style="white-space: pre;">@{{ message }}</p>
                    <p>Should you wish to view your invoices online, kindly login to your online profile and click on the "My Invoices" tab from the side navigation.</p>
                    <p>
                        Kind Regards, <br>
                        {{ auth()->user()->first_name.' '.auth()->user()->last_name }} <br>
                        SA Accounting Academy
                    </p>
                    <hr>
                </div>

                <button data-dismiss="modal" type="button" class="btn btn-warning pull-right">Close</button>
                <button class="btn btn-primary" type="submit" onclick="spin(this)" v-show="message"><i class="fa fa-send-o"></i> Send with Email & PDF</button>
                <button class="btn btn-primary" type="submit" onclick="spin(this)" v-show="! message"><i class="fa fa-send-o"></i> Send HTML only</button>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>