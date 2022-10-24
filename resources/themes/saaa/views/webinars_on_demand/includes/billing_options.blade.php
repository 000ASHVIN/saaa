<div class="panel panel-default">
    <div class="panel-heading">
        Payment Options
    </div>
    <div class="panel-body">

        {{-- Error Handling --}}
        <app-error-alert :form="forms.webinarCheckout"></app-error-alert>

        {{-- Credit Card --}}
        <label class="radio">
            <input type="radio" v-model="forms.webinarCheckout.paymentOption" value="cc">
            <i></i> Credit Card / Debit Card
        </label>

        {{-- Instant EFT --}}
        <label class="radio" >
            <input type="radio" v-model="forms.webinarCheckout.paymentOption" value="eft">
            <i></i> Instant EFT
        </label>
    </div>
</div>