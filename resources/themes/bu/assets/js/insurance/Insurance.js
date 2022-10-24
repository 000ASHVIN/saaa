Vue.component('app-insurance-register-screen', {

    ready: function () {},

    data() {
        return {
            step: 1,
            user: null,
            forms: {
                basic: $.extend(true, new AppForm({
                    first_name: '',
                    last_name: '',
                    email: '',
                    terms: ''
                }), App.forms.basic),
                address: $.extend(true, new AppForm({
                    user_id: '',
                    postal: {
                        line_one: '',
                        line_two: '',
                        city: '',
                        province: '',
                        code: ''
                    },
                    business: {
                        line_one: '',
                        line_two: '',
                        city: '',
                        province: '',
                        code: ''
                    }
                }), App.forms.address),
                declaration: $.extend(true, new AppForm({
                    user_id: '',
                    legal_entity: '',
                    practice_abroad: '',
                    work_abroad: '',
                    negligence : '',
                    aware: '',
                    aware_description: '',
                    declined: '',
                    declined_reason: ''
                }), App.forms.declaration),
                assessment: $.extend(true, new AppForm({
                    user_id: '',
                    registered: '',
                    body: '',
                    options: {
                        conduct: '',
                        cpd: '',
                        engagement: '',
                        technical: '',
                        resources: '',
                        reviews: '',
                        standards: '',
                        technology: ''
                    }
                }), App.forms.assessment)                
            }
        }
    },

    methods: {
        signup() {
            this.forms.basic.busy = true;
            App.post('/insurance/user', this.forms.basic)
                .then(response => {
                    this.user = response;
                    this.forms.address.user_id = response.id;
                    this.forms.assessment.user_id = response.id;
                    this.forms.declaration.user_id = response.id;
                    this.step++;
                })
                .catch(errors => {
                    this.forms.basic.busy = false;
                });
        },

        submitAddress() {
            this.forms.address.busy = true;
            App.post('/insurance/address', this.forms.address)
                .then(response => {
                    this.step++;
                    this.forms.address.busy = false;
                })
                .catch(errors => {
                    console.log(response);
                    this.forms.address.busy = false;
                });
        },

        completeAssessment() {
            App.post('/insurance/assessment', this.forms.assessment)
                .then(response => {
                    this.step++;
                    this.forms.address.busy = false;
                })
                .catch(errors => {
                    console.log(response);
                    this.forms.address.busy = false;
                });
        },

        completeApplication() {
            App.post('/insurance/complete', this.forms.declaration)
                .then(response => {
                    this.step++;
                    this.forms.address.busy = false;
                })
                .catch(errors => {
                    console.log(response);
                    this.forms.address.busy = false;
                });
        }
    }
});
