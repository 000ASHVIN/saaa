<div class="btn-group">
    <button type="button" class="btn btn-default dropdown-toggle"
            data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
        Action <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <li><a v-on:click.prevent="markAllSelectedAsAttended" href="#">Mark
                as attended</a></li>
        <li><a v-on:click.prevent="markAllSelectedAsUnattended"
               href="#">Mark as unattended</a></li>
        <li><a v-on:click.prevent="cancelAndDeleteAllSelected"
               href="#">Delete &amp; cancel invoice</a></li>
    </ul>
</div>
<button v-on:click="clearAndResetAllSelectedAttendees" style="margin-left: 5px;" type="button"
        class="btn btn-danger pull-right">Clear <i class="fa fa-arrow-left"></i>
</button>
<button v-on:click="save" v-if="saveState != ajaxStates.busy"
        style="margin-left: 5px;"
        type="button" class="btn btn-success pull-right">Save
</button>
<button v-if="saveState == ajaxStates.busy" style="margin-left: 5px;"
        type="button" class="btn btn-success pull-right" disabled>
    <i class="fa fa-cog fa-spin"></i>
</button>
<div class="btn-group pull-right">
    <span class="btn btn-warning btn-file" title="Looks for the following columns: ">
    From CSV <input type="file" accept=".csv">
    </span>
    <span class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
          aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </span>
    <ul class="dropdown-menu">
        <li><a target="_blank" href="/assets/admin/assets/documents/csv/attendance.csv">Download template</a></li>
    </ul>
</div>