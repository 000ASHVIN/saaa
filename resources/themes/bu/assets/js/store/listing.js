Vue.component('app-store-listing-product-selection', {
    props: ['listing', 'discounts', 'cartItems', 'selectedProductId'],
    ready: function () {
        var vm = this;
        this.generateDetailedTitleForProductListing();
        this.removeBackSlashesFromDiscountModels();
        this.generateDiscountedPrices();
        if (vm.selectedProductId > 0)
            this.setSelectedProductFromId(vm.selectedProductId);
    },
    data: function () {
        return {
            prospectiveDiscounts: ['AppStoreDiscountsAllListingProductsDiscount'],
            selectedProductListing: 0,
            totals: {
                price: this.calculateTotalProductsPrice(),
                tags: this.compileUniqueTags(),
                discounted_price: this.calculateTotalDiscountedPrice(),
                cpd_hours: this.calculateTotalCPDHours()
            }
        };
    },
    computed: {
        showCourierFeeNotice: function () {
            var show = false;
            if (
                (this.selectedProductListing.product && this.selectedProductListing.product.is_physical) ||
                (this.selectedProductListing == 'all' && this.listing.hasPhysicalProduct)
            )
                show = true;
            return show;
        }
    },
    methods: {
        setSelectedProductFromId: function (selectedProductId) {
            var vm = this;
            var found = _.find(vm.listing.product_listings, function (product_listing, product_listing_key) {
                if (!product_listing.product)
                    return false;

                return product_listing.product.id == selectedProductId;
            });
            if (found)
                vm.selectedProductListing = found;
        },
        submitForm: function (event) {
            $('#add-to-cart-form').submit();
        },
        discounted: function (amount, discount, discount_type) {
            return parseFloat(amount - this.discount(amount, discount, discount_type));
        },
        discount: function (amount, discount, discount_type) {
            if (discount_type == 'percentage')
                return parseFloat(amount * (discount / 100));
            else
                return discount;
        },
        calculateTotalProductsPrice: function () {
            var totalPrice = 0;
            _.forEach(this.listing.product_listings, function (productListing, key) {
                totalPrice += parseFloat(productListing.product.price);
            });
            return totalPrice;
        },
        calculateTotalProductsDiscount: function () {
            var that = this;
            var totalDiscount = 0;
            _.forEach(this.listing.product_listings, function (productListing, key) {
                if (productListing.discount > 0) {
                    totalDiscount += that.discount(productListing.product.price, productListing.discount, productListing.discount_type);
                }
            });
            return totalDiscount;
        },
        calculateTotalDiscountedPrice: function () {
            var that = this;
            var totalPrice = this.calculateTotalProductsPrice();
            var totalDiscount = this.calculateTotalProductsDiscount();
            var totalDiscountedProductsPrice = (totalPrice - totalDiscount);
            var totalDiscountedPrice = totalDiscountedProductsPrice;
            if (this.listing.discount > 0) {
                totalDiscountedPrice -= this.discount(totalDiscountedProductsPrice, this.listing.discount, this.listing.discount_type);
            }

            _.forEach(that.discounts, function (discount, key) {
                if (that.discountModelWithoutBackSlashes(discount.model) == 'AppStoreDiscountsAllListingProductsDiscount') {
                    if (discount.value > 0)
                        totalDiscountedPrice -= that.discount(totalDiscountedPrice, discount.value, discount.type);
                    return false;
                }
            });

            return totalDiscountedPrice;
        },
        calculateTotalCPDHours: function () {
            var totalCPDHours = 0;
            _.forEach(this.listing.product_listings, function (productListing, key) {
                totalCPDHours += productListing.product.cpd_hours;
            });
            return totalCPDHours;
        },
        compileUniqueTags: function () {
            var uniqueTags = [];
            _.forEach(this.listing.product_listings, function (productListing, key) {
                var tags = productListing.product.tags;
                _.forEach(tags, function (tag, key) {
                    var notFound = true;
                    _.forEach(uniqueTags, function (uniqueTag, key) {
                        if (uniqueTag.id === tag.id) {
                            notFound = false;
                            return false;
                        }
                    });
                    if (notFound)
                        uniqueTags.push(tag);
                });
            });
            return uniqueTags;
        },
        generateDetailedTitleForProductListing: function () {
            var that = this;
            _.forEach(this.listing.product_listings, function (productListing, key) {
                var product = productListing.product;
                //title
                var detailedTitle = product.title;

                //topic
                if (product.topic.trim() != "")
                    detailedTitle = product.topic + ": " + detailedTitle;

                //price
                //detailedTitle += " - R" + product.price;

                //discount
                if (productListing.discount > 0) {
                    if (productListing.discount_type == 'percentage')
                        detailedTitle += " [-" + productListing.discount + "%]";
                    else
                        detailedTitle += " [-" + productListing.discount + "]";
                }
                //year
                detailedTitle += " (" + product.year + ")";

                //In cart count
                var optionTitle = detailedTitle;
                _.forEach(that.cartItems, function (cartItem, key) {
                    if (cartItem.id == productListing.id) {
                        optionTitle += " | " + cartItem.qty + " in cart";
                        return false;
                    }
                });

                optionTitle += ' | ' + productListing.product.stock + ' in stock';

                Vue.set(productListing, 'detailedTitle', detailedTitle);
                Vue.set(productListing, 'optionTitle', optionTitle);
            });
        },
        generateDiscountedPrices: function () {
            var that = this;
            _.forEach(that.listing.product_listings, function (productListing, key) {
                var discountedPrice = productListing.product.price;
                discountedPrice -= that.discount(discountedPrice, productListing.discount, productListing.discount_type);
                discountedPrice -= that.discount(discountedPrice, productListing.listing.discount, productListing.listing.discount_type);
                _.forEach(productListing.qualifyingGlobalDiscounts, function (globalDiscount, globalDiscountKey) {
                    discountedPrice -= that.discount(discountedPrice, globalDiscount.value, globalDiscount.type);
                });
                Vue.set(productListing, 'discountedPrice', discountedPrice);
            });
        },
        removeBackSlashesFromDiscountModels: function () {
            var that = this;
            _.forEach(this.discounts, function (discount, key) {
                discount.model = that.discountModelWithoutBackSlashes(discount.model);
            });
        },
        discountModelWithoutBackSlashes: function (model) {
            return model.replace(/\\/g, "");
        }
    }
});
