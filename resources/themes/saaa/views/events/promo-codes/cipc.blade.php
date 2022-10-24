<div class="panel panel-default">
    <div class="panel-heading">
        Test Drive Discount
    </div>
    <div class="panel-body">
        @if(array_has(App\AppEvents\PromoCode::sessionCodes(),$promoCode->code))
            <h5 class="no-margin-bottom">Your 100% discount is applied</h5>
        @else
            <h5>Claim your discount now!</h5>
            {!! Former::vertical_open()->method('POST')->route('dashboard.cipc-update-free-event')->class('no-margin-bottom') !!}
            {!! Former::text('free_cipc_update','Your Unique Discount Code') !!}
            {!! Former::submit('Submit') !!}
            {!! Former::close() !!}
        @endif
    </div>
</div>