    <td>
        <span v-if="venue.city">
            <i class="ico-dark ico-hover et-briefcase"></i>
        </span>
        <span v-else>
            <i class="ico-dark ico-hover et-global"></i>
        </span>
    </td>

    <td class="vertical-align-middle">
        @{{ venue.name }}
    </td>

    <td class="text-center vertical-align-middle" v-if="venue.city">@{{ venue.city }}</td>
    <td class="text-center vertical-align-middle" v-else>Online</td>

    <td class="text-center vertical-align-middle">
        <div v-if="venue.dates.length >= 1">
            <div v-for="date in venue.dates">@{{ date.date }}</div>
        </div>
    </td>

    <td class="text-center vertical-align-middle">
        <div class="app-plan-subscribe-button-container">
            <button class="btn btn-primary app-plan-subscribe-button"  @click.prevent="selectVenue(venue)">
                    <span>
                        <i class="fa fa-check"></i> Select
                    </span>
            </button>
        </div>
    </td>