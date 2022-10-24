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
            this.getPrice();
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
            return this.getEventById(this.selectedCourseId);
        },

        getVenueById: function (id) {
            return _.find(this.selectedEvent.venues, {id: id});
        },

        getSelectedVenue: function () {
            return this.getVenueById(this.selectedVenueId);
        },

        getPrice: function () {
           return this.courses.Specs.filter(function(ele){
                return (ele.id == selectedCourseId)
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

    }
});