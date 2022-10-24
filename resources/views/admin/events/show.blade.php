@extends('admin.layouts.master')

@section('title', 'Event stats')
@section('description', $event->name)

@section('content')
    <event-data-grid :event="{{ $event }}" inline-template>
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
                    <button type="button" class="btn btn-xs btn-info" v-on:click="showRows = !showRows">Toggle rows</button>
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
    </event-data-grid>
@stop

@section('scripts')
<script src="/assets/themes/saaa/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop