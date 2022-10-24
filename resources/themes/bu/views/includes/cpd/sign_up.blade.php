<div id="cpd_sign_up_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
            </div>

            <div class="modal-body">
                <p>Dear {{ $user->first_name.' '.$user->last_name }}</p>

                <p>Please take note your new subscription invoice will be generated on {{ date_format(\Carbon\Carbon::now()->addMonth(), 'd F Y') }}</p>

                {!! Form::open(['method' => 'post', 'route' => 'subscriber_cpd.join_subscription']) !!}
                    {!! Form::label('new_plan', 'Please select your new plan') !!}
                    {!! Form::select('new_plan', \App\Subscriptions\Models\Plan::where('inactive', false)->get()->pluck('name', 'id')->toArray(), null, ['class' => 'form-control']) !!}
                {!! Form::close() !!}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>