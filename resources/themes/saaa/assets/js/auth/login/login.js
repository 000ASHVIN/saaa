Vue.component('login_web', {
    data: function () {
        return{
            forms: {
                login: $.extend(true, new AppForm({
                    email:'',
                    password: ''
                }), App.forms.login_form)
            },
        }
    },

    ready() {
        var queryString = URI(document.URL).query(true);
    },

    methods: {
        sendLogin: function () {
            this.process();
            this.$http.post('/login', this.forms.login)
                .then(function (response) {
                    location.reload();
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