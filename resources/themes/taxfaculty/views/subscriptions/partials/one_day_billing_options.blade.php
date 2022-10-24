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
            <input type="radio" v-model="forms.subscription.paymentOption" value="eft" v-on:click="selectEft()">
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

<div class="row app-plan-selector-row"   >
    <div class="col-md-12 text-center">
		<span class="app-plan-selector-interval">
			Invoice For Company &nbsp;
		</span>

        <input type="checkbox"
               id="plan-type-toggle"
               class="app-toggle app-toggle-round-flat"
               v-model="isCompanyInvoice">

        <label for="plan-type-toggle"></label>

    </div>
</div>
<div v-if="isCompanyInvoice">
<div class="panel panel-default" v-if="companys.length > 0">
    <div class="panel-heading">Select Company for Invoice</div>
    <div class="panel-body">

        <div class="col-md-12 " v-for="company in companys">
            <div> <label class="checkbox pt-20" for="check@{{{ company.id }}}@{{{company.user_id}}}">
                <input type="checkbox" value="@{{{ company.id }}}" id="check@{{{ company.id }}}@{{{company.user_id}}}"  @change.prevent="setCompany($event)">
                <i></i> @{{{ company.title }}} </div>
                </label>
            
        </div>
      
        <div class="clearfix"></div>
     
    </div> 
</div>
</div>