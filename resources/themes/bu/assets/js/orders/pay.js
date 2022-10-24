Vue.component('app-order-pay', {

    props: {
        'order': {
            'required': true
        },
        'user': {
            'required': true
        }
    },

    /*
     * Initial state of the component's data.
     */
    data: function () {
        return {
            instantlink: '',
            payment_token: '',
            instant_eft_success: false,
            forms: {
                pay: $.extend(true, new AppForm({
                    busy: false,
                    paymentOption: "cc",
                    card: null,
                    terms: false
                }), App.forms.pay)
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

    ready: function() {
        window.addEventListener("message", (event) => {
            if (typeof event.data == 'string') {
                try {
                    eval(event.data);
                    setTimeout(() => {
                        this.forms.pay.busy = false;
                        swal.close()
                    }, 2000)
                }
                catch(e){
                    this.forms.pay.busy = false;
                    swal.close()
                }
            }
        });
    },

    methods: {

        popEft() {
            this.process();
            this.forms.pay.busy = true;
            this.getEftLink();
        },

        addCard() {
            this.newcard.return = `/invoices/settle/${this.order.id}?threeDs=yes`;

            this.processPayment();

            this.$http.post('/account/billing', this.newcard)
                .then(response => {
                    if(response.data.redirect === undefined || response.data.redirect === null) {
                        this.saveCard(response.data.id);
                        this.addingNewCard = false;
                        swal.close();
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
                    this.forms.subscription.card = response.data.card.id;
                    swal.close()
                })
                .catch(errors => {
                    if(errors.data.number) {
                        swal({
                            type: 'error',
                            title: 'Whoops',
                            text: errors.data.number
                        })
                    }
                })
        },

        pay() {
            this.forms.pay.errors.forget();
            this.forms.pay.busy = true;
            this.process();
            this.sendPay();
        },
        processPayment: function() {
            swal({
                type: "warning",
                title: "Payment Processing",
                text: "Please wait while we are processing your payment.Do not Refresh page or close browser"
            })
        },
        sendPay() {

            if(this.forms.pay.paymentOption == 'eft' && this.instant_eft_success == false) {
                this.forms.pay.busy = true;
                return;
            }

            var payload = {
                terms: this.forms.pay.terms,
                card: this.forms.pay.card,
                paymentOption: this.forms.pay.paymentOption,
            };

            this.processPayment();
            this.$http.post(`/invoice/order/settle/${this.order.id}`, payload)
                .success((response) => {
                    this.forms.pay.busy = false;
                    swal.close();
                    window.location = '/dashboard/invoice_orders';
                })
                .error((errors) => {
                    this.forms.pay.busy = false;

                    if(errors.errors.card) {
                        swal({
                            type: 'error',
                            title: 'Whoops',
                            text: errors.errors.card
                        })
                    } else {
                        swal.close();
                    }

                    this.forms.pay.errors.set(errors);
                });
        },

        cancelPayment: function () {
            this.forms.pay.busy = false;
        },

        getEftLink: function () {
            this.processPayment();

            let data = {
                order: this.order.id
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
                        this.sendPay();
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
                    this.sendPay(); // Change to Relevant Pay Method
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

        process() {
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
        }
    }
})
;