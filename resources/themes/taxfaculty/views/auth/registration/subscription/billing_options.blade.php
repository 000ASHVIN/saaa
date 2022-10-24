<div class="panel panel-default">
    <div class="panel-heading">Billing Options</div>

    <div class="panel-body">
        <app-error-alert :form="forms.registration.paymentOption"></app-error-alert>
        <p>Please select your payment option below:</p>

        <label class="radio">
            <input type="radio" v-model="forms.registration.paymentOption" value="cc">
            <i></i> Credit Card
        </label>

        <label class="radio" v-if="selectedPlanIsMonthly">
            <input type="radio" v-model="forms.registration.paymentOption" value="debit">
            <i></i> Debit Order
        </label>

        <label class="radio" v-if="selectedPlanIsYearly">
            <input type="radio" v-model="forms.registration.paymentOption" value="eft">
            <i></i> Electronic funds transfer (EFT)
        </label>
    </div>
</div>
