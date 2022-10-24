Vue.component('store-admin-index', {

    props: ['orders', 'shippingInformationStatuses', 'provinces', 'countries'],

    ready: function () {
        var vm = this;
    },

    data: function () {
        var vm = this;
        var orders = vm.orders;
        var ajaxStates = {
            none: 0,
            busy: 1,
            success: 2,
            error: 3
        };
        return {
            search: '',
            ajaxStates: ajaxStates,
            shippingInformationUpdateState: ajaxStates.none,
            shippingInformationUpdate: {
                'tracking_code': '',
                'status': vm.shippingInformationStatuses[0]
            },
            showingShippingInformation: null,
            showModal: false,
            filteredOrders: orders
        };
    },

    computed: {},

    filters: {
        momentFromNow: function (date) {
            return moment(date).fromNow();
        }
    },

    methods: {
        showShippingInfoModal: function (shippingInformation) {
            var vm = this;
            vm.showingShippingInformation = shippingInformation;
            Vue.set(vm, 'shippingInformationUpdate', {
                'tracking_code': shippingInformation.tracking_code,
                'status': shippingInformation.status
            });
            $('#shippingInfoModal').modal('show');
        },
        updateShippingInformation: function () {
            var vm = this;
            vm.shippingInformationUpdateState = vm.ajaxStates.busy;
            vm.$http({
                url: '/admin/shipping/' + vm.showingShippingInformation.id + '/update',
                method: 'POST',
                data: {
                    'status_id': vm.shippingInformationUpdate.status.id,
                    'tracking_code': vm.shippingInformationUpdate.tracking_code
                }
            }).then(
                function (response) {
                    vm.shippingInformationUpdateState = vm.ajaxStates.success;
                    vm.showingShippingInformation.status = vm.shippingInformationUpdate.status;
                    vm.showingShippingInformation.tracking_code = vm.shippingInformationUpdate.tracking_code;
                    $('#shippingInfoModal').modal('hide');
                    swal({
                        'type': 'success',
                        'title': 'Success',
                        'text': 'Shipping information updated.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                function (response) {
                    vm.shippingInformationUpdateState = vm.ajaxStates.error;
                    swal('Error', 'Something went wrong, please check your internet and try again.', 'error');
                    console.log('error', response);
                }
            );
        }
    }
});
