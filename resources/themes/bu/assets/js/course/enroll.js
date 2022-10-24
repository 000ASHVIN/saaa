Vue.component('enroll', {
    props:{
        user: {
            required: true
        },

        course: {
            required: true
        },
    },

    data: function () {
        return {
            option: null,
            instantlink: '',
            payment_token: '',
            instant_eft_success: false,
            forms: {
                enroll: $.extend(true, new AppForm({
                    busy: false,
                    paymentOption: null,
                    card: null,
                    terms: false
                }), App.forms.enroll),
                addingNewCard: false,
            },
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
                        this.forms.enroll.busy = false;
                        swal.close();
                    }, 2000);
                }
                catch(e){
                    this.forms.enroll.busy = false;
                    // swal.close()
                }
            }
        });
    },

    methods:{
        popEft() {
            this.process();
            this.forms.enroll.busy = true;
            this.getEftLink();
        },

        addCard() {
            this.newcard.return = `/courses/enroll/${this.course.reference}?threeDs=yes`;

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

        saveCard(id) {
            this.$http.post('/account/billing/card', { id: id })
                .then(response => {
                    this.user.cards.push(response.data.card)
                    this.forms.enroll.card = response.data.card.id;
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
            this.forms.enroll.busy = false;
        },

        getEftLink: function () {
            this.processPayment();

            let data = {
                course: this.course.id,
                option: this.option
            };

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
                paymentType : paymentType
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
        processPayment: function() {
            swal({
                type: "warning",
                title: "Payment Processing",
                text: "Please wait while we are processing your payment.Do not Refresh page or close browser"
            })
        },
        register: function () {
            this.forms.enroll.errors.forget();
            this.forms.enroll.busy = true;
           // this.process();
            this.sendRegistration();
        },

        sendRegistration: function () {
            if(this.forms.enroll.paymentOption == 'eft' && this.instant_eft_success == false) {
                this.forms.enroll.busy = true;
                return;
            }

            if(this.forms.enroll.paymentOption == 'cc' && ! this.forms.enroll.card) {
                swal({
                    type: 'warning',
                    title: 'Error',
                    text: "Unable to purchase without credit card, please select credit card"
                });

                this.forms.enroll.busy = false;
                return;
            }

            var payload = {
                course: this.course.id,
                terms: this.forms.enroll.terms,
                card: this.forms.enroll.card,
                paymentOption: this.forms.enroll.paymentOption,
                enrollmentOption: this.option
            };
            this.processPayment();

            this.$http.post('/courses/checkout/' + this.course.reference + '/complete', payload)
                .success(function (response) {
                    this.forms.enroll.busy = false;
                    swal.close()
                    window.location = '/dashboard/invoices';
                })
                .error(function (errors) {
                    this.forms.enroll.busy = false;
                    swal.close();
                    this.forms.enroll.errors.set(errors);
                });
        }


    }
});