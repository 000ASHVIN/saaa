Vue.component('webinar-on-demand-checkout', {
    props: {
        video: {
            required: true
        },
        user: {
            required: true
        }
    },

    data: function () {
        return {
            instantlink: '',
            payment_token: '',
            instant_eft_success: false,
            forms: {
                webinarCheckout: $.extend(true, new AppForm({
                    busy: false,
                    paymentOption: 'cc',
                    card: null,
                    terms: false
                }), App.forms.webinarCheckout)
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
        }
    },

    ready: function() {
        window.addEventListener("message", (event) => {
            if (typeof event.data == 'string') {
                try {
                    eval(event.data);
                    setTimeout(() => {
                        this.forms.webinarCheckout.busy = false;
                        swal.close()
                    }, 2000)
                }
                catch(e){
                    this.forms.webinarCheckout.busy = false;
                    // swal.close()
                }
            }
        });
    },

    methods: {

        popEft() {
            this.process();
            this.forms.webinarCheckout.busy = true;
            this.getEftLink();
        },

        /**
         * Add New Card
         */
        addCard() {
            this.newcard.return = `/webinars_on_demand/checkout/${this.video.slug}?threeDs=yes`;

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
                    this.forms.webinarCheckout.card = response.data.card.id;
                    swal.close()
                })
                .catch(errors => {
                    if(errors.number) {
                        swal({
                            type: 'error',
                            title: 'Whoops',
                            text: errors.number
                        });
                    }
                });
        },

        cancelPayment: function () {
            this.forms.webinarCheckout.busy = false;
        },

        getEftLink: function () {
            this.processPayment();

            let data = {
                video: this.video.id
            };

            this.$http.post('/instant-eft', data)
                .success((response) => {
                    this.instantlink = response.link;
                    this.payment_token = response.key;
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
                    });
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
                console.log(data) ;
                if(data.success) {
                    this.instant_eft_success = true;
                    this.sendRegistration();
                } else {
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: "Your Payment has failed, please try again"
                    });
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

        register: function () {
            this.forms.webinarCheckout.errors.forget();
            this.forms.webinarCheckout.busy = true;
            //this.process();
            this.sendRegistration();
        },
        processPayment: function() {
            swal({
                type: "warning",
                title: "Payment Processing",
                text: "Please wait while we are processing your payment.Do not Refresh page or close browser"
            })
        },
        sendRegistration: function () {

            if(this.forms.webinarCheckout.paymentOption == 'eft' && this.instant_eft_success == false) {
                this.forms.webinarCheckout.busy = true;
                return;
            }

            if(this.forms.webinarCheckout.paymentOption == 'cc' && ! this.forms.webinarCheckout.card) {
                swal({
                    type: 'warning',
                    title: 'Error',
                    text: "Unable to purchase without credit card, please select credit card"
                });
                this.forms.webinarCheckout.busy = false;
                return;
            }

            var payload = {
                video: this.video.id,
                terms: this.forms.webinarCheckout.terms,
                card: this.forms.webinarCheckout.card,
                paymentOption: this.forms.webinarCheckout.paymentOption
            };
            
           this.processPayment();

            this.$http.post('/webinars_on_demand/checkout/' + this.video.slug + '/complete', payload)
                .success(function (response) {
                    this.forms.webinarCheckout.busy = false;
                    swal.close();
                    window.location = '/dashboard/webinars_on_demand';
                })
                .error(function (errors) {
                    this.forms.webinarCheckout.busy = false;
                    swal.close();
                    this.forms.webinarCheckout.errors.set(errors);
                });
        },
    },


})