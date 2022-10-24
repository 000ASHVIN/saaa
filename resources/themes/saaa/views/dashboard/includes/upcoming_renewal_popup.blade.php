<div class="modal fade" id="upcoming_renewal_popup" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Renew CPD Subscription Package</h4>
            </div>

            <div class="modal-body">
                <p>Your CPD Subscription Package is about to expire, renew your package now to continue receiving your benefits</p>
                <div class="text-center">
                    <a href="{{ route('profession.plans_and_pricing') }}" class="btn btn-default" style="margin-top:10px;">Change to monthly plan</a>
                    <?php
                        $user = auth()->user();
                        $subscription = $user->subscription('cpd');
                        // $popup_link = route('dashboard.self-renew-subscription');
                        // if($subscription->suspended()) {
                        //     $popup_link = route('invoices.settle', [$subscription->invoice_id]);
                        // }

                        $slug = $subscription->plan->slug;
                        if($slug) {
                            $popup_link = route('subscriptions.2019.one_day_only.package', [$slug]).'?renew=1';
                        }
                        else {
                            $popup_link = route('profession.plans_and_pricing');
                        }
                    ?>
                    <a href="{{ $popup_link }}" id="btn_renew_subscription" class="btn btn-success" style="background-color: #173175!important; border-color: #173175; margin-top:10px;">Renew Subscription</a>
                </div>
            </div>
        </div>
    </div>
</div>
