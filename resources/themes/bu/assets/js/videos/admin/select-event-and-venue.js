Vue.component('select-event-and-venue', {

    props: ['events'],

    ready: function () {
    },

    data: function () {
        return {
            selectedEventId: this.events[0].id,
            selectedVenueId: this.events[0].venues[0].id
        };
    },

    computed: {
        selectedEvent: function () {
            return this.getSelectedEvent();
        }
    },

    methods: {
        changeEventSelection: function () {
            this.selectedVenueId = this.selectedEvent.venues[0].id;
        },
        getEventById: function (id) {
            return _.find(this.events, {id: id});
        },
        getSelectedEvent: function () {
            return this.getEventById(this.selectedEventId);
        },
        getVenueById: function (id) {
            return _.find(this.selectedEvent.venues, {id: id});
        }
    }
});
