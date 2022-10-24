<div class="row">
    <a class="btn" data-popup-open-event="popup-1" href="#" style="display: none"></a>
    <div class="popup" data-popup-event="popup-1" style="z-index: 9999">
        <div class="popup-inner">
            <div class="heading-title heading-dotted text-center">
                <h3><span>Oops!</span></h3>
            </div>
            <p class="text-center">
                You are about to pay for an event that you can get for free.
            </p>
            <p class="text-center">
                Click on the tell me more button below and become a CPD monthly subscriber and get 4 events for the price of 1.
            </p>
            <hr>
            <p class="text-center">
                <a href="{{ route('subscriptions.index') }}" class="btn btn-success">Tell me more</a>
            </p>
            <a class="popup-close" data-popup-close-event="popup-1" href="#">x</a>
        </div>
    </div>
</div>