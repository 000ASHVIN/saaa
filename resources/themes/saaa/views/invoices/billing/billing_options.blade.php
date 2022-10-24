<div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-left">
            Payment Methods
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="panel-body">

        <div>
            <label class="col-md-4 control-label">Select one of the following:</label>
            <div class="col-md-6">

                <label class="radio">
                    <input type="radio" v-model="forms.pay.paymentOption" value="cc">
                    <i></i> Credit Card
                </label>

                <label class="radio">
                    <input type="radio" v-model="forms.pay.paymentOption" value="eft">
                    <i></i> Instant EFT
                </label>

                <label class="radio" v-if="user.availableWallet != 0">
                    <input type="radio" v-model="forms.pay.paymentOption" value="wallet">
                    <i></i> U-Wallet
                </label>

            </div>
        </div>
    </div>
</div>