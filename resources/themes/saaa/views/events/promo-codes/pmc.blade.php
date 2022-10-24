<div class="panel panel-default">
    <div class="panel-heading">
        PMC for Attorneys 50%
    </div>
    <div class="panel-body">
        @if(array_has(App\AppEvents\PromoCode::sessionCodes(),$promoCode->code))
            <h5 class="no-margin-bottom">Your 50% discount is applied</h5>
        @else
            <h5>Claim your discount now!</h5>
            {!! Former::vertical_open()->method('POST')->route('dashboard.pmc-discount-attorneys')->class('no-margin-bottom') !!}
            {!! Former::text('discount_code','Your Unique Discount Code') !!}
            {!! Former::submit('Submit') !!}
            {!! Former::close() !!}
        @endif
    </div>
</div>