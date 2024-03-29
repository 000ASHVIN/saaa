<div class="panel panel-default" v-if="forms.pay.paymentOption == 'wallet' && user.availableWallet != 0">

    <div class="panel-heading">
        <div class="pull-left">
            <span v-if="user.availableWallet >= invoice.balance">Full Payment</span>
            <span v-else="user.availableWallet <= invoice.balance">Partial Payment</span>
        </div>

        <div class="pull-right">
            Available Funds: <strong>@{{ user.availableWallet | currency 'R' }}</strong>
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="panel-body">
        <app-error-alert :form="forms.pay"></app-error-alert>
        <form class="form-horizontal" role="form" style="margin-bottom: 0px">
            <div class="form-group" style="margin-bottom: 0px">
                <div class="col-sm-12">
                    <p>
                    <div class="alert alert-success" v-if="user.availableWallet >= invoice.balance">
                        Note that if you select this payment option the funds will be deducted from your wallet. All transactions for your wallet can be found under the My Wallet tab from your profile.
                    </div>
                    <div class="alert alert-warning" v-else="user.availableWallet <= invoice.balance">
                        You only have a partial of the amount required to continue this purchase, You can use this option to allocate part of the funds to this invoice but you would need to make another payment from your profile for this invoice..
                    </div>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>