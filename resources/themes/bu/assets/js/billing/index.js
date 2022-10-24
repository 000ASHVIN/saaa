Vue.component('app-my-billing-screen', {

    props: {
        user: null
    },

    data: function () {
        return {
            busy: false,
            payment_method: '',
            otp: null,
            waiting_on_otp: false,
            requested_otp: false,
            forms: {
                debit_order: $.extend(true, new AppForm({
                    busy: false,
                    debit: {
                        bank: '',
                        number: '',
                        type: '',
                        branch_name: '',
                        branch_code: '',
                        billable_date: '',
                        account_holder: '',
                        id_number: '',
                        registration_number: "",
                        type_of_account: "",
                        otp: ""
                    }
                }), App.forms.debit_order)
            },
            addingNewCard: false,
            newcard: {
                number: '',
                holder: '',
                exp_month: '',
                exp_year: '',
                cvv: '',
                errors: []
            },
            threeDs: {
                url: '',
                connector: '',
                MD: '',
                TermUrl: '',
                PaReq: ''
            },
            readyForThreeDs: false,
            addingCard: false
        }
    },

    computed: {
        otpCorrect() {
            if(this.otp == null)
                return false;

            return this.otp == this.forms.debit_order.debit.otp;
        },

        debitOrderDetailsCompleted() {
            if (this.forms.debit_order.debit.type_of_account === 'company'){
                return this.forms.debit_order.debit.bank != '' &&
                    this.forms.debit_order.debit.number != '' &&
                    this.forms.debit_order.debit.type != '' &&
                    this.forms.debit_order.debit.branch_code != '' &&
                    this.forms.debit_order.debit.branch_name != '' &&
                    this.forms.debit_order.debit.billable_date != '' &&
                    this.forms.debit_order.debit.account_holder != '' &&
                    this.forms.debit_order.debit.type_of_account != '' &&
                    this.forms.debit_order.debit.registration_number != ''
            }else {
                return this.forms.debit_order.debit.bank != '' &&
                    this.forms.debit_order.debit.number != '' &&
                    this.forms.debit_order.debit.type != '' &&
                    this.forms.debit_order.debit.branch_code != '' &&
                    this.forms.debit_order.debit.branch_name != '' &&
                    this.forms.debit_order.debit.billable_date != '' &&
                    this.forms.debit_order.debit.account_holder != '' &&
                    this.forms.debit_order.debit.type_of_account != '' &&
                    this.forms.debit_order.debit.id_number != ''
            }
        }
    },

    methods: {

        /**
         * Add new Credit Card to Account
         */
        addCard() {
            this.busy = true;

            this.$http.post('/account/billing', this.newcard)
                .then(response => {
                    if(response.data.redirect === undefined || response.data.redirect === null) {
                        this.saveCard(response.data.id);
                        this.addingCard = false;
                        this.busy = false;
                    } else {
                        this.busy = false;
                        this.threeDs.url = response.data.redirect.url;
                        for (var i = response.data.redirect.parameters.length - 1; i >= 0; i--) {
                            this.threeDs[response.data.redirect.parameters[i].name] = response.data.redirect.parameters[i].value;
                        }
                        this.readyForThreeDs = true;
                    }
                })
                .catch(errors => {
                    this.busy = false;
                    this.newcard.errors = errors.data;
                });
        },

        /**
         * Save User Card
         */
        saveCard(id) {
            this.busy = true;
            this.$http.post('/account/billing/card', { id: id })
                .then(response => {
                    this.busy = false;
                    this.user.cards.push(response.data.card)
                    swal({
                        type: 'success',
                        title: 'Success',
                        text: 'Credit card has been added to your account'
                    })
                })
                .catch(errors => {
                    console.log(errors);
                    this.busy = false;
                    if(errors.data.number) {
                        swal({
                            type: 'error',
                            title: 'Whoops',
                            text: errors.data.number
                        })
                    }
                })
        },

        /**
         * Set User Primary Credit Card
         */
        setPrimary(cardId) {
            this.user.primary_card = cardId;

            this.$http.post('/account/billing/primary', { id: cardId })
                .then(response => {
                    this.user.primary_card = cardId;
                    swal({
                        type: 'success',
                        title: 'Success',
                        text: 'Primary Credit card has been changed'
                    })
                })
                .catch(errors => {
                    swal({
                        type: 'error',
                        title: 'Whoops',
                        text: 'Could not set that credit card as your primary card.'
                    })
                })
        },

        /**
         * Remove Credit Card from User's Account
         */
        removeCard(card) {

            if(card.id === this.user.primary_card) {
                swal({
                    type: 'warning',
                    title: 'Cannot delete card',
                    text: 'Cannot delete Primary Card, please set a new Primary Card in order to delete this card.'
                })
                return;
            }

                this.user.cards.$remove(card);

            this.$http.post('/account/billing/remove', { card_id: card.id })
                .then(response => {
                    swal({
                        type: 'success',
                        title: 'Success',
                        text: 'Credit card has been deleted'
                    })
                })
                .catch(errors => {
                    swal({
                        type: 'error',
                        title: 'Whoops',
                        text: 'Could not delete that credit card.'
                    })
                })
        },

        UpdateDebitOrderDetails() {

            if(this.payment_method == 'debit_order' && ! this.debitOrderDetailsCompleted) {
                swal({
                    type: 'warning',
                    title: 'Error',
                    text: "Your debit order details are not complete, please check and try again."
                })
                this.forms.debit_order.busy = false;
                return;
            }

            if(this.payment_method == 'debit_order' && ! this.requested_otp) {
                let data = {
                    account_number: this.forms.debit_order.debit.number,
                    branch_code: this.forms.debit_order.debit.branch_code
                }

                let valid = false;
                this.process();

                this.$http.post('/peach/validate', data)
                    .then((response) => {
                        if(response.data.Result != 'Valid') {
                            swal({
                                type: 'error',
                                title: 'Invalid Account Details',
                                text: "Ensure that you have filled out your information correctly and try again."
                            })
                            return;
                        } else {
                            valid = true;
                            return this.requestOTP();
                            swal.close();
                        }
                    })
                    .catch((errors) => {
                        console.log(errors)
                    })

                    if(! valid) {
                        this.forms.debit_order.busy = false;
                        return;
                    }
            }

            if(this.payment_method == 'debit_order' && ! this.otpCorrect) {
                swal({
                    type: 'error',
                    title: 'Invalid OTP',
                    text: "The OTP number you have entered is invalid, please try again."
                })

                this.forms.debit_order.busy = false;
                return;
            }

            if (this.otpCorrect){
                this.SubmitForUpdate();
            }
        },

        SubmitForUpdate() {
            this.process()
            let data = this.forms.debit_order.debit

            this.$http.post('/dashboard/edit/billing_information/update', data)
                .then(response => {
                    swal({
                        type: 'success',
                        title: 'Success',
                        text: "Thank you for updating your debit order details"
                    })
                this.busy = false
                // window.location.reload();
            })

            this.requested_otp = false;
            this.waiting_on_otp = false;
            this.otpCorrect = false;
            this.otp = '';
        },

        requestOTP: function () {
            this.$http.post('/peach/otp')
                .then((response) => {
                    this.otp = response.data.otp
                    this.requested_otp = true;
                    this.waiting_on_otp = true;
                    swal.close();
                })
        },

        resendOtp: function () {
            this.$http.post('/peach/otp')
                .then((response) => {
                    this.otp = response.data.otp
                    swal({
                        type: 'success',
                        title: 'OTP Sent!',
                        text: "The OTP has been sent!."
                    })
                })
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
        }
    }
});
