Vue.component('import-members-register-for-event', {

    props: {
        events: null,
        user_id: Number
    },

    ready: function () {
    },

    data: function () {
        return {
            venues: '',
            pricings: '',
            dates: '',
            selectedEventId: '',
            selectedVenueId: '',
            selectedDateId: '',
            selectedPricingId: '',
            registerEvents: [
                {
                    id: 1,
                    venues: '',
                    pricings: '',
                    dates: '',
                    extras: '',
                    dietary: '',
                    selectedEventId: '',
                    selectedVenueId: '',
                    selectedDateId: '',
                    selectedPricingId: '',
                    venueType: ''
                }
            ],
            index: 1,
            currentId: 0,
            generate_invoice: false,
            busy: false
        };
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

        currentIndex: function() {
            return this.registerEvents.findIndex((obj => obj.id == this.currentId));
        }
    },

    methods: {
        changeEventSelection: function (id) {
            this.currentId = id;
            if(this.registerEvents[this.currentIndex].selectedEventId) {
                this.selectedDateId = '';
                this.selectedVenueId = '';
                this.selectedExtraId = [];
                this.pricings = 0;
                this.venues = 0;
                this.getVenues();
                this.dates = 0;
                this.selectedPricingId = 0;
                this.venues = 0;
            }
        },

        changeVenueSelection: function (id) {
            this.currentId = id;
            this.registerEvents[this.currentIndex].selectedDateId = this.selectedVenue.dates[0].id;
            this.registerEvents[this.currentIndex].venueType = this.selectedVenue.type;
            this.getPricings();
            this.getDates();
        },

        addEvent: function() {
            this.index = this.index + 1;
            const newEvent = {
                id: this.index,
                venues: '',
                pricings: '',
                dates: '',
                extras: '',
                dietary: '',
                selectedEventId: '',
                selectedVenueId: '',
                selectedDateId: '',
                selectedPricingId: '',
                venueType: ''
            };
            this.registerEvents = [...this.registerEvents, newEvent];
        },

        registerData: function() {
            const data = this.registerEvents.filter((registerEvent) => parseInt(registerEvent.selectedEventId) > 0);
            this.busy = true;
            this.$http.post('/admin/member/'+this.user_id+'/events/register', {
                eventsObject: data,
                generate_invoice: this.generate_invoice
            }).then(function (response) {
                this.busy = false;
                window.location.reload();
            });
        },

        getEventById: function (id) {
            return _.find(this.events, {id: id});
        },

        getSelectedEvent: function () {
            return this.getEventById(this.registerEvents[this.currentIndex].selectedEventId);
        },

        getVenueById: function (id) {
            return _.find(this.selectedEvent.venues, {id: id});
        },

        getSelectedVenue: function () {
            return this.getVenueById(this.registerEvents[this.currentIndex].selectedVenueId);
        },

        getVenues: function () {
            this.$http.get('find/' + this.registerEvents[this.currentIndex].selectedEventId + '/venues')
                .then(response => {
                    this.registerEvents[this.currentIndex].venues = response.data.venues
                    this.registerEvents[this.currentIndex].selectedVenueId = response.data.venues[0].id;
                    this.registerEvents[this.currentIndex].venueType = response.data.venues[0].type;
                    this.getPricings();
                    this.getDates();
                });        
        },

        getPricings: function () {
            this.$http.get('find/' + this.registerEvents[this.currentIndex].selectedVenueId + '/pricings')
                .then(response => {
                   this.registerEvents[this.currentIndex].pricings = response.data.pricings
                    this.registerEvents[this.currentIndex].selectedPricingId = response.data.pricings[0].id;
                });
        },

        getDates: function () {
            this.$http.get('find/' + this.registerEvents[this.currentIndex].selectedVenueId + '/dates')
                .then(response => {
                    this.registerEvents[this.currentIndex].dates = response.data.dates;
                        this.registerEvents[this.currentIndex].selectedDateId = response.data.dates[0].id;
                        this.registerEvents[this.currentIndex].extras = this.selectedEvent.extras;
                });
        }
    }
});
