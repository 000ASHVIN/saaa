<div class="row">
    @include('admin.cpd.partials.search',['selected' => false])
    <br><br>
    <div class="col-sm-12 col-md-12">
        <form class="form-inline">
            <div class="form-group">
                @include('admin.cpd.partials.filters')
            </div>
            <button type="button" v-on:click="addAllFilteredAttendeesToSelected" class="btn btn-info pull-right">
                Add all&nbsp;<i class="fa fa-arrow-right"></i>
            </button>
        </form>
    </div>
</div>