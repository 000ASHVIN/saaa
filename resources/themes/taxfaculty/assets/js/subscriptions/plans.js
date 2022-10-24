import moment from 'moment';
Vue.component('app-subscriptions-screen-plan', {

    props: {
        plans: null,
        staff: null,
        user: null,
        subscriptions: null,
        profession: null,
        companys: null,
        upgrade: '',
        bank_codes: null,
        donations: {
            required: false,
            default: false
        }
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            selectedPlan: null,
            selectedFeatures: [],
            selectedStaff: [],
            selectedStaffCount:0,
            customFeatures: [],
            filterPlan: [],
            selectedRoute:"",
            Plantype:"",
            instantlink: '',
            payment_token: '',
            instant_eft_success: false,
            is_practice_plan: false,
            inviteStaff: false,
            planTypeState: false,
            isComanySetup: false,
            otp: null,
            waiting_on_otp: false,
            requested_otp: false,
            errors:{
                company:[],
                invite:[]
            },
            company:{
                detail:{
                    title:'',
                    company_vat:'',
                    plan:'',
                    plan_id:'',
                    user_id:'',
                    employees:'',
                    address_line_one:'',
                    address_line_two:'',
                    province:'',
                    city:'',
                    area_code:'',
                    country:'',
                },
                invite:{
                    first_name:"",
                    last_name:"",
                    cell:"",
                    alternative_cell:"",
                    email:"",
                    id_number:""
                }
            },
            forms: {
                subscription: $.extend(true, new AppForm({
                    busy: false,
                    bf: false,
                    debit_order_type:'',
                    plan: '',
                    pricing_group:[],
                    terms: false,
                    is_upgrade: false,
                    immediately: false,
                    paymentOption: 'cc',
                    pi_cover:false,
                    features: [],
                    staff: [],
                    card: null,
                    donations: 1,
                    debit: {
                        bank: '',
                        bank_temp: '',
                        account_number: '',
                        account_type: '',
                        branch_name: '',
                        branch_code: '',
                        billable_date: '',
                        bill_at_next_available_date: 0,
                        account_holder: '',
                        id_number: '',
                        registration_number: "",
                        type_of_account: "",
                        otp: ""
                    }
                }), App.forms.subscription)
            },
            addingNewCard: false,
            newcard: {
                number: '',
                holder: '',
                exp_month: '',
                exp_year: '',
                cvv: '',
                return: '',
                errors: []
            },
            threeDs: {
                url: '',
                connector: '',
                MD: '',
                TermUrl: '',
                PaReq: ''
            },
            readyForThreeDs: false,
            dataLayer:[]
        };
    },


    ready() {
        this.getConfig();

        var queryString = URI(document.URL).query(true);        

        window.addEventListener("message", (event) => {
            if (typeof event.data == 'string') {
                try {
                    eval(event.data);
                    setTimeout(() => {
                        this.forms.subscription.busy = false;
                        swal.close()
                    }, 2000)
                }
                catch(e){
                    this.forms.subscription.busy = false;
                    // swal.close()
                }
            }
        });
        
        this.makeactive('individual','chartered-accountant');
        // if(queryString.plan) {
        //     var plan = _.find(this.plans, (plan) => {
        //         return plan.id == queryString.plan
        //     });
        //
        //     this.setSelectedPlan(plan)
        // }
    },

    watch: {
        selectedStaffLength: function(val){
          if(val>0 && this.selectedPlanIsYearly){
                setTimeout(() => {
                    this.getEftLink();
                    $('.app-first-field')
                        .filter(':visible:first')
                        .focus();
                }, 100);
            }
        },
        paymentOption: function(val){
            let vm = this;
            if(val == 'debit' && this.forms.subscription.is_upgrade){
                swal({
                    title: "Are you Want to upgrade immediately?",
                    text: "Are you interested in changing your subscription plan? Click immediately and your debit order will proceed soon?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "immediately",
                    cancelButtonText: "Regaular",
                    closeOnConfirm: false
                },
                 function(isConfirm){ 
                 
                if (isConfirm) {
                    vm.forms.subscription.immediately = true;
                    swal.close();
                }
            });
            }
        }
    },
    computed: {
       
        pastDebitDateSelected() {
            if(! this.forms.subscription.debit.billable_date) {
                return false;
            }

            if(moment().date() >= this.forms.subscription.debit.billable_date) {
                return true;
            }
        },

        otpCorrect() {
            if(this.otp == null)
                return false;

            return this.otp == this.forms.subscription.debit.otp;
        },

        /**
         * Return the count of selected features
         */
        countSelectedFeatures() {
            return this.selectedFeatures.length;
        },

        /**
         * Return the count of maximum features that can be selected
         */
        getMaxFeatures() {
            return this.selectedPlan.max_no_of_features;
        },

        /**
         * Check if we can select more features
         */
        canSelectMoreFeatures() {
            var maxFeatures = this.selectedPlan.max_no_of_features;
            if(this.countSelectedFeatures < maxFeatures || maxFeatures==0){
                return true;
            }else{
                swal({
                    type: 'success',
                    title: 'Good job!',
                    text: "You have selected "+maxFeatures+" Topics, Please proceed or clear your selection to reselect."
                })
                return false;
            }
        },

         /*
         * Determine if the plans have been loaded from the API.
         */
        selectedStaffLength: function () {
            return this.selectedStaff.length;
        },

        paymentOption: function () {
            return this.forms.subscription.paymentOption;
        },
        /*
         * Determine if the plans have been loaded from the API.
         */
        plansAreLoaded: function () {
            return this.plans.length >= 0;
        },

        /*
         * Get all of the "default" available plans. Typically this is monthly.
         */
        defaultPlans: function () {
            if(this.plans.length >= 1){
                if (this.monthlyPlans.length > 0) {
                    return this.monthlyPlans;
                }

                if (this.yearlyPlans.length > 0) {
                    return this.yearlyPlans;
                }
            }else{
                return this.plans[1]
            }
        },


      
        /*
         * Get all of the plans that have a monthly interval.
         */
        monthlyPlans: function () {
            return _.filter(this.filterPlan, function (plan) {
                return plan.interval == 'month' && plan.inactive != 1;
            });
        },


        /*
         * Get all of the plans that have a yearly interval.
         */
        yearlyPlans: function () {
            return _.filter(this.filterPlan, function (plan) {
                return plan.interval == 'year' && plan.inactive != 1;
            });
        },


        /*
         * Determine if a free plan is currently selected during registration.
         */
        freePlanIsSelected: function () {
            return this.selectedPlan && this.selectedPlan.price <= 0;
        },


        /*
         * Determine if the plan type selector is set to "monthly".
         */
        shouldShowDefaultPlans: function () {
            return !this.planTypeState;
        },


        /*
         * Determine if the plan type selector is set to "yearly".
         */
        shouldShowYearlyPlans: function () {
            return this.planTypeState;
        },


        /*
         * Get the full selected plan price with currency symbol.
         */
        selectedPlanPrice: function () {
            if (this.selectedPlan) {
                if (this.forms.subscription.bf){
                    return 'R ' + this.selectedPlan.bf_price;
                } else{
                    if(this.getPlanPrice().length > 0){
                        return 'R ' + this.getPlanPrice()[0].price;
                    }else{
                        return 'R ' + this.selectedPlan.price;
                    }
                }
                
            }
        },

        selectedPlanIsMonthly: function () {
            if (this.selectedPlan.interval == 'month') {
                return true;
            }
        },

        selectedPlanIsYearly: function () {
            if (this.selectedPlan.interval == 'year') {
                return true;
            }
        },

        debitOrderDetailsCompleted() {
            if (this.forms.subscription.debit.type_of_account === 'company'){
                return this.forms.subscription.debit.bank != '' &&
                    this.forms.subscription.debit.account_number != '' &&
                    this.forms.subscription.debit.account_type != '' &&
                    this.forms.subscription.debit.branch_code != '' &&
                    // this.forms.subscription.debit.branch_name != '' &&
                    this.forms.subscription.debit.billable_date != '' &&
                    this.forms.subscription.debit.account_holder != '' &&
                    this.forms.subscription.debit.type_of_account != '' &&
                this.forms.subscription.debit.registration_number != ''
            }else {
                return this.forms.subscription.debit.bank != '' &&
                    this.forms.subscription.debit.account_number != '' &&
                    this.forms.subscription.debit.account_type != '' &&
                    this.forms.subscription.debit.branch_code != '' &&
                    // this.forms.subscription.debit.branch_name != '' &&
                    this.forms.subscription.debit.billable_date != '' &&
                    this.forms.subscription.debit.account_holder != '' &&
                    this.forms.subscription.debit.type_of_account != '' &&
                    this.forms.subscription.debit.id_number != ''
           }
        }
    },


    methods: {

        /*
        * Get bank name and bank code on change of bank name dropdown
        */
        bankNameChanged() {
            var bank_code = this.forms.subscription.debit.bank_temp;
            var bank_name = bank_code!=''?this.bank_codes[bank_code]:'';
            this.forms.subscription.debit.branch_code = this.forms.subscription.debit.bank_temp;
            this.forms.subscription.debit.bank = bank_name;
        },

        makeactive(planSelect,route)
        {
            this.Plantype=planSelect;
            this.selectAnotherPlan();
            this.selectedRoute="/profession/"+route;
            if(planSelect == 'individual'){
                this.filterPlan=_.filter(this.plans, function (plan,planSelect){
                    return plan.package_type == 'individual' && plan.inactive != 1;
                });
            }else if(planSelect == 'business'){
                this.filterPlan=_.filter(this.plans, function (plan,planSelect){
                    return plan.package_type == 'business' && plan.inactive != 1;
                });
            }else if(planSelect == 'trainee'){
                this.filterPlan=_.filter(this.plans, function (plan,planSelect){
                    return plan.package_type == 'trainee' && plan.inactive != 1;
                });
            }
            
            
            this.monthlyPlans=_.filter(this.filterPlan, function (plan) {
                return plan.interval == 'month' && plan.inactive != 1;
            });
            this.yearlyPlans=_.filter(this.filterPlan, function (plan) {
                return plan.interval == 'year' && plan.inactive != 1;
            });
            
            this.monthlyPlans.sort(function(a, b) {
                return b.price - a.price;
            });
            this.yearlyPlans.sort(function(a, b) {
                return b.price - a.price;
            });
            
           
        },
        getConfig() {
            this.$http.get('/api/getMyCustomConfig')
                .then(response => {
                    this.customFeatures = response.data;
                })
        },
        toggleStaff(){
            if(this.inviteStaff){
                this.inviteStaff = false;
            }else{
                this.inviteStaff = true;
            }
        },
        createCompany()
        {
            this.$http.post('/dashboard/company/saveCompany',{title:this.company.detail.title,
                company_vat:this.company.detail.company_vat,
                plan:this.company.detail.plan,
                plan_id:this.company.detail.plan_id,
                user_id:this.company.detail.user_id,
                employees:this.company.detail.employees,
                address_line_one:this.company.detail.address_line_one,
                address_line_two:this.company.detail.address_line_two,
                province:this.company.detail.province,
                city:this.company.detail.city,
                area_code:this.company.detail.area_code,
                country:this.company.detail.country})
                .success((response) => {
                    this.companys.push(response);
                }).catch(errors => {
                    this.errors.company=errors.data;
                });
            console.log(this.company.detail);
        },
        popEft() {
            this.process();
            this.forms.subscription.busy = true;
            if(this.selectedStaff.length == this.selectedStaffCount){
                this.processEft();  
            }
            //this.processEft();
        },

        sendInvitation(){
         
            this.$http.post('/dashboard/company/store_invite',{first_name:this.company.invite.first_name,
                last_name:this.company.invite.last_name,
                email:this.company.invite.email,
                alternative_cell:this.company.invite.alternative_cell,
                id_number:this.company.invite.id_number,
                cell:this.company.invite.cell, check_users: true})
                .success((response) => {
                    this.staff.push(response.staff);
                    this.company.invite.first_name="";
                    this.company.invite.last_name="";
                    this.company.invite.email="";
                    this.company.invite.id_number="";
                    this.company.invite.alternative_cell="";
                    this.company.invite.cell="";
                    this.toggleStaff();
                }).catch(errors => {
                    this.errors.invite=errors.data;
                });
        },
        addCard() {
            this.newcard.return = `/`+ window.location.pathname + `?threeDs=yes&plan=${this.forms.subscription.plan}`;
            this.processPayment();

            this.$http.post('/account/billing', this.newcard)
                .then(response => {
                    if(response.data.redirect === undefined || response.data.redirect === null) {
                        this.saveCard(response.data.id);
                        this.addingNewCard = false;
                    } else {
                        this.threeDs.url = response.data.redirect.url;
                        for (var i = response.data.redirect.parameters.length - 1; i >= 0; i--) {
                            this.threeDs[response.data.redirect.parameters[i].name] = response.data.redirect.parameters[i].value;
                        }
                        this.readyForThreeDs = true;
                        swal.close();
                    }
                })
                .catch(errors => {
                    this.newcard.errors = errors.data;
                    swal.close();
                });
        },

        /**
         * Save User Card
         */
        saveCard(id) {
            this.$http.post('/account/billing/card', { id: id })
                .then(response => {
                    this.user.cards.push(response.data.card)
                    this.forms.subscription.card = response.data.card.id;
                    swal.close()
                })
                .catch(errors => {
                    if(errors.data.number) {
                        swal({
                            type: 'error',
                            title: 'Whoops',
                            text: errors.data.number
                        })
                    }
                })
        },
        processPayment: function() {
            swal({
                type: "warning",
                title: "Payment Processing",
                text: "Please wait while we are processing your payment.Do not Refresh page or close browser"
            })
        },
        /*
         * Select a plan from the list. Initialize registration form.
         */
        selectPlan: function (plan) {
            this.setSelectedPlan(plan);

            if (this.plans.length >= 2){
                window.scrollTo({
                    top: 450,
                    behavior: "smooth"
                });
            }
            //if(!this.is_practice_plan && plan.pricing_group>=0){
                setTimeout(() => {
                    this.getEftLink();
                    $('.app-first-field')
                        .filter(':visible:first')
                        .focus();
                }, 100);
            //}
        },

        changePlanPopup: function (plan) {

            let vm=this;   
            if(vm.subscriptions && parseFloat(vm.subscriptions.price)<parseFloat(plan.price)){
                vm.forms.subscription.is_upgrade = true;
                vm.selectPlan(plan);
                return false;
            }
            if(vm.subscriptions && parseFloat(vm.subscriptions.price)>=parseFloat(plan.price)){
                swal({
                    title: "Are you sure?",
                    text: "Are you interested in changing your subscription plan? Click okay and one of our sales consultants will contact you?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes",
                    closeOnConfirm: false
                },
                 function(isConfirm){ 
                 
                if (isConfirm) {
                    vm.SendMail(plan);
                }
            });
            }
           

        },
        selectEft: function()
        {
            setTimeout(() => {
                this.getEftLink();
                $('.app-first-field')
                    .filter(':visible:first')
                    .focus();
            }, 100);
        },

        /*
         * Set the selected plan.
         */
        setSelectedPlan: function (plan) {
            this.selectedPlan = plan;
            this.forms.subscription.plan = plan.id;
            this.forms.subscription.pricing_group = plan.pricing_group;
        },
        setSelectedStaff: function (id) {
            if(id.target.checked){
                this.selectedStaff.push(id.target.value);
            }else{
                this.selectedStaff.splice($.inArray(id.target.value, this.selectedStaff), 1);
            }
            this.forms.subscription.staff = this.selectedStaff;
        },

        getPlanPrice: function()
        {
            if(this.forms.subscription.pricing_group.length>0)
            {
                let staff = parseInt(this.selectedStaff.length)+parseInt(1);
                let plans =  _.max(this.forms.subscription.pricing_group.map(function(rec) {return rec.max_user}))
                let minuser = _.filter(this.forms.subscription.pricing_group, function (group) {
                    return parseInt(group.max_user) == 0;
                });
                if(minuser.length>0)
                {
                    let minuserCount = minuser[0].min_user;

                    
                    if(staff >= minuserCount) 
                    {
                        return minuser;
                    }
                }
                if(minuser.length == 0){
                    if(staff > plans) 
                    {
                        return _.filter(this.forms.subscription.pricing_group, function (group) {
                            return parseInt(group.max_user) == parseInt(plans);
                            
                        });    
                    }
                }
                return _.filter(this.forms.subscription.pricing_group, function (group) {
                    return parseInt(group.min_user) <= parseInt(staff) && parseInt(group.max_user) >= parseInt(staff);
                    
                });
            }
            return [];
        },
        sendTransactions: function(invoice){
            this.dataLayer.event='checkout.event';
            this.dataLayer.transactionId=invoice.reference;
            this.dataLayer.transactionTotal=parseFloat(parseFloat(invoice.total).toFixed(2));
            this.dataLayer.transactionTax=parseFloat(((invoice.total/100)*invoice.vat_rate).toFixed(2));
            this.dataLayer.transactionProducts=[];

            if(invoice.items.length>0){
                const self= this;
                invoice.items.forEach(function(item){
                    var prof = {
                        'sku': 'subscription-'+item.item_id,
                        'name': item.name,
                        'category': 'CPD Subscription',
                        'price': item.price,
                        'quantity': item.quantity
                    }
                    self.dataLayer.transactionProducts.push(prof)
                })
            }
           this.dataLayer = Object.assign({},this.dataLayer); 
           window.dataLayer.push(this.dataLayer);
        },
        /*
         * Clear the selected plan from the component state.
         */
        selectAnotherPlan: function () {
            this.cancelPayment();
            this.selectedPlan = null;
            this.clearSelectedFeatures();
            this.clearselectedStaff();
            this.forms.subscription.plan = '';
            this.forms.subscription.paymentOption = 'cc';
            this.forms.subscription.card = null;
            this.forms.subscription.debit = {};
        },

        /**
         * Clear User Selected Features
         */
        clearSelectedFeatures() {
            this.selectedFeatures = [];
        },
        /**
         * Clear User Selected Features
         */
        clearselectedStaff() {
            this.selectedStaff = [];
        },


        /*
         * Initialize the registration process.
         */
        register: function () {
            
            
            if(this.selectedPlan.is_practice)
            {   if(this.selectedPlan.pricing_group.length>0)
                {
                    let minUser =  _.min(this.selectedPlan.pricing_group.map(function(rec) {return rec.min_user}));
                    let staff = parseInt(this.selectedStaff.length)+parseInt(1);
                    if(staff < minUser)
                    {
                        swal({
                            type: 'warning',
                            title: "Select more Staff Members",
                            text: "You have only selected (" + staff + ") staff Members You need to add Minimum (" + minUser + ") staff Members for your subscription package."
                        });
                        return;
                    }
                }
            }
            if(this.selectedPlan.is_custom) {
                if(this.selectedFeatures.length < this.selectedPlan.max_no_of_features) {
                    swal({
                        type: 'warning',
                        title: "Select more topics",
                        text: "You have only selected (" + this.selectedFeatures.length + "/" + this.selectedPlan.max_no_of_features + ") required Topics for your subscription package."
                    });
                    return;
                }
            }

            this.forms.subscription.errors.forget();
            this.forms.subscription.staff = this.selectedStaff;
            this.forms.subscription.busy = true;
            //this.process();
            this.sendRegistration();

        },

        getEftLink: function () {
            let Staffs = this.selectedStaff;
            this.$http.post('/instant-eft', { staffs:this.forms.subscription.staff, plan: this.selectedPlan.id, bf: this.forms.subscription.bf, donation_amount:this.getDonations() })
                .success((response) => {
                    this.selectedStaffCount = this.forms.subscription.staff.length;
                    this.instantlink = response.link
                    this.payment_token = response.key
                });
        },


        SendMail: function (plan) {
            swal("Thanks! Our sales consultants will contact you");
            this.$http.post('/dashboard/contact_email',{id:plan.id})
                .success((response) => {
                    
                });
        },

        processEft: function () {
            if(this.selectedPlan.is_practice)
            {   if(this.selectedPlan.pricing_group.length>0)
                {
                    let minUser =  _.min(this.selectedPlan.pricing_group.map(function(rec) {return rec.min_user}));
                    let staff = parseInt(this.selectedStaff.length)+parseInt(1);
                    if(staff < minUser)
                    {
                        swal({
                            type: 'warning',
                            title: "Select more Staff Members",
                            text: "You have only selected (" + staff + ") staff Members You need to add Minimum (" + minUser + ") staff Members for your subscription package."
                        });
                        this.forms.subscription.busy = false;
                        return;
                    }
                }
            }
            var paymentKey = this.payment_token;
            var paymentType = 'eft';

            eftSec.checkout.settings.serviceUrl = '{protocol}://eft.ppay.io/rpp-transaction/create-from-key';
            eftSec.checkout.settings.checkoutRedirect = false;
            eftSec.checkout.settings.onComplete = (data) => {
                eftSec.checkout.hideFrame();
                this.process();
                console.log(data) 
                if(data.success) {
                    this.instant_eft_success = true;
                    this.sendRegistration();
                } else {
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: "Your Payment has failed, please try again"
                    })
                }

            };
            eftSec.checkout.init({
                paymentKey: paymentKey,
                paymentType : paymentType,
                onLoad: function() {
                    swal.close();
                }
            });
        },

        cancelPayment: function () {
            this.forms.subscription.busy = false;
        },

        /*
         * After obtaining the 3DS Information, send the registration to App.
         */
        sendRegistration: function () {

            if(this.forms.subscription.paymentOption == 'eft' && this.instant_eft_success == false) {
                this.startListener();
                this.forms.subscription.busy = true;
                swal({
                    type: 'warning',
                    title: 'Waiting on Payment',
                    text: "We're waiting for payment confirmation, please do not close this page."
                })
                return;
            }

            if(this.forms.subscription.paymentOption == 'cc' && ! this.forms.subscription.card) {
                swal({
                    type: 'warning',
                    title: 'Error',
                    text: "Unable to register without credit card, please select credit card"
                })
                this.forms.subscription.busy = false;
                return;
            }

            if(this.forms.subscription.paymentOption == 'debit' && ! this.debitOrderDetailsCompleted) {
                swal({
                    type: 'warning',
                    title: 'Error',
                    text: "Debit order details not completed"
                })
                this.forms.subscription.busy = false;
                return;
            }

            if(this.forms.subscription.paymentOption == 'debit' && ! this.requested_otp) {
                let data = {
                    account_number: this.forms.subscription.debit.account_number,
                    branch_code: this.forms.subscription.debit.branch_code
                }

                let valid = false;

                this.$http.post('/peach/validate', data)
                    .then((response) => {
                        if(response.data.Result != 'Valid') {
                            swal({
                                type: 'error',
                                title: 'Invalid Account Details',
                                text: "Ensure that you have filled out your information correctly and try again."
                            })
                            return;
                        } else {
                            valid = true;
                            return this.requestOTP();
                        }
                    })
                    .catch((errors) => {
                        console.log(errors)
                    })

                    if(! valid) {
                        this.forms.subscription.busy = false;
                        return;
                    }
            }

            if(this.forms.subscription.paymentOption == 'debit' && ! this.otpCorrect) {
                swal({
                    type: 'error',
                    title: 'Invalid OTP',
                    text: "The OTP number you have entered is invalid, please try again."
                })
                this.forms.subscription.busy = false;
                return;
            }

            if (this.selectedFeatures.length > 0 && this.selectedPlan.is_custom) {
                this.selectedFeatures.push(this.customFeatures);
                this.forms.subscription.features = this.selectedFeatures;
            }
            if (this.selectedStaff.length > 0) {
                this.forms.subscription.staff = this.selectedStaff;
            }
            this.processPayment();

            var subscription_data = this.forms.subscription;
            subscription_data.donations = this.getDonations();

            this.$http.post('/subscriptions', subscription_data)
                .success((response) => {
                    this.forms.subscription.busy = false;
                    swal.close();                    
                    var redirectUrl = '/dashboard/general';

                    if(this.forms.subscription.pi_cover) {
                        redirectUrl = '/insurance'
                    }
                    if(response.invoice !== undefined){
                        this.sendTransactions(response.invoice)
                    }
                    // window.location = redirectUrl;
                })
                .error((errors) => {
                    swal.close();                    
                    this.selectedFeatures.$remove(this.customFeatures);
                    this.forms.subscription.busy = false;
                    this.forms.subscription.errors.set(errors);
                });
        },

        requestOTP: function () {
            this.$http.post('/peach/otp')
                .then((response) => {
                    this.otp = response.data.otp
                    this.requested_otp = true;
                    this.waiting_on_otp = true;
                    swal.close();
                })
        },

        resendOtp: function () {
            this.$http.post('/peach/otp')
                .then((response) => {
                    this.otp = response.data.otp
                    swal({
                        type: 'success',
                        title: 'OTP Sent!',
                        text: "The OTP has been sent!."
                    })
                })
        },

        startListener: function () {
            var pusher = new Pusher('5e9e56a5a0ebaf5484b0', {
              cluster: 'mt1'
            });

            var channel = pusher.subscribe('instantefts');

            channel.bind("App\\Events\\InstantEftNotificationReceived", (data) => {
              if(this.payment_token == data.payment_key) {
                if(data.success === true) {
                    this.process();
                    this.instant_eft_success = true;
                    this.sendRegistration();
                } else {
                    this.cancelPayment();
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: "Your Payment has failed, please try again"
                    })
                }
              }
            });

        },

        /*
         * Get the proper column width based on the number of plans.
         */
        getPlanColumnWidth: function (count) {
            switch (count) {
                case 1:
                    return 'col-md-6 col-md-offset-3';
                case 2:
                    return 'col-md-6';
                case 3:
                    return 'col-md-4';
                case 4:
                    return 'col-md-3';
                case 5:
                    return 'col-md-5th';
                case 7:
                    return 'col-md-4';
                case 8:
                    return 'col-md-3';
                default:
                    return 'col-md-4';
            }
        },

        process: function () {
            swal({
                title: "",
                text: '<i id="busy" class="fa fa-spinner fa-pulse" style="font-size: 8em; color: #ffffff;"></i>',
                html: true,
                allowEscapeKey: false,
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
                closeOnCancel: false,
                customClass: 'no-bg'
            });
        },
        updateDebitDate: function()
        {
            $('#debit_options').modal('show');
        },
        selectDebitOrder: function($event)
        {
            if(this.forms.subscription.debit_order_type == 'next day'){
                this.forms.subscription.debit.bill_at_next_available_date = 1;
            }else if(this.forms.subscription.debit_order_type == 'wait'){
                
            }else if(this.forms.subscription.debit_order_type == 'other'){
                
            }
            $('#debit_options').modal('hide');           
        },

        /*
         * Calculate the donation
         */
        getDonations: function() {

            var total = 0;
            if(this.forms.subscription.donations && this.selectedPlanIsYearly) {
                total = this.donations;
            }
            return total;
        },

        buynow(plan) {
            $.cookie('plan',plan.id);
            $.cookie('pathName',window.location.pathname);
            window.location = 'auth/login';
        }
        
    }
});
