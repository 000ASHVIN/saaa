/**
 * Text field input component for Bootstrap.
 */
Vue.component('app-text', {
    props: ['display', 'form', 'name', 'input'],

    template: '<div class="form-group" :class="{\'has-error\': form.errors.has(name)}">\
    <label class="col-md-4 control-label">{{ display }}</label>\
    <div class="col-md-6">\
        <input type="text" class="form-control" v-model="input">\
        <span class="help-block" v-show="form.errors.has(name)">\
            <strong>{{ form.errors.get(name) }}</strong>\
        </span>\
    </div>\
</div>'
});


/**
 * E-mail field input component for Bootstrap.
 */
Vue.component('app-email', {
    props: ['display', 'form', 'name', 'input'],

    template: '<div class="form-group" :class="{\'has-error\': form.errors.has(name)}">\
    <label class="col-md-4 control-label">{{ display }}</label>\
    <div class="col-md-6">\
        <input type="email" class="form-control" v-model="input">\
        <span class="help-block" v-show="form.errors.has(name)">\
            <strong>{{ form.errors.get(name) }}</strong>\
        </span>\
    </div>\
</div>'
});


/**
 * Password field input component for Bootstrap.
 */
Vue.component('app-password', {
    props: ['display', 'form', 'name', 'input'],

    template: '<div class="form-group" :class="{\'has-error\': form.errors.has(name)}">\
    <label class="col-md-4 control-label">{{ display }}</label>\
    <div class="col-md-6">\
        <input type="password" class="form-control" v-model="input">\
        <span class="help-block" v-show="form.errors.has(name)">\
            <strong>{{ form.errors.get(name) }}</strong>\
        </span>\
    </div>\
</div>'
});

/**
 * Date field input component for Bootstrap.
 */
Vue.component('app-date', {
    props: ['display', 'form', 'name', 'input'],

    template: '<div class="form-group" :class="{\'has-error\': form.errors.has(name)}">\
        <input type="text" class="form-control is-date" v-model="input">\
        <span class="help-block" v-show="form.errors.has(name)">\
            <strong>{{ form.errors.get(name) }}</strong>\
        </span>\
</div>'
});


/**
 * Password field input component for Bootstrap.
 */
Vue.component('app-select', {
    props: ['display', 'form', 'name', 'items', 'input'],

    template: '<div class="form-group" :class="{\'has-error\': form.errors.has(name)}">\
    <label class="col-md-4 control-label">{{ display }}</label>\
    <div class="col-md-8">\
        <select class="form-control" v-model="input">\
            <option v-for="item in items" :value="item.value">\
                {{ item.text }}\
            </option>\
        </select>\
        <span class="help-block" v-show="form.errors.has(name)">\
            <strong>{{ form.errors.get(name) }}</strong>\
        </span>\
    </div>\
</div>'
});
