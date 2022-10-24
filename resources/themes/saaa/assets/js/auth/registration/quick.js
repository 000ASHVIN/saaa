Vue.component('reg_web', {
    data: function () {
        return{
            forms: {
                register_web: $.extend(true, new AppForm({
                    first_name:'',
                    last_name: '',
                    id_number: '',
                    email: '',
                    cell: '',
                }), App.forms.register_web)
            },
        }
    },

    ready() {
        var queryString = URI(document.URL).query(true);
    },

    methods: {
        sendWebReg: function () {
            this.process();
            this.$http.post('/auth/register', this.forms.register_web)
                .then(function (response) {
                    // location.reload();
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