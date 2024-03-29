/**
 * AppForm helper class. Used to set common properties on all forms.
 */
window.AppForm = function (data) {
    var form = this;

    $.extend(this, data);

    this.errors = new AppFormErrors();
    this.busy = false;
    this.successful = false;

    this.startProcessing = function () {
        form.errors.forget();
        form.errors.forget();
        form.busy = true;
        form.successful = false;
    };

    this.finishProcessing = function () {
        form.busy = false;
        form.successful = true;
    };
};
