@if(count($course->promoCodes) > 0)


    @foreach($course->promoCodes->take(1) as $promoCode)
        <div class="panel panel-default"  v-if="option">

            <div class="panel-heading">
                Claim Your Discount
            </div>

            <div class="panel-body">
                @if(array_flatten(App\AppEvents\PromoCode::sessionCodes(), $promoCode->code ))
                    <h5 class="no-margin-bottom">Your discount has been applied</h5>
                @else
                    <h5 v-if="couponApplied" class="no-margin-bottom">Your discount has been applied</h5>
                    <div v-else>
                    <h5>Claim your discount now!</h5>
                    {!! Form::open(['method' => 'post','id'=>'Coupon_code_apply', 'route' => ['check_coupon', $course->id]]) !!}
                    {!! Former::text('code','Your Unique Discount Code') !!}
                    <input name="type" type="hidden" value="course">
                    <input name="course_id" type="hidden" value="{{ $course->id }}">
                    <input name="event_name" type="hidden" value="{{ $course->title }}">
                    <button type="button"  @click.prevent="applyCouponCode"  class="btn btn-primary">
                        <i class="fa fa-lock"></i> Apply Coupon
                    </button>
                    {!! Form::close() !!}
                    </div>
                @endif
            </div>
        </div>
    @endforeach

@endif