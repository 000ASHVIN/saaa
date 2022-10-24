
Vue.component('support-ticket-popup', {

    props: {
        loggedin: null,
        categories: []
    },

    data: function () {
        return{
            createNew: false, 
            error: false,
            errors: {},
            categoriesdd: [],
            forms: {
                support_ticket: {
                    subject:'',
                    support_email:'',
                    mobile:'',
                    type:'',
                    tag:'',
                    description:''
                }
            }
        }
    },
    
    ready() {
        this.categoriesdd = this.categories;
        this.typeChanged();
    },

    methods: {
        
        checkForm: function() {
            
            // Validations
            this.createNew = false;
            this.error = false;
            this.errors = {};
            var support_ticket = this.forms.support_ticket;
            if(!this.loggedin) {
                
                if(support_ticket.support_email=="") {
                    this.error = true;
                    this.errors.support_email = "Email address is required";
                }
                else if(!this.validEmail(support_ticket.support_email)) {
                    this.error = true;
                this.errors.support_email = "Please enter a valid email address";
                }

                if(support_ticket.mobile=="") {
                    this.error = true;
                    this.errors.mobile = "Mobile number is required";
                }

            }
            
            if(support_ticket.subject=="") {
                this.error = true;
                this.errors.subject= 'Subject is required';
            }
            if(support_ticket.type=="") {
                // this.error = true;
                // this.errors.type= 'Mandatory type';
            }
            if(support_ticket.tag=="") {
                this.error = true;
                this.errors.tag= 'Ticket Category is required';
            }
            if(support_ticket.description=="") {
                this.error = true;
                this.errors.description= 'Description is required';
            }
            
            // If validations are proper then check email for account
            if(!this.error){

                if(!this.loggedin) {
                    self = this;
                    this.$http.post('/checklogin', { email: this.forms.support_ticket.support_email })
                    .then(function (response) {   
                        if(response.data.status == 'success')
                        { 
                            self.createNew = false;
                            $('#support_ticket_popup_form').submit();
                        }
                        else{
                            self.createNew = true;
                            $.cookie('email',self.forms.support_ticket.support_email);

                            self.error = true;
                            Vue.set(self.errors, 'support_email', 'We couldn\'t found any email record');
                        }
                    });
                }
                else {
                    self.createNew = false;
                    $('#support_ticket_popup_form').submit();
                }
            }
            return false;
               
        },

        typeChanged: function() {
            var self = this;
            var faq_type = self.forms.support_ticket.type;
            self.forms.support_ticket.tag = '';

            if(!faq_type) {
                this.categoriesdd = this.categories;
            }
            else {
                this.categoriesdd = [];
                Array.from(this.categories).forEach(category => {
                    if(category.faq_type==faq_type) {
                        this.categoriesdd.push(category);
                    }
                });
            }
        },

        validEmail: function (email) {
            var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
    }
})