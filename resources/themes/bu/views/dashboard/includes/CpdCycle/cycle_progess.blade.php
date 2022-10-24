@if($cycle->designation->tax)
    <div class="text-left"><strong><small>Tax Hours | {{ $cycle->tax }} / {{ $cycle->designation->tax }}</small></strong></div>
    <div class="progress">
        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
             aria-valuenow="{{ ($cycle->designation->tax > 1) ? $cycle->tax * 100 / $cycle->designation->tax : 0 }}"
             aria-valuemin="0" aria-valuemax="100" style="width: {{ ($cycle->designation->tax > 1) ? ($cycle->tax) ? $cycle->tax * 100 /$cycle->designation->tax : 10 : '2em'}}%">
            {{ round(($cycle->designation->tax > 1) ? $cycle->tax * 100 / $cycle->designation->tax : 0) }}%
        </div>
    </div>
@endif

@if($cycle->designation->ethics)
    <div class="text-left"><strong><small>Ethics Hours | {{ $cycle->ethics }} / {{ $cycle->designation->ethics }}</small></strong></div>
    <div class="progress">
        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
             aria-valuenow="{{ ($cycle->designation->ethics > 1) ? $cycle->ethics * 100 / $cycle->designation->ethics : 0 }}"
             aria-valuemin="0" aria-valuemax="100" style="width: {{ ($cycle->designation->ethics > 1) ? ($cycle->ethics) ? $cycle->ethics * 100 /$cycle->designation->ethics : 10 : '2em'}}%">
            {{ round(($cycle->designation->ethics > 1) ? $cycle->ethics * 100 / $cycle->designation->ethics : 0) }}%
        </div>
    </div>
@endif

@if($cycle->designation->accounting)
    <div class="text-left"><strong><small>Accounting Hours | {{ $cycle->accounting }} / {{ $cycle->designation->accounting }}</small></strong></div>
    <div class="progress">
        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
             aria-valuenow="{{ ($cycle->designation->accounting > 1) ? $cycle->accounting * 100 / $cycle->designation->accounting : 0 }}"
             aria-valuemin="0" aria-valuemax="100" style="width: {{ ($cycle->designation->accounting > 1) ? ($cycle->accounting) ? $cycle->accounting * 100 /$cycle->designation->accounting : 10 : '2em'}}%">
            {{ round(($cycle->designation->accounting > 1) ? $cycle->accounting * 100 / $cycle->designation->accounting : 0) }}%
        </div>
    </div>
@endif

@if($cycle->designation->auditing)
    <div class="text-left"><strong><small>Auditing Hours | {{ $cycle->auditing }} / {{ $cycle->designation->auditing }}</small></strong></div>
    <div class="progress">
        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
             aria-valuenow="{{ ($cycle->designation->auditing > 1) ? $cycle->auditing * 100 / $cycle->designation->auditing : 0 }}"
             aria-valuemin="0" aria-valuemax="100" style="width: {{ ($cycle->designation->auditing > 1) ? ($cycle->auditing) ? $cycle->auditing * 100 /$cycle->designation->auditing : 10 : '2em'}}%">
            {{ round(($cycle->designation->auditing > 1) ? $cycle->auditing * 100 / $cycle->designation->auditing : 0) }}%
        </div>
    </div>
@endif

@if($cycle->designation->verifiable)
    <div class="text-left"><strong><small>Verifiable Hours | {{ $cycle->verifiable }} / {{ $cycle->designation->verifiable }}</small></strong></div>
    <div class="progress">
        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
             aria-valuenow="{{ ($cycle->designation->verifiable > 1) ? $cycle->verifiable * 100 / $cycle->designation->verifiable : 0 }}"
             aria-valuemin="0" aria-valuemax="100" style="width: {{ ($cycle->designation->verifiable > 1) ? ($cycle->verifiable) ? $cycle->verifiable * 100 /$cycle->designation->verifiable : 10 : '2em'}}%">
            {{ round(($cycle->designation->verifiable > 1) ? $cycle->verifiable * 100 / $cycle->designation->verifiable : 0) }}%
        </div>
    </div>
@endif

@if($cycle->designation->unstructed)
    <div class="text-left"><strong><small>Unstrcuted Hours | {{ $cycle->unstructed }} / {{ $cycle->designation->unstructed }}</small></strong></div>
    <div class="progress">
        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
             aria-valuenow="{{ ($cycle->designation->unstructed > 1) ? $cycle->unstructed * 100 / $cycle->designation->unstructed : 0 }}"
             aria-valuemin="0" aria-valuemax="100" style="width: {{ ($cycle->designation->unstructed > 1) ? ($cycle->unstructed) ? $cycle->unstructed * 100 /$cycle->designation->unstructed : 10 : '2em'}}%">
            {{ round(($cycle->designation->unstructed > 1) ? $cycle->unstructed * 100 / $cycle->designation->unstructed : 0) }}%
        </div>
    </div>
@endif

@if($cycle->designation->non_verifiable)
    <div class="text-left"><strong><small>Non Verifiable Hours | {{ $cycle->non_verifiable }} / {{ $cycle->designation->non_verifiable }}</small></strong></div>
    <div class="progress">
        <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
             aria-valuenow="{{ ($cycle->designation->non_verifiable > 1) ? $cycle->non_verifiable * 100 / $cycle->designation->non_verifiable : 0 }}"
             aria-valuemin="0" aria-valuemax="100" style="width: {{ ($cycle->designation->non_verifiable > 1) ? ($cycle->non_verifiable) ? $cycle->non_verifiable * 100 /$cycle->designation->non_verifiable : 10 : '2em'}}%">
            {{ round(($cycle->designation->non_verifiable > 1) ? $cycle->non_verifiable * 100 / $cycle->designation->non_verifiable : 0) }}%
        </div>
    </div>
@endif

<div class="text-left"><strong><small>Total Hours | {{ $cycle->total_hours }} / {{ $cycle->designation->total_hours }}</small></strong></div>
<div class="progress">
    <div class="progress-bar progress-bar-primary progress-bar-striped active" role="progressbar"
         aria-valuenow="{{ ($cycle->designation->total_hours > 1) ? $cycle->total_hours * 100 / $cycle->designation->total_hours : 0 }}"
         aria-valuemin="0" aria-valuemax="100" style="width: {{ ($cycle->designation->total_hours > 1) ? ($cycle->total_hours) ? $cycle->total_hours * 100 /$cycle->designation->total_hours : 10 : '2em'}}%">
        {{ round(($cycle->designation->total_hours > 1) ? $cycle->total_hours * 100 / $cycle->designation->total_hours : 0) }}%
    </div>
</div>

<div class="text-cetner">
    <a href="#" data-target="#update_cpd_cycle" data-toggle="modal" class="btn btn-primary btn-block"><i class=" fa fa-pencil"></i> Update Existing Cycle</a>
    @include('dashboard.includes.CpdCycle.update_cycle')
</div>