<div class="col-sm-12 col-md-12">
    <div class="input-group">
        @if(!$selected)
            <input v-model="search" type="text" class="form-control"
                   placeholder="Search">
            <span class="input-group-btn">
        <button v-on:click="clearAttendeeSearch" class="btn btn-default" type="button">&times;</button>
      </span>
        @else
            <input v-model="selectedSearch" type="text" class="form-control"
                   placeholder="Search">
            <span class="input-group-btn">
        <button v-on:click="clearSelectedAttendeeSearch" class="btn btn-default" type="button">&times;</button>
      </span>
        @endif
    </div>
</div>