Vue.component('renew-company-subscription', {
    props: {
        plan: '',
        staffs: null,
        companys: null,
        last_staff_count: ''
    },
    ready: function ready() {
        this.staff_count = this.last_staff_count;
        var index = 1;
        this.staffs.forEach(function (staff) {
            staff.number = index;
            index++;
        });
        // console.log(this.staffs)
    },

    data: function data() {
        return {
            selectedStaff: [],
            staff_count: 0,
            staff_error: false
        };
    },
    watch: {
        staff_count: function staff_count(val) {
            var _this = this;

            if (val < this.staffs.length) {
                this.selectedStaff = [];
                this.staffs.forEach(function (staff) {
                    if (staff.number <= val) {
                        _this.selectedStaff.push(staff.id);
                    }
                });
            }
        },
        selectedStaff: function selectedStaff(val) {
            this.staff_error = false;
            if (this.staff_count < this.staffs.length && (val.length < this.staff_count || val.length > this.staff_count)) {
                this.staff_error = true;
            }
        }
    },
    methods: {
        submit: function submit() {
            if (this.staff_error) {
                return false;
            }
            $('#renew_subscription').submit();
        }
    }
});