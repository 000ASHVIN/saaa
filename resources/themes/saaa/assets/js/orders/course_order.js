Vue.component('course-order-template', {
    props:{
        selectedOption: '',
        courses: '',
        venues: '',
        pricings: '',
        dates: '',
        products: '',
        selectedCourseId: '',
        selectedVenueId: '',
        selectedDateId: '',
        selectedPricingId: '',
        selectedproductId: '',
        discounted_price: '',
    },
    data:function() {
        return {
            couponApplied:false
        }
    },
    computed: {
        selectedEvent: function () {
            return this.getSelectedEvent();
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
        }
    },

    methods: {
        SelectedOption: function(tag){
            this.selectedOption = tag,
                this.selectedCourseId = '',
                this.selectedVenueId = '',
                this.selectedDateId = '',
                this.selectedPricingId = ''
        },

        changeCourseselection: function () {
            this.selectedDateId = '';
            this.selectedVenueId = '';
            this.selectedExtraId = [];
            this.pricings = 0;
            this.venues = 0;
            //this.getPrice();
            this.dates = 0;
            this.selectedPricingId = 0;
            this.venues = 0;
        },

        changeVenueSelection: function () {
            this.selectedDateId = this.selectedVenue.dates[0].id;
            this.getPricings();
            this.getDates();
        },

        getEventById: function (id) {
            return _.find(this.courses, {id: id});
        },

        getSelectedEvent: function () {
            return this.getEventById(parseInt(this.selectedCourseId));
        },

        getVenueById: function (id) {
            return _.find(this.selectedEvent.venues, {id: id});
        },

        getSelectedVenue: function () {
            return this.getVenueById(this.selectedVenueId);
        },

        getPrice: function () {
           return this.courses.filter(function(ele){
                return (ele.id == this.selectedCourseId)
             });
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
        applyCouponCode: function(){
            let $cid = this.selectedCourseId; 
            var payload = {
                'type':'course',
                'event_name':this.selectedEvent.title,
                'code':this.Couponcode
            };
           
            let checkCoupon = false;
             this.$http.post('/check/'+$cid, payload)
                 .then((response) => {
                     if(response.data.status==1){
                         checkCoupon =true;
                         this.couponApplied = true;
                         swal({
                            type: 'success',
                            title: 'Coupon Code',
                            text: response.data.message
                        });
                     }else{
                         swal({
                             type: 'error',
                             title: 'Error',
                             text: response.data.message
                         });
                     }
                 });
         },
    }
});