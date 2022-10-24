<div id="cpd_change_package" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change my package</h4>
            </div>

            <div class="modal-body">

                <p>Dear {{ $user->first_name.' '.$user->last_name }}</p>
                <p>Your current subscription is <span style="color: #173175">{{ $user->subscription('cpd')->plan->name }}</span>.</p>

                <p>Please take note your new subscription invoice will be generated on {{ date_format(\Carbon\Carbon::now()->addMonth(), 'd F Y') }}</p>

                {!! Form::open(['method' => 'post', 'route' => 'subscriber_cpd.change_subscription']) !!}
                    {!! Form::label('new_plan', 'Please select your new plan') !!}
                    {!! Form::select('new_plan', \App\Subscriptions\Models\Plan::where('inactive', false)->where('id', '!=', $user->subscription('cpd')->plan->id )->get()->pluck('name', 'id')->toArray(), null, ['class' => 'form-control']) !!}
                {!! Form::close() !!}
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-default">Confirm</button>
            </div>
        </div>

    </div>
</div>