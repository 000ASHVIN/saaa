Vue.filter('filterBySelectedListingsAndTitle', function (products) {
    var vm = this;
    return _.sortBy(products, function (product) {
        var found = _.find(vm.selectedProducts, function (selectedProductId, index) {
            return selectedProductId == product.id;
        });
        return ((found ? 0 : 1) + product.title + product.listings.length);
    })
});
Vue.component('store-listings-admin-add-product', {

    props: ['products', 'selectedProducts'],

    ready: function () {
    },

    data: function () {
        return {
            search: ''
        };
    },

    computed: {},

    methods: {
        toggleSelectProduct: function (product) {
            var vm = this;
            var found = _.find(vm.selectedProducts, function (productId, index) {
                return productId == product.id;
            });
            if (found) {
                vm.selectedProducts.$remove(product.id);
            }
            else {
                vm.selectedProducts.push(product.id);
            }
        }
    }
});
