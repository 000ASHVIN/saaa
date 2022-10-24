Vue.component('app-store-checkout', {

    props: ['cartItems', 'cartHasPhysicalProduct', 'cartTotalQuantity', 'cartTotalDiscountedPrice', 'shippingMethods', 'addresses', 'countries', 'provinces', 'user', 'donations'],

    /*
     * Bootstrap the component. Load the initial data.
     */
    ready: function () {

        window.addEventListener("message", (event) => {
            if (typeof event.data == 'string') {
                try {
                    eval(event.data);
                    setTimeout(() => {
                        this.forms.checkout.busy = false;
                        swal.close()
                    }, 2000)
                }
                catch(e){
                    this.forms.checkout.busy = false;
                    // swal.close()
                }
            }
        });

        this.generateOptionTitlesForAddresses();
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
                checkout: $.extend(true, new AppForm({
                    busy: false,
                    shippingMethod: this.shippingMethods[0],
                    deliveryAddress: this.getDefaultDeliveryAddress(),
                    paymentOption: null,
                    free: false,
                    card: null,
                    terms: false,
                    donations: 1
                }), App.forms.checkout)
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
            readyForThreeDs: false,
            dataLayer:[]
        };
    },

    computed: {
        total: function () {
            var that = this;
            var total = that.cartTotalDiscountedPrice;
            if (that.cartHasPhysicalProduct)
                total += parseFloat(this.forms.checkout.shippingMethod.price);

            if (this.getDonations()) {
                total += this.getDonations();
            }

            return total;
        },

        free: function () {
          if (this.total <= 0){
              this.forms.checkout.free = true
          }
          return this.forms.checkout.free
        },

        checkFreePayment: function () {
          if (this.forms.checkout.free === true){
              this.forms.checkout.paymentOption = 'eft'
          }
            return this.forms.checkout.paymentOption
        }
    },

    methods: {
        popEft() {
            this.process();
            this.forms.checkout.busy = true;
            this.getEftLink();
        },

        addCard() {
            this.newcard.return = `/store/checkout?threeDs=yes`;

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
            this.process();
            this.$http.post('/account/billing/card', { id: id })
                .then(response => {
                    this.user.cards.push(response.data.card)
                    this.forms.subscription.card = response.data.card.id;
                    swal.close()
                })
                .catch(errors => {
                    swal.close();
                    if(errors.number) {
                        swal({
                            type: 'error',
                            title: 'Whoops',
                            text: errors.number
                        })
                    }
                })
        },

        generateOptionTitlesForAddresses: function () {
            var that = this;
            _.forEach(this.addresses, function (address, key) {
                var selectOptionTitle = address.type.toUpperCase();
                if (address.line_one)
                    selectOptionTitle += " - " + address.line_one;
                if (address.line_two)
                    selectOptionTitle += ", " + address.line_two;
                if (address.city)
                    selectOptionTitle += ", " + address.city;
                if (address.area_code)
                    selectOptionTitle += ", " + address.area_code;
                if (address.province)
                    selectOptionTitle += ", " + that.provinces[address.province];
                if (address.country)
                    selectOptionTitle += ", " + that.countries[address.country];

                Vue.set(address, 'selectOptionTitle', selectOptionTitle);
            });
        },

        getDefaultDeliveryAddress: function () {
            if (this.addresses.length == 0)
                return 0;

            var primaryAddress = false;
            _.forEach(this.addresses, function (address, key) {
                if (address.primary) {
                    primaryAddress = address;
                    return false;
                }
            });

            if (primaryAddress)
                return primaryAddress;

            return this.addresses[0];
        },
        
        calculateTotalQuantity: function () {
            var totalQty = 0;
            _.forEach(this.cart, function (cartItem, key) {
                totalQty += cartItem.quantity;
            });
            return totalQty;
        },

        checkout: function () {
            this.forms.checkout.errors.forget();
            this.forms.checkout.busy = true;
            //this.process();
            this.sendCheckout();
        },
        sendTransactions: function(invoice){
            this.dataLayer.event='checkout.event';
            this.dataLayer.transactionId=invoice.reference;
            this.dataLayer.transactionTotal=parseFloat(parseFloat(invoice.total).toFixed(2));
            this.dataLayer.transactionTax=parseFloat(((invoice.total/100)*invoice.vat_rate).toFixed(2));
            this.dataLayer.transactionProducts=[];

            if(invoice.items.length>0){
                const self= this;
                invoice.items.forEach(function(item){
                    var prof = {
                        'sku': 'store-'+item.item_id,
                        'name': item.name,
                        'category': 'Store',
                        'price': item.price,
                        'quantity': item.quantity
                    }
                    self.dataLayer.transactionProducts.push(prof)
                })
            }
           this.dataLayer = Object.assign({},this.dataLayer); 
           window.dataLayer.push(this.dataLayer);
        },
        processPayment: function() {
            swal({
                type: "warning",
                title: "Payment Processing",
                text: "Please wait while we are processing your payment.Do not Refresh page or close browser"
            })
        },
        sendCheckout: function () {

            if (this.forms.checkout.free === false){
                if(this.forms.checkout.paymentOption == 'eft' && this.instant_eft_success == false) {
                    this.forms.checkout.busy = true;
                    return;
                }
            }

            var payload = {
                terms: this.forms.checkout.terms,
                free: this.forms.checkout.free,
                card: this.forms.checkout.card,
                paymentOption: this.forms.checkout.paymentOption,
                shippingMethodId: this.forms.checkout.shippingMethod.id,
                deliveryAddressId: this.forms.checkout.deliveryAddress.id,
                donations: this.getDonations(),
            };
            this.processPayment();


            this.$http.post('/store/checkout', payload)
                .success(function (response) {
                    this.forms.checkout.busy = false;
                    swal.close();
                    if(response.invoice !== undefined){
                        this.sendTransactions(response.invoice)
                    }
                    window.location = '/dashboard/products';
                })
                .error((errors) => {
                    this.forms.checkout.busy = false;

                    if(errors.errors && errors.errors.card) {
                        swal({
                            type: 'error',
                            title: 'Whoops',
                            text: errors.errors.card
                        })
                    } else {
                        // swal.close();
                    }

                    this.forms.checkout.errors.set(errors);
                });
        },

        cancelPayment: function () {
            this.forms.checkout.busy = false;
        },

        getEftLink: function () {
            this.processPayment();

            let data = {
                store: true,
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
                  console.log(data)
                  if(data.success === true) {
                    this.process();
                    this.instant_eft_success = true;
                    this.sendCheckout();
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
                    this.sendCheckout();
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

        process: function () {
            swal({
                title: "",
                text: '<i id="busy" class="fa fa-spinner fa-pulse" style="font-size: 8em; color: #000066;"></i>',
                html: true,
                allowEscapeKey: false,
                allowOutsideClick: false,
                showCancelButton: false,
                showConfirmButton: false,
                closeOnCancel: false,
                customClass: 'no-bg'
            });
        },

        /*
         * Calculate the donation
         */
        getDonations: function() {

            var total = 0;
            if(this.forms.checkout.donations) {
                total = this.donations;
            }
            return total;
        }
    }
})
;
