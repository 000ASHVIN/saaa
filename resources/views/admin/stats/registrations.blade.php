@extends('admin.layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-daterangepicker/daterangepicker.css"/>
@endsection

@section('title', 'Stats')
@section('description', 'Event Registrations')

@section('content')
    <br>
    <div class="row">
        <div class="col-md-12">
            <label for="date-range">
                Date range: <input id="date-range" type="text">
            </label>
            <registrations-stats :registrations="{{ $registrations->toJson() }}" inline-template>
                <br>
                <div class="well" style="padding-bottom: 14px;">
                    <div v-for="(statKey, stat) in stats" class="row">
                        <div class="col-sm-12">
                            <strong>@{{ stat.title }}</strong>:&nbsp;
                    <span v-if="stat.type == aggregationTypes.group"
                          v-for="(groupKey, group) in stat.groups | orderBy 'count' -1">
                        <button style="margin-bottom: 5px;"
                                v-bind:class="{'btn-default': !group.active, 'btn-success': group.active}"
                                v-on:click="toggleFilterActive(statKey,groupKey)"
                                class="btn btn-xs"
                                type="button">
                            @{{ group.data }} <span class="badge">@{{ group.count }}</span>
                        </button>&nbsp;
                    </span>
                    <span v-if="stat.type == aggregationTypes.sum" style="line-height: 22px; margin-bottom: 5px;">
                        @{{ stat.sum | currency 'R ' }}
                    </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <strong>Count:&nbsp;@{{ rows.length }}</strong>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button type="button" class="btn btn-xs btn-danger" v-on:click="clearAllFilters()">Clear all
                                filters
                            </button>
                            <button type="button" class="btn btn-xs btn-info" v-on:click="showRows = !showRows">Toggle
                                rows
                            </button>
                        </div>
                    </div>
                </div>
                <table v-if="showRows" class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th v-for="heading in headings | orderBy 'sortOrder'">@{{ heading.title }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in rows">
                        <td v-for="column in row.columns | orderBy 'sortOrder'">
                            @{{ column.data }}
                        </td>
                    </tr>
                    </tbody>
                </table>

            </registrations-stats>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/assets/admin/vendor/moment/moment.min.js"></script>
    <script type="text/javascript" src="/assets/admin/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/js/app.js"></script>
    <script>
        var getUrlParameter = function getUrlParameter(sParam) {
            var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

            for (i = 0; i < sURLVariables.length; i++) {
                sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] === sParam) {
                    return sParameterName[1] === undefined ? true : sParameterName[1];
                }
            }
        };
        jQuery(document).ready(function () {
            Main.init();
            var options = {};
            var startDate = getUrlParameter('start-date');
            var endDate = getUrlParameter('end-date');
            if (startDate) {
                options['startDate'] = moment(startDate);
            }
            if (endDate) {
                options['endDate'] = moment(endDate);
            }
            $('#date-range').daterangepicker(options);
            $('#date-range').on('apply.daterangepicker', function (event, picker) {
                var newUrl = '/admin/stats/registrations?start-date=' + picker.startDate.format('YYYY-MM-DD') + '&end-date=' + picker.endDate.format('YYYY-MM-DD');
                window.location.href = newUrl;
            });
        });
    </script>
@endsection