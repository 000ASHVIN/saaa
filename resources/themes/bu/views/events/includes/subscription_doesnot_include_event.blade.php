<div class="row">
    <a class="btn" data-popup-open-event="popup-1" href="#" style="display: none"></a>
    <div class="popup" data-popup-event="popup-1" style="z-index: 9999">
        <div class="popup-inner">
            <div class="heading-title heading-dotted text-center">
                <h3>{{ ucfirst(auth()->user()->first_name) }} <span>Head's up!</span></h3>
            </div>
            <p class="text-center">
                You are about to register for an event that is <strong>not included</strong> in your CPD subscription package.
                Should you proceed with registration, you will be liable for the invoice and that payment should be made
                before gaining access to the <strong>links & resources</strong> for this event.
            </p>
            <br>
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td>Your Subscription:</td>
                    <td>{{ ucfirst(auth()->user()->subscription('cpd')->plan->name.' '.auth()->user()->subscription('cpd')->plan->interval.'ly') }}</td>
                    <td><span class="label label-success">{{ ucfirst(auth()->user()->subscription('cpd')->status) }}</span></td>
                </tr>
                </tbody>
            </table>
            <p class="text-center">
                <a href="{{ route('cpd') }}" class="btn btn-success">Tell me more</a>
            </p>
            <a class="popup-close" data-popup-close-event="popup-1" href="#">x</a>
        </div>
    </div>
</div>