<div class="panel panel-default">
    <div class="panel-heading">Your Venue</div>
    <div class="panel-body">

        <!-- Current Venue -->
        <div class="pull-left" style="line-height: 36px;" v-if="! hasSingleVenue">
            You have selected the <strong v-if="forms.eventSignup.venue.city">@{{ forms.eventSignup.venue.city }},</strong> <strong>@{{ forms.eventSignup.venue.name }}</strong> venue.
            <span v-if="forms.eventSignup.venue.type == 'online'"><br><em>
                    <small>If you cannot attend the webinar live, you will
                        able to view the online recording at a later date.
                    </small></em></span>
        </div>

        <div v-else>
            <strong v-if="forms.eventSignup.venue.city">@{{ forms.eventSignup.venue.city }},</strong> <strong>@{{ forms.eventSignup.venue.name }}</strong>
            <span v-if="forms.eventSignup.venue.type == 'online'"><br><em>
                    <small>If you cannot attend the webinar live, you will
                        able to view the online recording at a later date.
                    </small></em></span>
        </div>

        <!-- Select Another Venue -->
        <div class="pull-right" style="line-height: 32px;" v-if="! hasSingleVenue">
            <button class="btn btn-primary" @click.prevent="selectAnotherVenue">
                <i class="fa fa-btn fa-arrow-left"></i>Change Venue
            </button>
        </div>

        <div class="clearfix"></div>
    </div>
</div>