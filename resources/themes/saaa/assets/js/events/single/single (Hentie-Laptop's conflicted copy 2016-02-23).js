Vue.component('app-simple-event-registration-screen', {

    props: ['event', 'dietary'],

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        this.setVenues();
        this.setExtras();
        this.setDietary();
        this.startPusherSubscriber();
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            venues: [],
            online_venues: [],
            pricingOptions: [],
            dates: [],
            extras: [],
            AttendTypeState: false,
            threeDsInfo: {
                ACSUrl: null,
                PAReqMsg: null,
                TransactionIndex: null
            },
            pusher: null,
            forms: {
                eventSignup: $.extend(true, new AppForm({
                    busy: false,
                    venue: null,
                    pricingOption: null,
                    dates: [],
                    extras: [],
                    dietaryRequirement: null,
                    paymentOption: null,
                    card: {
                        enrolled: false,
                        number: null,
                        cvc: null,
                        month: null,
                        year: null,
                        md: null,
                        pares: null
                    },
                    terms: false
                }), App.forms.eventSignup)
            }
        };
    },

    computed: {

        /*
         * Compute the total of options the user has selected
         */
        total: function () {

            var pricings = this.getTotalPricing();
            var dietary = this.getTotalDiatry();
            var extras = this.getTotalExtras();
            var total = parseInt(pricings) + parseInt(dietary) + parseInt(extras);

            if (total == 0)
                this.forms.eventSignup.paymentOption = 'eft';

            return total;
        },

        /*
         * Determine if the venue type selector is set to "physical".
         */
        shouldShowPhysicalVenues: function () {
            return !this.AttendTypeState || this.online_venues.length == 0;
        },


        /*
         * Determine if the plan type selector is set to "online".
         */
        shouldShowOnlineVenues: function () {
            return this.AttendTypeState || this.venues.length == 0;
        }
    },


    methods: {

        register: function () {
            this.forms.eventSignup.errors.forget();
            this.forms.eventSignup.busy = true;
            this.process();
            if (this.total == 0)
                this.sendRegistration();

            if (this.forms.eventSignup.paymentOption == 'cc')
                this.checkThreeDs();

            if (this.forms.eventSignup.paymentOption == 'eft')
                this.sendRegistration();
        },

        sendRegistration: function () {

            var payload = {
                event: this.event.id,
                venue: this.forms.eventSignup.venue.id,
                pricing: this.forms.eventSignup.pricingOption.id,
                dates: this.forms.eventSignup.dates,
                extras: this.forms.eventSignup.extras,
                dietary: this.forms.eventSignup.dietaryRequirement.id,
                terms: this.forms.eventSignup.terms,
                card: this.forms.eventSignup.card,
                paymentOption: this.forms.eventSignup.paymentOption
            };

            this.$http.post('/events/' + this.event.slug + '/register', payload)
                .success(function (response) {
                    this.forms.eventSignup.busy = false;
                    // window.location = '/dashboard/events';
                })
                .error(function (errors) {
                    this.forms.eventSignup.busy = false;
                    this.clearThreeDs();
                    swal.close();
                    this.forms.eventSignup.errors.set(errors);
                });
        },

        setVenues: function () {
            for (var i = this.event.venues.length - 1; i >= 0; i--) {
                if (this.event.venues[i].city) {
                    this.venues.push(this.event.venues[i]);
                } else {
                    this.online_venues.push(this.event.venues[i]);
                }
            }
            if (this.venues.length == 0 && this.online_venues.length == 1)
                this.selectVenue(this.online_venues[0]);
        },

        setDietary: function setDietary() {
            this.forms.eventSignup.dietaryRequirement = this.dietary[0];
        },

        setExtras: function () {
            for (var i = this.event.extras.length - 1; i >= 0; i--) {
                this.extras.push(this.event.extras[i]);
            }
        },

        selectVenue: function (venue) {
            var vm = this;
            vm.forms.eventSignup.venue = venue;
            vm.pricingOptions = venue.pricings;

            if (venue.pricings.length <= 1)
                this.autoSelectPricing(venue);
            //here
            if (venue.dates.length <= vm.forms.eventSignup)
                this.autoSelectAllDates();
        },

        selectAnotherVenue: function () {
            this.forms.eventSignup.venue = null;
            this.forms.eventSignup.pricingOption = null;
            this.forms.eventSignup.dates = [];
            this.forms.eventSignup.paymentOption = null;
        },

        autoSelectPricing: function (venue) {
            this.forms.eventSignup.pricingOption = venue.pricings[0];
        },

        autoSelectAllDates: function () {
            var vm = this;
            _.forEach(vm.forms.eventSignup.venue.dates, function (date, dateKey) {
                vm.forms.eventSignup.dates.push(date);
            })
        },

        /*
         * Calculate the total Pricings
         */
        getTotalPricing: function () {

            var price = this.forms.eventSignup.pricingOption.price;

            if (this.forms.eventSignup.pricingOption.discount > 0)
                price = this.forms.eventSignup.pricingOption.discounted_price;

            return price;
        },

        /*
         * Calculate the total Diatry Requirements
         */
        getTotalDiatry: function () {

            if (this.forms.eventSignup.dietaryRequirement != null) {
                return this.forms.eventSignup.dietaryRequirement.price ? this.forms.eventSignup.dietaryRequirement.price : 0;
            }

            return 0;
        },

        /*
         * Calculate the total Extras
         */
        getTotalExtras: function () {
            var total = 0;
            for (var i = this.forms.eventSignup.extras.length - 1; i >= 0; i--) {
                total += this.forms.eventSignup.extras[i].price;
            }
            ;

            return total;
        },

        /*
         Here we will build the payload to send to MyGate, which will
         return an md and pares. This can be used to make charges on
         the user's credit cards instead of storing the numbers.
         */
        checkThreeDs: function () {

            var payload = {
                ccNo: this.forms.eventSignup.card.number,
                expMonth: this.forms.eventSignup.card.month,
                expYear: this.forms.eventSignup.card.year,
                amount: this.total,
                orderId: '1',
                reference: "ThreeDSCheck"
            };

            this.$http.post('/payment/checkThreeDs', payload)
                .success(function (response) {

                    if (response.Result != 0) {
                        this.forms.eventSignup.busy = false;
                        this.forms.eventSignup.card.errors.set({number: [response.ErrorDesc]});
                        this.clearThreeDs();
                    } else {
                        if (response.Enrolled == "Y") {
                            this.forms.eventSignup.card.enrolled = true;
                            this.processThreeDS(response);
                        } else {
                            this.forms.eventSignup.card.md = response.TransactionIndex;
                            this.forms.eventSignup.card.pares = "";
                            this.sendRegistration();
                        }
                    }

                })
                .error(function (errors) {
                    this.forms.eventSignup.errors.set({number: ["Failed to verify if your card has been enrolled for 3DS verification."]});
                    this.forms.eventSignup.busy = false;
                    this.clearThreeDs();
                    swal.close();
                });
        },

        /*
         * Setting 3DS Data and Display Popup for user to fill in credit card 3DS Pin
         */
        processThreeDS: function (response) {

            var vm = this;

            this.threeDsInfo.ACSUrl = response.ACSUrl;
            this.threeDsInfo.PAReqMsg = response.PAReqMsg;
            this.threeDsInfo.TransactionIndex = response.TransactionIndex;

            setTimeout(function () {
                vm.submitThreeDsForm();
            }, 500);
        },

        /**
         * Submit the 3DS form and display Iframe for user to complete
         */
        submitThreeDsForm: function () {

            swal({
                title: "",
                text: '<iframe id="threeDFrame" name="threeDFrame" src="' + this.threeDsInfo.ACSUrl + '" frameborder="0"></iframe>',
                html: true,
                allowEscapeKey: true,
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
                closeOnCancel: false,
                customClass: 'no-bg'
            });

            $('#threeDsForm').submit();
        },

        startPusherSubscriber: function () {
            var vm = this;
            var pusher = new Pusher('5e9e56a5a0ebaf5484b0');
            var channel = pusher.subscribe('3ds');

            channel.bind('App\\Events\\ThreeDSecureCompleted', function (data) {
                if (data.md == vm.threeDsInfo.TransactionIndex) {
                    vm.forms.eventSignup.card.md = data.md;
                    vm.forms.eventSignup.card.pares = data.pares;
                    vm.process();
                    vm.sendRegistration();
                }
            });
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

        clearThreeDs: function () {
            this.threeDsInfo.ACSUrl = '';
            this.threeDsInfo.PAReqMsg = '';
            this.threeDsInfo.TransactionIndex = '';

            this.forms.eventSignup.card.number = '';
            this.forms.eventSignup.card.month = '';
            this.forms.eventSignup.card.year = '';
            this.forms.eventSignup.card.cvc = '';
            this.forms.eventSignup.card.md = '';
            this.forms.eventSignup.card.pares = '';
        },

        /*
         * Get the proper column width based on the number of venues.
         */
        getVenueColumnWidth: function (count) {
            switch (count) {
                case 1:
                    return 'col-md-4 col-md-offset-4';
                case 2:
                    return 'col-md-6';
                case 3:
                    return 'col-md-4';
                case 4:
                    return 'col-md-12';
                default:
                    console.error("We only support up to 4 venues per interval.");
            }
        }
    }

});
