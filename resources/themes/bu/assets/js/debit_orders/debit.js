import moment from 'moment'
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('debit-order-admin', {
    data: function () {
        return {
            search_results: [],
            selectedResult: null,
            search_results_count: null,
            forms: {
                search_debit_order: $.extend(true, new AppForm({
                    busy: false,
                    account_holder: '',
                    number: '',
                    branch: '',
                    provider: '',
                    status: '',
                    debit_date: '',
                }), App.forms.search_debit_order),

                debitOrderForm: $.extend(true, new AppForm({
                    busy: false,
                    account_holder: '',
                    bank: '',
                    number: '',
                    branch_code: '',
                    branch_name: '',
                    type: '',
                    type_of_account: '',
                    registration_number: '',
                    id_number: '',
                    skip_next_debit: '',
                    billable_date: '',
                    peach: '',
                    note: '',
                    otp: '',
                    errors: []
                }), App.forms.debitOrderForm)
            },
        }
    },

    filters: {
        formatDate: function (value) {
            if (value) {
                return moment(String(value)).format('MM/DD/YYYY')
            }
        },
    },

    methods:{
        download_data: function(){
            if (this.search_results_count){
                let data = this.forms.search_debit_order;

                this.$http.post(`/admin/debit_orders/export_search`, data)
                    .then((response) => {
                        window.open(response.data.file);

                        if(response.data.data.length >= 1){
                            swal.close()

                        }else{
                            swal({
                                type: 'warning',
                                title: 'No Results Found',
                                text: "We have not find any results using that filter."
                            })
                        }

                    });
            } else{
                swal({
                    type: 'warning',
                    title: 'No Results Found',
                    text: "We have not find any results using that filter."
                })
            }
        },

        resetSearch: function(){
            this.search_results = ''
            this.selectedResult = ''
            this.search_results_count = ''
        },

        clearSelected: function(){
            this.selectedResult = ''
        },

        search: function(page = 1){
            this.process()
            let data = this.forms.search_debit_order
            this.$http.post(`/admin/debit_orders/search?page=${page}`, data)
                .then((response) => {
                    this.search_results = ''
                    this.search_results_count = response.data.total
                    this.search_results = response.data
                    if(response.data.data.length >= 1){
                        swal.close()
                    }else{
                        swal({
                            type: 'warning',
                            title: 'No Results Found',
                            text: "We have not find any results using that filter."
                        })
                    }

            });
        },

        fetch: function(page = 1){
        this.selectedResult = ''
        let data = this.forms.search_debit_order
        this.$http.post(`/admin/debit_orders/search?page=${page}`, data)
            .then((response) => {
                this.search_results = ''
                this.search_results = response.data
            });
        },

        sendOTP: function () {
            this.$http.post('/admin/member/'+this.selectedResult.user.id +'/peach/otp')
                .then((response) => {
                    this.otp = response.data.otp
                    swal({
                        type: 'success',
                        title: 'OTP Sent!',
                        text: "The OTP has been sent!."
                    })
                })
        },

        updateDetails: function () {
            let debit_data = {
                account_number: this.forms.debitOrderForm.number,
                branch_code: this.forms.debitOrderForm.branch_code
            }

            let valid = false;
            this.process();

            this.$http.post('/peach/validate', debit_data)
                .then((response) => {
                    if(response.data.Result != 'Valid') {
                        swal({
                            type: 'error',
                            title: 'Invalid Account Details',
                            text: "Ensure that you have filled out your information correctly and try again."
                        })
                        return;
                    } else {
                        this.forms.debitOrderForm.errors = [];
                        let data = this.forms.debitOrderForm
                        this.$http.post(`/admin/debit_orders/update/`+ this.selectedResult.id, data)
                            .then((response) => {
                                if (response.data.errors){
                                    this.forms.debitOrderForm.errors = response.data.errors;
                                    swal({
                                        type: 'error',
                                        title: 'Something went wrong!',
                                        text: "Please check your input and try again"
                                    })
                                } else{
                                    this.forms.debitOrderForm.errors = [];
                                    swal({
                                        type: 'success',
                                        title: 'Success!',
                                        text: "Details has been updated successfully!"
                                    })
                                    this.selectedResult = ''
                                }
                            })
                    }
                })
                .catch((errors) => {
                    console.log(errors)
                })

            if(! valid) {
                return;
            }
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

        selectResult: function (selected) {
            this.forms.debitOrderForm.errors = []
            this.selectedResult = selected
            this.forms.debitOrderForm.otp = selected.otp
            this.forms.debitOrderForm.type = selected.type
            this.forms.debitOrderForm.note = selected.note
            this.forms.debitOrderForm.bank = selected.bank
            this.forms.debitOrderForm.skip_next_debit = selected.skip_next_debit
            this.forms.debitOrderForm.peach = selected.peach
            this.forms.debitOrderForm.number = selected.number
            this.forms.debitOrderForm.id_number = selected.user.id_number
            this.forms.debitOrderForm.branch_code = selected.branch_code
            this.forms.debitOrderForm.branch_name = selected.branch_name
            this.forms.debitOrderForm.billable_date = selected.billable_date
            this.forms.debitOrderForm.account_holder = selected.user.first_name +' '+ selected.user.last_name
            this.forms.debitOrderForm.type_of_account = selected.type_of_account
            this.forms.debitOrderForm.registration_number = selected.registration_number
        }
    },
});