Vue.component('app-simple-event-registration-screen', {

    props: {
        event: {
            required: true
        },
        dietary: {
            required: true
        },
        promoCodes: {
            required: false,
            default: []
        },
        user: {
            required: true
        },
        donations: {
            required: false,
            default: false
        }
    },

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {
        window.addEventListener("message", (event) => {
            if (typeof event.data == 'string') {
                try {
                    eval(event.data);
                    setTimeout(() => {
                        this.forms.eventSignup.busy = false;
                        swal.close()
                    }, 2000)
                }
                catch(e){
                    this.forms.eventSignup.busy = false;
                    // swal.close()
                }
            }
        });
        this.setVenues();
        this.setExtras();
        this.setDietary();
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
            instantlink: '',
            payment_token: '',
            instant_eft_success: false,
            forms: {
                eventSignup: $.extend(true, new AppForm({
                    busy: false,
                    venue: null,
                    pricingOption: null,
                    dates: [],
                    extras: [],
                    dietaryRequirement: null,
                    paymentOption: null,
                    card: null,
                    terms: false,
                    donations: 1
                }), App.forms.eventSignup)
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

    watch: {
        'forms.eventSignup.pricingOption': function (val, oldVal) {
            var vm = this;
            if (val) {
                Vue.set(vm.forms.eventSignup, 'dates', []);
                var numberOfDates = vm.forms.eventSignup.venue.dates.length;
                var pricingDayCount = vm.forms.eventSignup.pricingOption.day_count;
                if (numberOfDates <= pricingDayCount) {
                    _.forEach(vm.forms.eventSignup.venue.dates, function (date, dateKey) {
                        vm.forms.eventSignup.dates.push(date);
                    });
                }
            }
        }
    },

    computed: {

        /**
         * Check if the Event only has 1 Venue
         * @returns boolean
         */
        hasSingleVenue: function() {
            return this.event.venues.length === 1;
        },

        /*
         * Compute the total of options the user has selected
         */
        total: function () {

            var pricings = this.getTotalPricing();
            var dietary = this.getTotalDiatry();
            var extras = this.getTotalExtras();
            var donation = this.getDonations();

            var total = parseInt(pricings) + parseInt(dietary) + parseInt(extras) + parseInt(donation);

            if (this.forms.eventSignup.pricingOption && total == 0)
                this.forms.eventSignup.paymentOption = null;

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

        popEft() {
            this.process();
            this.forms.eventSignup.busy = true;
            this.getEftLink();
        },

        /**
         * Add New Card
         */
        addCard() {
            this.newcard.return = `/events/${this.event.slug}/register?threeDs=yes&venue=${this.forms.eventSignup.venue.id}`;

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
                    this.forms.eventSignup.card = response.data.card.id;
                    swal.close()
                })
                .catch(errors => {
                    if(errors.number) {
                        swal({
                            type: 'error',
                            title: 'Whoops',
                            text: errors.number
                        })
                    }
                })
        },

        dateChanged: function () {
            var vm = this;
            var numberOfDates = vm.forms.eventSignup.dates.length;
            var pricingDayCount = vm.forms.eventSignup.pricingOption.day_count;
            if (numberOfDates > pricingDayCount) {
                vm.forms.eventSignup.dates = _.slice(vm.forms.eventSignup.dates, 1);
            }
        },

        register: function () {
            this.forms.eventSignup.errors.forget();
            this.forms.eventSignup.busy = true;
           // this.process();
            this.sendRegistration();
        },

        sendRegistration: function () {

            if(this.forms.eventSignup.paymentOption == 'eft' && this.instant_eft_success == false) {
                this.forms.eventSignup.busy = true;
                return;
            }

            if(this.forms.eventSignup.paymentOption == 'cc' && ! this.forms.eventSignup.card) {
                swal({
                    type: 'warning',
                    title: 'Error',
                    text: "Unable to register without credit card, please select credit card"
                })
                this.forms.eventSignup.busy = false;
                return;
            }

            var payload = {
                event: this.event.id,
                venue: this.forms.eventSignup.venue.id,
                pricing: this.forms.eventSignup.pricingOption.id,
                dates: this.forms.eventSignup.dates,
                extras: this.forms.eventSignup.extras,
                dietary: this.forms.eventSignup.dietaryRequirement.id,
                terms: this.forms.eventSignup.terms,
                card: this.forms.eventSignup.card,
                donations: this.getDonations(),
                paymentOption: this.forms.eventSignup.paymentOption
            };

            this.processPayment();

            this.$http.post('/events/' + this.event.slug + '/register', payload)
                .success(function (response) {
                    this.forms.eventSignup.busy = false;
                    swal.close();
                    window.location = '/dashboard/events';
                })
                .error(function (errors) {
                    this.forms.eventSignup.busy = false;
                    swal.close();
                    this.forms.eventSignup.errors.set(errors);
                });
        },

        cancelPayment: function () {
            this.forms.eventSignup.busy = false;
        },

        getEftLink: function () {
            this.processPayment();

            let data = {
                event: this.event.id,
                total: this.total
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

        setVenues: function () {
            for (var i = this.event.venues.length - 1; i >= 0; i--) {
                if (this.event.venues[i].city) {
                    this.venues.push(this.event.venues[i]);
                } else {
                    this.online_venues.push(this.event.venues[i]);
                }
            }
            
            this.venues = _.sortBy(this.venues, function(o) { return o.dates[0].date; });
            
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
            if (!this.forms.eventSignup.pricingOption)
                return 0;

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
                if(this.forms.eventSignup.dietaryRequirement.price > 0)
                {
                    if(this.forms.eventSignup.dates.length > 1) {
                        return this.forms.eventSignup.dietaryRequirement.price * this.forms.eventSignup.dates.length;
                    } else {
                        return this.forms.eventSignup.dietaryRequirement.price;
                    }
                } else {
                    return 0;
                }
            }

            return 0;
        },

        /*
         * Calculate the total Extras
         */
        getTotalExtras: function () {
            var total = 0;

            this.forms.eventSignup.extras.forEach(extra => {
                total = parseInt(total) + parseInt(extra.price);
            });

            return total;
        },

        /*
         * Calculate the donation
         */
        getDonations: function() {

            var total = 0;
            if(this.forms.eventSignup.donations) {
                total = this.donations;
            }
            return total;
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
        processPayment: function() {
            swal({
                type: "warning",
                title: "Payment Processing",
                text: "Please wait while we are processing your payment.Do not Refresh page or close browser"
            })
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
                    return 'col-md-3';
                default:
                    return 'col-md-2';
            }
        }
    }

});
