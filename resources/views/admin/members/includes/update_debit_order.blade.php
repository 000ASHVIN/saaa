<div id="course_edit{{ $course->id }}" class="modal fade text-left" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
<?php
$getSubscription = $member->GetSubscription('course',$course->monthly_plan_id);

if(!$getSubscription)
{
    $getSubscription = $member->GetSubscription('course',$course->yearly_plan_id);
}

?>
            {!! Form::open(['method' => 'Post','id'=>'coupon_code_apply', 'route' => ['member.update_subscriptions', @$getSubscription->id]]) !!}
            <div class="modal-body">
                <div class="alert alert-warning">
                    <p><strong>Please Note</strong> Please be careful to Apply coupon code. It will apply only for future installment.
                    </p>
                </div>

                <div class="form-group @if ($errors->has('coupon_code')) has-error @endif">
                        {!! Form::label('Coupon Code', 'Coupon Code') !!}
                        {!! Form::input('text', 'coupon_code', null, ['class' => 'form-control ', 'v-model' => 'coupon_code']) !!}
                        @if ($errors->has('coupon_code')) <p class="help-block">{{ $errors->first('coupon_code') }}</p> @endif
                </div>
                

                
                {!! Form::submit('Apply Coupon Code', ['class' => 'btn btn-info']) !!}
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>