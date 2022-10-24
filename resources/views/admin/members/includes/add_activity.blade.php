<div id="add_activity" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        {!! Form::open(['method' => 'post', 'route' => ['admin.notes.store', $member->id]]) !!}
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h4 class="heaing">Create New Note</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('type', 'Please select type') !!}
                    {!! Form::select('type', [
                        'general' => 'General Information',
                        'email' => 'Email / Notification',
                        'sales' => 'Sales Call / Email',
                        'phone_call' => 'Phone Call',
                        'subscription_discount' => 'Subscription Discount',
                        'payment_arrangement' => 'Payment Arrangement',
                    ],null, ['class' => 'form-control']) !!}    
                </div>

                <div class="form-group">
                    {!! Form::label('description', 'Description') !!}
                    {!! Form::textarea('description', null, ['class' => 'ckeditor form-control']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-default">Create</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>