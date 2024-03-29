/*
 * Common Error Display Component.
 */
Vue.component('app-errors', {
    props: ['form'],

    template: "<div><div class='alert alert-danger' v-if='form.errors && form.errors.hasErrors()'>\
                <strong>Whoops!</strong> There were some problems with your input.<br><br>\
                <ul>\
                    <li v-for='error in form.errors.flatten()'>\
                        {{ error }}\
                    </li>\
                </ul>\
            </div></div>"
});


Vue.component('app-error-alert', {
    props: ['form'],

    template: "<div><div class='alert alert-danger' v-if='form.errors && form.errors.hasErrors()'>\
                <strong>Whoops!</strong> There were some problems with your input.\
            </div></div>"
});