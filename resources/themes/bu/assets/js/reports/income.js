Vue.component('income-reports', {

    data: function () {
        return {
            filter: '',
            transactions: null,    
            forms: {
                incomeReports: $.extend(true, new AppForm({
                    from: null,
                    to: null
                }), App.forms.incomeReports),
                exportReport: $.extend(true, new AppForm({
                    file_name: '',
                    discounts: null,
                    cancellations: null,
                    invoices: null,
                    payments: null
                }), App.forms.exportReport)
            }
        }
    },

    computed: {
        /**
         * Get all of the invoice transactions.
         */
        invoices() {
            return _.filter(this.transactions, transaction => {
                return transaction.type == 'debit' && transaction.display_type == 'Invoice';
            });
        },

        /**
         * Get the total of the invoices transactions.
         */
        invoicesTotal() {
            return _.reduce(this.invoices, (memo, transaction) => {
                return memo + transaction.amount;
            }, 0);
        },

        /**
         * Get all of the payment transactions.
         */
        payments() {
            return _.filter(this.transactions, transaction => {
                return transaction.type == 'credit' && transaction.display_type == 'Payment';
            });
        },

        /**
         * Get the total of the payments transactions.
         */
        paymentsTotal() {
            return _.reduce(this.payments, (memo, transaction) => {
                return memo + transaction.amount;
            }, 0);
        },

        /**
         * Get all of the discount transactions.
         */
        discounts() {
            return _.filter(this.transactions, transaction => {
                return transaction.tags == 'Discount';
            });
        },

        /**
         * Get the total of the discounts transactions.
         */
        discountsTotal() {
            return _.reduce(this.discounts, (memo, transaction) => {
                return memo + transaction.amount;
            }, 0);
        },

        /**
         * Get all of the adjustments transactions.
         */
        cancellations() {
            return _.filter(this.transactions, transaction => {
                return transaction.type == 'credit' && transaction.tags == 'Cancellation';
            });
        },

        /**
         * Get the total of the adjustments transactions.
         */
        cancellationsTotal() {
            return _.reduce(this.cancellations, (memo, transaction) => {
                return memo + transaction.amount;
            }, 0);
        },

        /**
         * Get the total of the creditnotes transactions.
         */
        creditnotesTotal() {
            return this.discountsTotal + this.cancellationsTotal;
        },

        /**
         * Get the total Debit Transactions
         */
        totalDebit() {
            return this.invoicesTotal;
        },

        /**
         * Get Total Credit
         */
        totalCredit() {
            return this.creditnotesTotal + this.paymentsTotal;
        },

        /**
         * Get the outstanding Total.
         */
        outstandingTotal() {
            return this.totalDebit - this.totalCredit;
        }
    },

    methods: {
        runIncomeReport() {
            this.transactions = null;

            this.forms.incomeReports.busy = true;

            setTimeout(() => {
                this.$http.post('/admin/reports/payments/income', this.forms.incomeReports)
                    .then(response => {
                        this.transactions = response.data;
                        this.forms.incomeReports.busy = false;
                    })
                    .catch(errors => {
                        alert("Whoops, something went wrong.");
                        this.forms.incomeReports.busy = false;
                    });
            }, 3000);
        },

        runExportReport() {
            this.forms.exportReport.busy = true;

            this.forms.exportReport.file_name = 'Income report: ' + this.forms.incomeReports.from + ' ' + this.forms.incomeReports.to;
            this.forms.exportReport.discounts = this.discounts;
            this.forms.exportReport.cancellations = this.cancellations;
            this.forms.exportReport.invoices = this.invoices;
            this.forms.exportReport.payments = this.payments;

            setTimeout(() => {
                this.$http.post('/admin/reports/payments/export', this.forms.exportReport)
                    .then(response => {
                        this.forms.exportReport.busy = false;
                        window.open(response.data.file);
                    })
                    .catch(errors => {
                        this.forms.exportReport.busy = false;
                    }); 
            }, 3000);
        },
    }
});
