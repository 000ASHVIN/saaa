<div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-left">
            Billing Options
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="panel-body">

        {{-- <app-error-alert :form="forms.checkout"></app-error-alert> --}}
            
        <div>
            {{--<label class="col-md-4 control-label">Select one of the following:</label>--}}
            <div class="col-md-12">
                
                <label class="radio" style="display: block">
                    <input type="radio" v-model="forms.checkout.paymentOption" value="cc">
                    <i></i> Credit Card / Debit Card
                </label>

                <label class="radio" style="display: block">
                    <input type="radio" v-model="forms.checkout.paymentOption" value="eft">
                    <i></i> Instant EFT
                </label>

                <label class="radio" style="display: block">
                    <input type="radio" v-model="forms.checkout.paymentOption" value="po">
                    <i></i> Purchase Order
                </label>

                <label class="radio" v-if="user.availableWallet > total">
                    <input type="radio" v-model="forms.checkout.paymentOption" value="wallet">
                    <i></i> U-Wallet
                </label>

            </div>
        </div>            
    </div>
</div>