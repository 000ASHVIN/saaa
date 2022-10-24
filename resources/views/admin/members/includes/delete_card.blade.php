<div id="remove_card{{$member->id}}" class="modal fade horizontal" role="dialog">
    <div class="modal-dialog modal-sm">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    @if ($member->primaryCard->visa)
                        <img src="/assets/frontend/images/cc/visa.png" style="width: 15%" alt="Visa">
                    @else
                        <img src="/assets/frontend/images/cc/Mastercard.png" style="width: 15%" alt="Mastercard">
                    @endif
                    Credit Card
                </h4>
            </div>
                <div class="modal-body">
                    <p><i>By <strong style="color: red">removing</strong> this credit card, we will not be able to charge this credit card for future CPD subscription payments</i></p>
                    <hr>

                    {!! Form::open(['method' => 'POST', 'route' => 'account.billing.remove']) !!}

                    <div class="form-group">
                        <label>Card Number</label>
                        <input type="text" value="{{ $member->primaryCard->number }}" disabled="true" class="form-control">
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Expiry Month</label>
                                <input type="text" value="{{ $member->primaryCard->exp_month }}" disabled="true" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label>Expiry Year</label>
                                <input type="text" value="{{ $member->primaryCard->exp_year }}" disabled="true" class="form-control">
                            </div>
                        </div>

                    {!! Form::hidden('card_id', $member->primary_card, ['id' => 'primary_card']) !!}

                    <hr>
                    <div class="text-center">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-check"></i> Remove</button>
                        <button data-dismiss="modal" class="btn btn-info btn-sm"><i class="fa fa-times"></i> Cancel</button>
                    </div>
               {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>
</div>