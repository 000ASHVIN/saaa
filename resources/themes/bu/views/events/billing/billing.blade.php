<div v-if="forms.eventSignup.paymentOption && total > 0">
    @include('events.billing.po')
    @include('events.billing.eft')
    @include('events.billing.wallet')
    @include('events.billing.credit_card')
</div>