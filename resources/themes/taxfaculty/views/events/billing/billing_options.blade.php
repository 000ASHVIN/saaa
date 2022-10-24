<div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-left">
            Payment Options
        </div> 

        <div class="pull-right">
            <span v-if="total > 0">
                <strong>Total: @{{ total | currency 'R' }}</strong>
            </span>
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="panel-body">

        {{-- <app-error-alert :form="forms.eventSignup"></app-error-alert> --}}
            
        <div>
            <div class="col-md-12">
                
                <label class="radio" style="display: block">
                    <input type="radio" v-model="forms.eventSignup.paymentOption" value="cc">
                    <i></i> Credit Card / Debit Card
                </label>

                <label class="radio" style="display: block">
                    <input type="radio" v-model="forms.eventSignup.paymentOption" value="eft">
                    <i></i> Instant EFT
                </label>

                <label class="radio" style="display: block">
                    <input type="radio" v-model="forms.eventSignup.paymentOption" value="po">
                    <i></i> Purchase Order
                </label>

                <label class="radio" v-if="user.availableWallet > total">
                    <input type="radio" v-model="forms.eventSignup.paymentOption" value="wallet">
                    <i></i> U-Wallet
                </label>

            </div>
        </div>            
    </div>
</div>