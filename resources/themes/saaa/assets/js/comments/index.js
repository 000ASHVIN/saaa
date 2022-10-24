import wysiwyg from "vue-wysiwyg";


Vue.component('ticket-replies', {
    components:wysiwyg,
    props: {
        user: null,
        thread: null
    },

    data: function () {
        return {
            forms: {
                ticket_reply: $.extend(true, new AppForm({
                    busy: false,
                    description: ''
                }), App.forms.ticket_reply),
            },
        }
    },
});