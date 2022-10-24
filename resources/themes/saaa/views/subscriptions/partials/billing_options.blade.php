<div class="panel panel-default">
    <div class="panel-heading">
        Payment Options
    </div>
    <div class="panel-body">

        {{-- Error Handling --}}
        <app-error-alert :form="forms.subscription"></app-error-alert>

        {{-- Credit Card --}}
        <label class="radio">
            <input type="radio" v-model="forms.subscription.paymentOption" value="cc">
            <i></i> Credit Card / Debit Card
        </label>

        {{-- Instant EFT --}}
        <label class="radio" v-if="selectedPlanIsYearly">
            <input type="radio" v-model="forms.subscription.paymentOption" v-on:click="selectEft()" value="eft">
            <i></i> Instant EFT
        </label>
        <a :href="instantlink" style="display: none;" id="instantlink" target="_blank">Redirect To URL</a>

        {{-- Debit Order --}}
        <label class="radio" v-if="selectedPlanIsMonthly">
            <input type="radio" v-model="forms.subscription.paymentOption" value="debit">
            <i></i> Debit Order
        </label>

    </div>
</div>