<div v-if="forms.subscription.paymentOption && forms.subscription.amount > 0">
    @include('pages.billing.eft')
    @include('pages.billing.credit_card')
</div> 