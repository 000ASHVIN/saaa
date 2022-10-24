Vue.component('plan-order-template', {
    props:{
        selectedOption: '',
        plans: '',
        venues: '',
        pricings: '',
        staff: null,
        companys: null,
        dates: '',
        products: '',
        selectedCourseId: '',
        selectedVenueId: '',
        selectedDateId: '',
        selectedPricingId: '',
        selectedproductId: '',
        discounted_price: '',
    },
    data: function () {
        return {
            selectedPlan:[],
            inviteStaff: false,
            selectedStaff: [],
            isComanySetup: false,
            selectedStaffCount:0,
            isPracticePlan:false,
            planType:'month',
            plansList:[],
            filterPlan:[],
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
        }   
    },
    ready() {
        this.setPlanType();
    },
    watch: {
        staff_count: function(val){
          
        }
    },
    computed: {
        selectedEvent: function () {
            return this.getSelectedEvent();
        },
        filterPlan: function () {
            return this.setPlanType();
        },
        selectedVenue: function () {
            return this.getSelectedVenue();
        },

        selectedPricing: function() {
            return this.getSelectedPricing()
        },

        selectedProduct: function(){
            return this.getSelectedProduct();
        },
        
        getDiscountedPrice: function () {
            this.discounted_price = this.discount(this.selectedProduct.price, this.selectedProduct.listings[0].discount, this.selectedProduct.listings[0].discount_type)
        },
          /*
         * Get the full selected plan price with currency symbol.
         */
         selectedPlanPrice: function () {
            if (this.selectedPlan) {
                
                    if (typeof this.getPlanPrice() !== 'undefined' && this.getPlanPrice().length>0){
                        return 'R ' + this.getPlanPrice()[0].price;
                    }else{
                        return 'R ' + this.selectedPlan.price;
                    }
              
            }
        },
    },
    
    methods: {
        SelectedOption: function(tag){
            this.selectedOption = tag,
                this.selectedCourseId = '',
                this.selectedVenueId = '',
                this.selectedDateId = '',
                this.selectedPricingId = ''
        },
        getPlanPrice: function()
        {   
            if(this.selectedPlan.pricing_group.length>0)
            {
                let staff_count = this.staff_count;
                let plans =  _.max(this.selectedPlan.pricing_group.map(function(rec) {return rec.max_user}))
                let minuser = _.filter(this.selectedPlan.pricing_group, function (group) {
                    return parseInt(group.max_user) == 0;
                });
                if (typeof minuser !== 'undefined'){

                    if(minuser.length>0)
                    {
                        let minuserCount = minuser[0].min_user;
                        let maxuserCount = minuser[0].max_user;

                        
                        if(staff_count >= minuserCount) 
                        {
                            return minuser;
                        }
                    }
                }
                if (typeof plans !== 'undefined'){ 
                    if(staff_count > plans) 
                    {
                        return _.filter(this.selectedPlan.pricing_group, function (group) {
                            return parseInt(group.max_user) == parseInt(plans);
                            
                        });    
                    }
                }
                return _.filter(this.selectedPlan.pricing_group, function (group) {
                    return parseInt(group.min_user) <= parseInt(staff_count) && parseInt(group.max_user) >= parseInt(staff_count);
                    
                });
            }
            return [];
        },
        changeplanselection: function () {
            this.selectedDateId = '';
            this.selectedVenueId = '';
            this.selectedExtraId = [];
            this.pricings = 0;
            this.venues = 0;
            this.selectedPlan = this.getPrice()[0];
            this.checkisPracticePlan();
            this.dates = 0;
            this.selectedPricingId = 0;
            this.venues = 0;
        },
        toggleStaff(){
            if(this.inviteStaff){
                this.inviteStaff = false;
            }else{
                this.inviteStaff = true;
            }
        },
        changeVenueSelection: function () {
            this.selectedDateId = this.selectedVenue.dates[0].id;
            this.getPricings();
            this.getDates();
        },

        getEventById: function (id) {
            return _.find(this.plans, {id: id});
        },

        getSelectedEvent: function () {
            return this.getEventById(this.selectedCourseId);
        },

        getVenueById: function (id) {
            return _.find(this.selectedEvent.venues, {id: id});
        },

        getSelectedVenue: function () {
            return this.getVenueById(this.selectedVenueId);
        },

        getPrice: function () {
            let SelcetdId=this.selectedCourseId;
            return this.plans.filter(function(ele){
                return (ele.id ==SelcetdId)
             });
        },
        checkisPracticePlan(){
            if(this.selectedPlan.length > 0)
            {
                if(this.selectedPlan[0]['is_practice'] == 1)
                {
                    this.isPracticePlan = true;
                }else{
                    this.isPracticePlan = false;
                }
            }
        },
        getPricings: function () {
            this.$http.get('find/' + this.selectedVenueId + '/pricings')
                .then(response => {
                    this.pricings = response.data.pricings
                    this.selectedPricingId = this.pricings[0].id;
                });
        },

        getDates: function () {
            this.$http.get('find/' + this.selectedVenueId + '/dates')
                .then(response => {
                    this.dates = response.data.dates
                    this.selectedDateId = this.dates[0].id
                });
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
        setSelectedStaff: function (id) {
            if(id.target.checked){
                this.selectedStaff.push(id.target.value);
            }else{
                this.selectedStaff.splice($.inArray(id.target.value, this.selectedStaff), 1);
            }
            this.selectedStaff = this.selectedStaff;
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
        setPlanType()
        {
            let planType = this.planType;
            console.log(planType);
            let plansLists =  _.filter(this.plans, function (plan) {
                return plan.interval == planType   && plan.inactive != 1;
            });
            return plansLists;
        }
    }
});