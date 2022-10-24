Vue.component('app-order-pay', {

    props: {
        'order': {
            'required': true
        },
        'user': {
            'required': true
        },
        staff: null,
        selectedplan: null,     
        companys: null,
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            instantlink: '',
            selectedPlan:[],
            inviteStaff: false,
            selectedStaff: [],
            isComanySetup: false,
            selectedStaffCount:0,
            isPracticePlan:false,
            is_practice_plan: false,
            errors:{
                company:[],
                invite:[],
                billing_company:[]
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
            payment_token: '',
            instant_eft_success: false,
            forms: {
                pay: $.extend(true, new AppForm({
                    busy: false,
                    paymentOption: "cc",
                    card: null,
                    terms: false,
                    staffcount: null,
                    bf: false,
                    pricing_group:[],
                }), App.forms.pay)
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
            readyForThreeDs: false
        };
    },
    // watch: {
    //     selectedStaff: function(val){
    //       this.selectedStaff = val;
    //     }
    // },

    
    ready: function() {
        this.selectedPlan = this.selectedplan;
        // this.forms.pay.pricing_group = plan.pricing_group;
        window.addEventListener("message", (event) => {
            if (typeof event.data == 'string') {
                try {
                    eval(event.data);
                    setTimeout(() => {
                        this.forms.pay.busy = false;
                        swal.close()
                    }, 2000)
                }
                catch(e){
                    this.forms.pay.busy = false;
                    swal.close()
                }
            }
        });
    },

     watch: {
        selectedStaffLength: function(val){
          if(val>0 && this.selectedPlanIsYearly){
                // setTimeout(() => {
                //     this.getEftLink();
                //     $('.app-first-field')
                //         .filter(':visible:first')
                //         .focus();
                // }, 100);
            }
        }
    },
    computed: {

         /*
         * Determine if the plans have been loaded from the API.
         */
        selectedStaffLength: function () {
            return this.selectedStaff.length;
        },
        /*
         * Get the full selected plan price with currency symbol.
         */
        selectedPlanPrice: function () { 
            if (this.selectedPlan) {
                if (this.forms.pay.bf){
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
    },

    methods: {
          toggleStaff(){
            if(this.inviteStaff){
                this.inviteStaff = false;
            }else{
                this.inviteStaff = true;
            }
        },

        // setSelectedStaff: function (id) {
        //     if(id.target.checked){
        //         this.selectedStaff.push(id.target.value);
        //     }else{
        //         this.selectedStaff.splice($.inArray(id.target.value, this.selectedStaff), 1);
        //     }
        //     this.forms.subscription.staff = this.selectedStaff;
        // },

        /*
         * Select a plan from the list. Initialize registration form.
         */
        // selectPlan: function (plan) {
        //     this.setSelectedPlan(plan);

        //     if (this.plans.length >= 2){
        //         window.scrollTo({
        //             top: 450,
        //             behavior: "smooth"
        //         });
        //     }
        //     //if(!this.is_practice_plan && plan.pricing_group>=0){
        //         setTimeout(() => {
        //             this.getEftLink();
        //             $('.app-first-field')
        //                 .filter(':visible:first')
        //                 .focus();
        //         }, 100);
        //     //}
        // },

         /*
         * Set the selected plan.
         */
        // setSelectedPlan: function (plan) {
        //     console.log('plan');
        //     this.selectedPlan = plan;
        //     this.forms.pay.plan = plan.id;
        //     this.forms.pay.pricing_group = plan.pricing_group;
        // },

        getPlanPrice: function()
        {
            if(this.selectedplan.pricing_group.length>0)
            {
                let staff = parseInt(this.selectedStaff.length)+parseInt(1);
                let plans =  _.max(this.selectedPlan.pricing_group.map(function(rec) {return rec.max_user}))
                let minuser = _.filter(this.selectedPlan.pricing_group, function (group) {
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
                        return _.filter(this.selectedPlan.pricing_group, function (group) {
                            return parseInt(group.max_user) == parseInt(plans);
                            
                        });    
                    }
                }
                return _.filter(this.selectedPlan.pricing_group, function (group) {
                    return parseInt(group.min_user) <= parseInt(staff) && parseInt(group.max_user) >= parseInt(staff);
                    
                });
            }
            return [];
        },

        setSelectedStaff: function (id) {
            order_quantity = 0;
            if(this.order.items.length > 0){
                order_quantity = parseInt(this.order.items[0].quantity)-parseInt(1);
            }

            if(id.target.checked){
                var checked_count = parseInt(this.selectedStaff.length)+parseInt(1);
                if(checked_count > order_quantity)
                {
                    swal({
                        type: 'warning',
                        title: 'Warning',
                        text: "You Can't select more then "+order_quantity+" staff member"
                    });
                    id.target.checked=false;
                    return false;
                }
                this.selectedStaff.push(id.target.value);
            }else{
                this.selectedStaff.splice($.inArray(id.target.value, this.selectedStaff), 1);
                this.forms.pay.staff = this.selectedStaff;
            }
            

            
        },

       

        popEft() {
            this.process();
            this.forms.pay.busy = true;
            this.getEftLink();
        },

        addCard() {
            this.newcard.return = `/invoices/settle/${this.order.id}?threeDs=yes`;

            this.processPayment();

            this.$http.post('/account/billing', this.newcard)
                .then(response => {
                    if(response.data.redirect === undefined || response.data.redirect === null) {
                        this.saveCard(response.data.id);
                        this.addingNewCard = false;
                        swal.close();
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

        pay() {
            this.forms.pay.errors.forget();
            this.forms.pay.busy = true;
            this.process();
            this.sendPay();
        },
        processPayment: function() {
            swal({
                type: "warning",
                title: "Payment Processing",
                text: "Please wait while we are processing your payment.Do not Refresh page or close browser"
            })
        },
        sendPay() {

            if(this.forms.pay.paymentOption == 'eft' && this.instant_eft_success == false) {
                this.forms.pay.busy = true;
                return;
            }

            var payload = {
                terms: this.forms.pay.terms,
                card: this.forms.pay.card,
                paymentOption: this.forms.pay.paymentOption,
                staff: this.selectedStaff,
            };

            this.processPayment();
            this.$http.post(`/invoice/order/settle/${this.order.id}`, payload)
                .success((response) => {
                    this.forms.pay.busy = false;
                    swal.close();
                    window.location = '/dashboard/invoice_orders';
                })
                .error((errors) => {
                    this.forms.pay.busy = false;

                    if(errors.errors.card) {
                        swal({
                            type: 'error',
                            title: 'Whoops',
                            text: errors.errors.card
                        })
                    } else {
                        swal.close();
                    }

                    this.forms.pay.errors.set(errors);
                });
        },

        cancelPayment: function () {
            this.forms.pay.busy = false;
        },

        getEftLink: function () {
            this.processPayment();

            let data = {
                order: this.order.id
            }

            this.$http.post('/instant-eft', data)
                .success((response) => {
                    this.instantlink = response.link
                    this.payment_token = response.key
                    this.processEft();
                });
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
                        this.sendPay();
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

        processEft: function () {
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
                    this.sendPay(); // Change to Relevant Pay Method
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
         /**
         * Clear User Selected Features
         */
        clearselectedStaff() {
            this.selectedStaff = [];
        },
        sendInvitation(){
         
            this.$http.post('/dashboard/company/store_invite',{first_name:this.company.invite.first_name,
                last_name:this.company.invite.last_name,
                email:this.company.invite.email,
                alternative_cell:this.company.invite.alternative_cell,
                id_number:this.company.invite.id_number,
                cell:this.company.invite.cell})
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
        process() {
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
        }
    }
})
;