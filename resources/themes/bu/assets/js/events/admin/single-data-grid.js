Vue.component('event-data-grid', {

    props: ['event'],

    ready: function () {
    },

    data: function () {
        var aggregationTypes = {none: 0, group: 1, sum: 2};
        return {
            'aggregationTypes': aggregationTypes,
            'columnConfigs': [
                {
                    'id': 's1',
                    'title': 'Venue',
                    'aggregationType': aggregationTypes.group,
                    'ticketDataPath': 'venue.name',
                    'default': 'None',
                    'sortOrder': 0
                },
                {
                    'id': 's2',
                    'title': 'Pricing',
                    'aggregationType': aggregationTypes.group,
                    'ticketDataPath': 'pricing.name',
                    'default': 'None',
                    'sortOrder': 1
                },
                {
                    'id': 's3',
                    'title': 'Days',
                    'aggregationType': aggregationTypes.group,
                    'ticketDataPath': 'dates',
                    'default': 'None',
                    'sortOrder': 2
                },
                {
                    'id': 's4',
                    'title': 'Subscription',
                    'aggregationType': aggregationTypes.group,
                    'ticketDataPath': 'user.subscription.plan.name',
                    'default': 'None',
                    'sortOrder': 3
                },
                {
                    'id': 's5',
                    'title': 'Invoice Number',
                    'aggregationType': aggregationTypes.none,
                    'ticketDataPath': 'invoice.reference',
                    'default': 'None',
                    'sortOrder': 4
                },
                {
                    'id': 's6',
                    'title': 'Invoice Status',
                    'aggregationType': aggregationTypes.group,
                    'ticketDataPath': 'invoice.status',
                    'default': 'None',
                    'sortOrder': 5
                },
                {
                    'id': 's7',
                    'title': 'Dietary requirements',
                    'aggregationType': aggregationTypes.group,
                    'ticketDataPath': 'dietary_requirement.name',
                    'default': 'None',
                    'sortOrder': 6
                },
                {
                    'id': 's8',
                    'title': 'Email',
                    'aggregationType': aggregationTypes.none,
                    'ticketDataPath': 'user.email',
                    'default': 'None',
                    'sortOrder': 7
                },
                {
                    'id': 's9',
                    'title': 'Name',
                    'aggregationType': aggregationTypes.none,
                    'ticketDataPath': 'user.first_name',
                    'default': 'None',
                    'sortOrder': 8
                },
                {
                    'id': 's10',
                    'title': 'Last name',
                    'aggregationType': aggregationTypes.none,
                    'ticketDataPath': 'user.last_name',
                    'default': 'None',
                    'sortOrder': 9
                },
                {
                    'id': 's11',
                    'title': 'Cell',
                    'aggregationType': aggregationTypes.none,
                    'ticketDataPath': 'user.cell',
                    'default': 'None',
                    'sortOrder':10
                },
                // {
                //     'id': 's12',
                //     'title': 'Company',
                //     'aggregationType': aggregationTypes.none,
                //     'ticketDataPath': 'user.profile.company',
                //     'default': 'None',
                //     'sortOrder': 11
                // },
                {
                    'id': 's13',
                    'title': 'Total',
                    'aggregationType': aggregationTypes.sum,
                    'ticketDataPath': 'invoice.total',
                    'default': 0,
                    'sortOrder': 12
                },
                {
                    'id': 's14',
                    'title': 'Due',
                    'aggregationType': aggregationTypes.sum,
                    'ticketDataPath': 'invoice.balance',
                    'default': 0,
                    'sortOrder': 13
                },
                // {
                //     'id': 's15',
                //     'title': 'Purchased',
                //     'aggregationType': aggregationTypes.none,
                //     'ticketDataPath': 'created_at',
                //     'default': 'None',
                //     'sortOrder': 14
                // }
            ],
            extrasColumnsSortStart: 14,
            columnsFilters: {},
            showRows: false,
            search: '' //TODO
        };
    },

    computed: {
        extras: function () {
            var vm = this;
            var extras = {};
            _.forEach(vm.event.tickets, function (ticket, ticketKey) {
                var ticketExtras = ticket.extras;
                _.forEach(ticketExtras, function (extra, extraKey) {
                    if (!extras[extra.id]) {
                        Vue.set(extras, extra.id, {
                            'id': 'e' + extra.id,
                            'title': extra.name,
                            'sortOrder': vm.extrasColumnsSortStart + extras.length
                        });
                    }
                });
            });
            return extras;
        },
        headings: function () {
            var vm = this;
            var headings = [];
            _.forEach(vm.columnConfigs, function (columnConfig, columnKey) {
                headings.push({
                    'id': columnConfig.id,
                    'title': columnConfig.title,
                    'sortOrder': columnConfig.sortOrder,
                    'aggregationType': columnConfig.aggregationType
                });
            });
            _.forEach(vm.extras, function (extra, extraKey) {
                headings.push({
                    'id': extra.id,
                    'title': extra.title,
                    'sortOrder': extra.sortOrder,
                    'aggregationType': vm.aggregationTypes.group
                });
            });
            return headings;
        },
        rows: function () {
            var vm = this;
            var rows = [];
            _.forEach(vm.event.tickets, function (ticket, ticketKey) {
                var row = {'columns': []};
                _.forEach(vm.columnConfigs, function (columnConfig, columnKey) {
                    var data = vm.getNestedProperty(ticket, columnConfig.ticketDataPath, columnConfig.default);
                    row.columns.push({
                        'id': columnConfig.id,
                        'title': columnConfig.title,
                        'isExtra': false,
                        'data': data,
                        'sortOrder': columnConfig.sortOrder,
                        'aggregationType': columnConfig.aggregationType
                    });
                });
                //Extras
                _.forEach(vm.extras, function (extra, extraKey) {
                    var hasExtra = _.find(ticket.extras, function (comparisonExtra) {
                        return extra.id == 'e' + comparisonExtra.id
                    });
                    row.columns.push({
                        'id': extra.id,
                        'title': extra.title,
                        'isExtra': true,
                        'data': hasExtra ? "Yes" : "No",
                        'sortOrder': extra.sortOrder,
                        'aggregationType': vm.aggregationTypes.group
                    });
                });
                //Check filters
                var validColumns = 0;
                _.forEach(row.columns, function (column, columnKey) {
                    var isValidColumn = false;
                    var foundFilters = 0;
                    _.forEach(vm.columnsFilters, function (columnFilters, columnFilterKey) {
                        if (columnFilters.length > 0 && column.id == columnFilterKey) {
                            foundFilters++;
                            _.forEach(columnFilters, function (filter, filterKey) {
                                if (filter == column.data)
                                    isValidColumn = true;
                            })
                        }
                    });
                    if (!foundFilters)
                        isValidColumn = true;
                    if (isValidColumn)
                        validColumns++;
                });
                if (validColumns == row.columns.length)
                    rows.push(row);
            });
            return rows;
        },
        stats: function () {
            var vm = this;
            var stats = {};
            _.forEach(vm.rows, function (row, rowKey) {
                _.forEach(row.columns, function (column, columnKey) {
                    if (column.aggregationType == vm.aggregationTypes.none)
                        return false;
                    //Groups
                    if (column.aggregationType == vm.aggregationTypes.group) {
                        //Check if the column has stats yet
                        if (!stats[column.id]) {
                            Vue.set(stats, column.id, {
                                'title': column.title,
                                'groups': {},
                                'type': vm.aggregationTypes.group
                            });
                        }
                        //Check if there is an entry for the column's data in the stats
                        if (!stats[column.id]['groups'][column.data]) {
                            Vue.set(stats[column.id]['groups'], column.data, {
                                'id': column.id,
                                'data': column.data,
                                'count': 0,
                                'active': vm.isActiveFilter(column.id, column.data)
                            });
                        }

                        stats[column.id]['groups'][column.data].count++;
                    }
                    else if (column.aggregationType == vm.aggregationTypes.sum) {
                        if (!stats[column.id]) {
                            Vue.set(stats, column.id, {
                                'title': column.title,
                                'sum': 0.00,
                                'type': vm.aggregationTypes.sum
                            });
                        }

                        stats[column.id]['sum'] += parseFloat(column.data);
                    }
                })
            });
            return stats;
        }
    },

    methods: {
        isInt: function (value) {
            return !isNaN(value) &&
                parseInt(Number(value)) == value && !isNaN(parseInt(value, 10));
        },
        clearAllFilters: function () {
            var vm = this;
            Vue.set(vm, 'columnsFilters', {});
        },
        isActiveFilter: function (statKey, groupKey) {
            var vm = this;
            var activeFilters = vm.getNestedProperty(vm.columnsFilters, statKey, false);
            if (!activeFilters)
                return false;
            else
                return _.find(activeFilters, function (filter) {
                    return filter == groupKey;
                })
        },
        toggleFilterActive: function (statKey, groupKey) {
            var vm = this;
            var myColumnFilters = vm.getNestedProperty(vm.columnsFilters, statKey, false);
            if (myColumnFilters) {
                var myFilter = _.find(myColumnFilters, function (filter) {
                    return filter == groupKey;
                });
                if (myFilter)
                    myColumnFilters.$remove(groupKey);
                else
                    myColumnFilters.push(groupKey);
            }
            else {
                Vue.set(vm.columnsFilters, statKey, [groupKey]);
            }
        },
        findColumnConfigById: function (id) {
            var vm = this;
            return _.find(vm.columnConfigs, function (columnConfig) {
                if (columnConfig.id == id)
                    return true;
            })
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
