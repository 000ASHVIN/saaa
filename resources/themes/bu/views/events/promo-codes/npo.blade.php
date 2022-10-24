<div class="panel panel-default">
    <div class="panel-heading">
        NPO Discount
    </div>
    <div class="panel-body">
        @if(array_has(App\AppEvents\PromoCode::sessionCodes(),$promoCode->code))
            <h5 class="no-margin-bottom">Your 15% discount is applied</h5>
        @else
            <h5>NPOs qualify for a 15% discount</h5>
            {!! Former::vertical_open()->method('POST')->route('dashboard.update-npo-registration-number')->class('no-margin-bottom') !!}
            {!! Former::text('npo_registration_number','NPO Registration number:') !!}
            {!! Former::submit('Submit') !!}
            {!! Former::close() !!}
        @endif
    </div>
</div>