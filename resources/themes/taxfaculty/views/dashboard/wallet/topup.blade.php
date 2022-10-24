<div class="modal fade" id="walletTopup" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Topup your wallet.</h4>
            </div>

            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'route' => ['dashboard.wallet.topup', $user->id, $user->primaryCard->id]]) !!}

                    <h5>Your Primary Card</h5>
                    @if($user->primaryCard())
                        <ul class="list-group" style="margin-bottom: 0px;">
                            <li class="list-group-item text-primary">
                                @if($user->primaryCard->visa)
                                    <img src="http://imageshack.com/a/img923/1633/MrzQgy.jpg" alt="Visa">
                                @else
                                    <img src="http://imageshack.com/a/img922/7215/rlMbNo.jpg" alt="Visa">
                                @endif
                                <span>{{$user->primaryCard->number}} <span class="pull-right">Expires: ({{$user->primaryCard->exp_month}}/{{$user->primaryCard->exp_year}})</span></span>
                            </li>
                        </ul>
                    @endif
                <hr>

                <div class="form-group @if ($errors->has('amount')) has-error @endif">
                    {!! Form::label('amount', 'Enter your amount') !!}
                    {!! Form::input('text', 'amount', null, ['class' => 'form-control', 'placeholder' => 'R500.00']) !!}
                    @if ($errors->has('amount')) <p class="help-block">{{ $errors->first('amount') }}</p> @endif
                </div>


            </div>

            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" onclick="spin(this)">Confirm</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>
