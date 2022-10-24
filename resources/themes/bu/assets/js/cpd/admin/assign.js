Vue.filter('searchAndFilterAttendees', function (users, rawSearch) {
    var vm = this;
    var search = rawSearch.trim().toLowerCase();
    var filteredUsers = [];
    _.forEach(users, function (user, key) {
        var validSearch = false;
        var fullName = (user.first_name.trim() + ' ' + user.last_name.trim()).toLowerCase();
        user.full_name = fullName;

        //Search
        if (fullName.indexOf(search) > -1 ||
            user.email.trim().toLowerCase().indexOf(search) > -1 ||
            user.code.trim().toLowerCase().indexOf(search) > -1
        ) validSearch = true;

        //Filter
        var validFilters = false;
        var matchedFilters = 0;
        var totalFilters = 0;
        _.forOwn(vm.filters, function (filter, filterKey) {
                totalFilters++;
                if (filter.selected == filter.options.Any) {
                    matchedFilters++;
                }
                else {
                    var filterCheckAgainst = vm.getNestedProperty(user, filter.on, null);
                    if (filterCheckAgainst == filter.selected)
                        matchedFilters++;
                }
            }
        );

        if (matchedFilters == totalFilters)
            validFilters = true;

        if (validSearch && validFilters)
            filteredUsers.push(user);
    });
    return filteredUsers;
});
Vue.filter('searchSelectedAttendees', function (users, rawSearch) {
    var vm = this;
    var search = rawSearch.trim().toLowerCase();
    var filteredUsers = [];
    _.forEach(users, function (user, key) {
        var validSearch = false;
        var fullName = (user.first_name.trim() + ' ' + user.last_name.trim()).toLowerCase();
        user.full_name = fullName;

        //Search
        if (fullName.indexOf(search) > -1 ||
            user.email.trim().toLowerCase().indexOf(search) > -1 ||
            user.code.trim().toLowerCase().indexOf(search) > -1
        ) filteredUsers.push(user);
    });
    return filteredUsers;
});
Vue.component('assign-cpd', {

    props: ['events'],

    ready: function () {
        var vm = this;
        $(document).on('change', '.btn-file :file', function () {
            var input = $(this);
            var file = input.get(0).files[0];
            Papa.parse(file, {
                header: true,
                complete: function (results) {
                    vm.processCSVRows(results.data);
                }
            });
            input.val("");
        });
    },

    data: function () {
        var ajaxStates = {
            none: 0,
            busy: 1,
            success: 2,
            error: 3
        };
        return {
            ajaxStates: ajaxStates,
            fetchState: ajaxStates.none,
            saveState: ajaxStates.none,
            selectedEventId: 0,
            selectedVenueId: 0,
            search: '',
            selectedSearch: '',
            sortField: 'full_name',
            attendees: [],
            selectedAttendees: [],
            filters: {
                'invoice': {
                    'on': 'invoice.status',
                    'title': 'Invoice',
                    'options': {
                        'Any': -1,
                        'Free': null,
                        'Unpaid': 'unpaid',
                        'Paid': 'paid',
                        'Cancelled': 'cancelled'
                    }
                    ,
                    'selected': -1
                }
                ,
                'attendance': {
                    'on': 'attended',
                    'title': 'Attendance',
                    'options': {
                        'Any': -1,
                        'Unattended': 0,
                        'Attended': 1
                    }
                    ,
                    'selected': -1
                }
            }
        };
    },

    computed: {
        selectedEvent: function () {
            return this.getSelectedEvent();
        }
    },

    methods: {
        processCSVRows: function (rows) {
            var vm = this;
            var foundCount = 0;
            var notFound = [];
            var blanks = 0;
            _.forEach(rows, function (row, rowKey) {
                var attendee = vm.findAttendeeByCodeEmailName(row.code, row.email, row.first_name, row.last_name);
                if (attendee) {
                    vm.addToSelected(attendee.id);
                    foundCount++;
                }
                else {
                    if (
                        row.code && row.code.trim() != "" ||
                        row.email && row.email.trim() != "" ||
                        row.first_name && row.first_name.trim() != "" ||
                        row.last_name && row.last_name.trim() != ""
                    )
                        notFound.push(row);
                    else
                        blanks++;
                }
            });
            swal({
                title: "Select from CSV",
                html: true,
                text: "<p><strong>"
                + foundCount + "</strong> out of <strong>" + (rows.length - blanks) + "</strong> attendees selected from CSV file." +
                "</p>",
                type: "success",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Download extra " + notFound.length + " attendees",
                cancelButtonText: "Close",
                closeOnConfirm: false,
                closeOnCancel: true
            }, function (isConfirm) {
                if (isConfirm) {
                    vm.downloadExtraRowsFromCSV(notFound);
                    swal.close();
                }
            });
        },
        downloadExtraRowsFromCSV: function (extraRows) {
            var csv = Papa.unparse(extraRows, {
                quotes: true,
                delimiter: ",",
                newline: "\r\n"
            });
            var csvData = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
            var csvURL = null;
            if (navigator.msSaveBlob) {
                csvURL = navigator.msSaveBlob(csvData, 'extra-rows.csv');
            } else {
                csvURL = window.URL.createObjectURL(csvData);
            }
            var tempLink = document.createElement('a');
            tempLink.href = csvURL;
            tempLink.setAttribute('download', 'extra-rows.csv');
            tempLink.click();
        },
        findAttendeeByCodeEmailName: function (code, email, first_name, last_name) {
            var vm = this;
            var found = false;
            var attendee = null;
            if (code && code.trim() != "") {
                attendee = _.find(vm.attendees, {code: code});
                if (attendee)
                    found = true;
            }
            if (!found && email && email.trim() != "") {
                attendee = _.find(vm.attendees, {email: email});
                if (attendee)
                    found = true;
            }
            if (!found && first_name && first_name.trim() != "" && last_name && last_name.trim() != "") {
                attendee = _.find(vm.attendees, {first_name: first_name, last_name: last_name});
            }

            return attendee;
        },
        clearAttendeeSearch: function () {
            var vm = this;
            vm.search = '';
        },
        clearSelectedAttendeeSearch: function () {
            var vm = this;
            vm.selectedSearch = '';
        },
        addAllFilteredAttendeesToSelected: function () {
            var vm = this;
            var attendees = _.clone(vm.attendees);
            _.forEach(attendees, function (attendee, key) {
                vm.attendees.$remove(attendee);
                vm.selectedAttendees.push(attendee);
            });
        },
        clearAndResetAllSelectedAttendees: function () {
            var vm = this;
            var selectedAttendees = _.clone(vm.selectedAttendees);
            _.forEach(selectedAttendees, function (attendee, key) {
                vm.selectedAttendees.$remove(attendee);
                vm.resetAttendeeValuesToOriginalValues(attendee);
                vm.attendees.push(attendee);
            });
        },
        save: function () {
            var vm = this;
            vm.saveState = vm.ajaxStates.busy;
            vm.$http({
                url: '/admin/event/' + vm.selectedEventId + '/venue/' + vm.selectedVenueId + '/attendees/save/',
                method: 'POST',
                data: {
                    'attendees': vm.selectedAttendees
                }
            }).then(
                function (response) {
                    vm.saveState = vm.ajaxStates.success;
                    swal('Success', 'Attendees have been updated.', 'success');
                    vm.fetchAttendees();
                },
                function (response) {
                    vm.saveState = vm.ajaxStates.error;
                    swal('Error', 'Something went wrong, please check your internet and try again.', 'error');
                    console.log('error', response);
                }
            );
        },
        cancelAndDeleteAllSelected: function () {
            var vm = this;
            _.forEach(vm.selectedAttendees, function (attendee, key) {
                attendee.marked_for_deletion = true;
                if (attendee.invoice != null)
                    attendee.invoice.status = 'cancelled';
            });
        },
        markAllSelectedAsAttended: function () {
            var vm = this;
            _.forEach(vm.selectedAttendees, function (attendee, key) {
                attendee.attended = true;
            })
        },
        markAllSelectedAsUnattended: function () {
            var vm = this;
            _.forEach(vm.selectedAttendees, function (attendee, key) {
                attendee.attended = false;
            })
        },
        addToSelected: function (attendeeId) {
            var vm = this;
            var attendee = _.find(vm.attendees, {id: attendeeId});
            if (attendee) {
                vm.attendees.$remove(attendee);
                vm.selectedAttendees.push(attendee);
            }
        },
        removeFromSelected: function (attendeeId) {
            var vm = this;
            var attendee = _.find(vm.selectedAttendees, {id: attendeeId});
            if (attendee) {
                vm.selectedAttendees.$remove(attendee);
                vm.resetAttendeeValuesToOriginalValues(attendee);
                vm.attendees.push(attendee);
            }
        },
        resetAttendeeValuesToOriginalValues: function (attendee) {
            attendee.marked_for_deletion = attendee.original_values.marked_for_deletion;
            attendee.attended = attendee.original_values.attended;
            if (attendee.invoice != null)
                attendee.invoice.status = attendee.original_values.invoice.status;
        },
        fetchAttendees: function () {
            var vm = this;
            vm.attendees = [];
            vm.selectedAttendees = [];
            vm.fetchState = vm.ajaxStates.busy;
            vm.$http({
                url: '/admin/event/' + vm.selectedEventId + '/venue/' + vm.selectedVenueId + '/attendees',
                method: 'GET'
            }).then(
                function (response) {
                    vm.fetchState = vm.ajaxStates.success;
                    vm.attendees = response.data;
                    vm.setupAttendees();
                },
                function (response) {
                    vm.fetchState = vm.ajaxStates.error;
                    console.log('error', response);
                }
            );
        },
        setupAttendees: function () {
            var vm = this;
            _.forEach(vm.attendees, function (attendee, key) {
                Vue.set(attendee, 'marked_for_deletion', false);
                Vue.set(attendee, 'original_values', (JSON.parse(JSON.stringify(attendee))));
            });
        },
        changeEventSelection: function () {
            this.selectedVenueId = 0;
        },
        changeVenueSelection: function () {
            if (this.selectedVenueId > 0)
                this.fetchAttendees();
        },
        getEventById: function (id) {
            return _.find(this.events, {id: id});
        },
        getSelectedEvent: function () {
            return this.getEventById(this.selectedEventId);
        },
        getVenueById: function (id) {
            return _.find(this.selectedEvent.venues, {id: id});
        },
        getNestedProperty: function (obj, prop, defaultValue) {
            var vm = this;
            if (typeof obj === 'undefined' || obj == null) {
                return defaultValue;
            }

            var _index = prop.indexOf('.');
            if (_index > -1) {
                return vm.getNestedProperty(obj[prop.substring(0, _index)], prop.substr(_index + 1), defaultValue);
            }

            return obj[prop];
        }
    }
});
