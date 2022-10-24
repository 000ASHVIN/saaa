Vue.component('event-assign-to-plans', {

    props: ['events', 'plans'],

    ready: function () {
    },

    data: function () {
        return {
            plansIds: [],
            selectedEventId: this.events[0].id,
            selectedVenueId: this.events[0].venues[0].id,
            selectedDateId: this.events[0].venues[0].dates[0].id
        };
    },

    computed: {
        selectedEvent: function () {
            return this.getSelectedEvent();
        },
        selectedVenue: function () {
            return this.getSelectedVenue();
        }
    },

    methods: {
        changeEventSelection: function () {
            this.selectedVenueId = this.selectedEvent.venues[0].id;
            this.selectedDateId = this.selectedVenue.dates[0].id;
        },
        changeVenueSelection: function () {
            this.selectedDateId = this.selectedVenue.dates[0].id;
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
        }
    }
});
