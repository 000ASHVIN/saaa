@extends('app')

@section('title', 'Upgrade Subscription')

@section('content')
    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
        <s :plans="{{ $plans }}" :upgrade="true" inline-template>
            <div id="app-register-screen" class="container app-screen">

                <!-- Subscription Plan Selector -->
                <div class="col-md-12" v-if="plans.length > 1 && plansAreLoaded && ! forms.subscription.plan">
                    @include('subscriptions.partials.plans.selector')
                </div>

                <!-- User Information -->
                <div class="col-md-8 col-md-offset-2" v-if="selectedPlan">

                    <!-- The Selected Plan -->
                    <div class="row" v-if="plans.length > 1">
                        @include('subscriptions.partials.plans.selected')
                    </div>

                    {{-- Plan Features if Selected Plan is Custom --}}
                    <div class="row" v-if="selectedPlan.is_custom">
                        @include('subscriptions.partials.plans.features')
                    </div>

                    <div class="display-table">
    <div class="display-table-cell vertical-align-middle">
        <div class="panel panel-default">
            <div class="panel-heading panel-heading-transparent">
                <h2 class="panel-title">Register</h2>
            </div>
            <div class="panel-body">

                <app-error-alert :form="forms.registration"></app-error-alert>
                    <div class="form-group" :class="{'has-error': forms.registration.errors.has('terms')}">
                        <div class="col-sm-6 col-sm-offset-4">
                            <span class="help-block" v-show="forms.registration.errors.has('terms')">
                                <strong>@{{ forms.registration.errors.get('terms') }}</strong>
                            </span>
                            <label class="checkbox nomargin">
                                <input class="checked-agree" type="checkbox" v-model="forms.registration.terms">
                                    <i></i>
                                    I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                            </label>
                        </div>
                    </div>

                    <br><br>

                    <div class="form-group">
                        <div class="col-sm-6 col-sm-offset-4">
                            <button type="submit" class="btn btn-primary" @click.prevent="register" :disabled="forms.registration.busy">
                                <span v-if="forms.registration.busy">
                                    <i class="fa fa-btn fa-spinner fa-spin"></i> Upgrading Subscription
                                </span>
                                <span v-else>
                                    <i class="fa fa-btn fa-check-circle"></i> Upgrade Subscription
                                </span>
                            </button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
                </div>
            </div>
        </s>
    </section>
@endsection