
    Vue.component('auth-login', {
        data: function () {
        return{
            isSuccess:false,
            isEmailExist: false,
            createNew: false,
            hidePassword:false
        }
    },


    ready() {

        if (typeof $.cookie('plan') !== 'undefined' && $.cookie('plan')!="")
        {
            this.hidePassword=true;
        }

    },

    methods: {
        checkemail: function() {
            let self=this;
            if(!self.isSuccess){
            self.$http.post('/checklogin', self.forms.login)
                .then(function (response) {
                 if(response.data.status == 'success')
                 {
                   $('#login_form').attr('onSubmit','');
                    self.isEmailExist =  true;
                    self.isSuccess =  true;
                    self.createNew =  false;
                 }
                //  else if(response.data.status == 'error')
                //  {
                //     self.createNew =  true;
                //     $.cookie('email',self.forms.login.email);
                //     window.location = '/auth/register';
                //  }
                 else{
                    self.createNew =  true;
                    $.cookie('email',self.forms.login.email);
                  //  window.location = '/auth/register';
                 }
                //  window.location = '/auth/register';
                });
            }else{
                $('#login_form').submit();
                $.cookie('email',"");
            }

        },
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
