<div v-if="forms.checkout.paymentOption && total >= 0">
    @include('store.checkout.billing.po')
    @include('store.checkout.billing.eft')
    @include('store.checkout.billing.wallet')
    @include('store.checkout.billing.credit_card')
</div>