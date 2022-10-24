Vue.component('app-upgrade-subscription', {

    props: {
        'plans':[],
        'monthly_plans':[],
        'yearly_plans':[]
    },

    data: function () {
        return {
            'planId':'',
            'payment_method':'',
            'selectedPlan':''
        };
    },

    ready: function() {
    },

    methods: {
        planChange: function () {
            this.selectedPlan = this.getPlanById(this.planId);
        },
        getPlanById: function(planId) {
            return _.find(this.plans, {id: planId});
        }
    }
})