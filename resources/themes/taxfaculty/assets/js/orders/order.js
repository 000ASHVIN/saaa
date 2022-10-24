Vue.component('order-template', {
    props:{
        selectedOption: '',
        events: '',
        venues: '',
        pricings: '',
        dates: '',
        products: '',
        selectedEventId: '',
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
                this.selectedEventId = '',
                this.selectedVenueId = '',
                this.selectedDateId = '',
                this.selectedPricingId = ''
        },

        changeEventSelection: function () {
            this.selectedDateId = '';
            this.selectedVenueId = '';
            this.selectedExtraId = [];
            this.pricings = 0;
            this.venues = 0;
            this.getVenues();
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
            return _.find(this.events, {id: id});
        },

        getSelectedEvent: function () {
            return this.getEventById(this.selectedEventId);
        },

        getVenueById: function (id) {
            return _.find(this.selectedEvent.venues, {id: id});
        },

        getSelectedVenue: function () {
            return this.getVenueById(this.selectedVenueId);
        },

        getVenues: function () {
            this.$http.get('find/' + this.selectedEventId + '/venues')
                .then(response => {
                    this.venues = response.data.venues
                    this.selectedVenueId = this.venues[0].id;
                    this.getPricings();
                    this.getDates();
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

        getProductById: function (id) {
            return _.find(this.products, {id: id});
        },

        getSelectedProduct: function () {
            return this.getProductById(this.selectedproductId);
        },

        discount: function (amount, discount, discount_type) {
            if (discount_type == 'percentage')
                return parseFloat(amount * (discount / 100));
            else
                return discount;
        },
    }
});