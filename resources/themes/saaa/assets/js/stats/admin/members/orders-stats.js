Vue.component('orders-stats', {

    props: {
        'orders': {
            required: true
        }
    },

    ready: function () {
    },

    data: function () {
        var aggregationTypes = {none: 0, group: 1, sum: 2};
        return {
            'aggregationTypes': aggregationTypes,
            'columnConfigs': [
                {
                    'id': 's1',
                    'title': 'Invoice',
                    'aggregationType': aggregationTypes.none,
                    'dataPath': 'invoice.reference',
                    'default': 'None',
                    'sortOrder': 0
                },
                {
                    'id': 's2',
                    'title': 'Invoice Status',
                    'aggregationType': aggregationTypes.group,
                    'dataPath': 'invoice.status',
                    'default': 'None',
                    'sortOrder': 1
                },
                {
                    'id': 's3',
                    'title': 'Shipping Method',
                    'aggregationType': aggregationTypes.group,
                    'dataPath': 'shipping_method.title',
                    'default': 'None',
                    'sortOrder': 2
                },
                {
                    'id': 's4',
                    'title': 'Shipping Status',
                    'aggregationType': aggregationTypes.group,
                    'dataPath': 'shipping_information.status.title',
                    'default': 'None',
                    'sortOrder': 3
                },
                {
                    'id': 's5',
                    'title': 'Total',
                    'aggregationType': aggregationTypes.sum,
                    'dataPath': 'invoice.total',
                    'default': 0,
                    'sortOrder': 4
                },
                {
                    'id': 's6',
                    'title': 'Due',
                    'aggregationType': aggregationTypes.sum,
                    'dataPath': 'invoice.balance',
                    'default': 0,
                    'sortOrder': 5
                },
                {
                    'id': 's7',
                    'title': 'Date Ordered',
                    'aggregationType': aggregationTypes.none,
                    'dataPath': 'created_at',
                    'default': 'Unknown',
                    'sortOrder': 6
                }
            ],
            columnsFilters: {},
            showRows: false
        };
    },

    computed: {
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
            return headings;
        },
        rows: function () {
            var vm = this;
            var rows = [];
            _.forEach(vm.orders, function (order, orderKey) {
                var row = {'columns': []};
                _.forEach(vm.columnConfigs, function (columnConfig, columnKey) {
                    var data = vm.getNestedProperty(order, columnConfig.dataPath, columnConfig.default);
                    row.columns.push({
                        'id': columnConfig.id,
                        'title': columnConfig.title,
                        'data': data,
                        'sortOrder': columnConfig.sortOrder,
                        'aggregationType': columnConfig.aggregationType
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
