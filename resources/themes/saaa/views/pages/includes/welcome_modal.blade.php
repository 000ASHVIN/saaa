<div class="modal fade" id="renew_subscription_modal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content text-center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">CPD Subscription {{ \Carbon\Carbon::now()->year }}</h4>
            </div>
            <div class="modal-body">
                <p>
                    Are you registered for the CPD subscription package? <a href="{{ route('cpd') }}">Click here</a> to find out more about the CPD Subscription package.
                </p>
            </div>
            <div class="modal-footer">
                <center>
                    <a href="{{ route('cpd') }}" class="btn btn-primary">View packages</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </center>
            </div>
        </div>

    </div>
</div>