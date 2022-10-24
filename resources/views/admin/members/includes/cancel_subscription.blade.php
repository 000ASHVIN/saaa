
<div id="cancel_subscription" class="modal fade text-left">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'post', 'route' => ['admin.members.cancel_subscription', $member->id]]) !!}

            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('description', 'Subscription cancellation reason') !!}
                    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '5']) !!}
                </div>
                <hr>

                <div class="alert alert-warning">
                    <p>If CPD subscription should be cancelled according to our terms & conditions then <strong>do not specify</strong> a date, if the date is selected, the subscription will be cancelled on the selected day, this cannot be undone.</p>
                </div>

                <div class="checkbox clip-check check-primary">
                    <input type="checkbox" id="custom_date" name="custom_date" value="true" v-model="custom_date">
                    <label for="custom_date">
                        Subscription cancellation date.
                    </label>
                </div>

                <div v-show="custom_date">
                    <hr>
                    <div class="form-group @if ($errors->has('date_range')) has-error @endif">
                        {!! Form::label('date_range', 'Please select Date') !!}
                        {!! Form::input('text', 'date_range', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('date_range')) <p class="help-block">{{ $errors->first('date_range') }}</p> @endif
                    </div>
                </div>

                <hr>
                <div class="checkbox clip-check check-primary">
                    <input type="checkbox" id="invoices" name="cancel_invoices" value="true">
                    <label for="invoices">
                        Cancel unpaid subscription invoices
                    </label>
                </div>

                <hr>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-info">Confirm Cancellation</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>