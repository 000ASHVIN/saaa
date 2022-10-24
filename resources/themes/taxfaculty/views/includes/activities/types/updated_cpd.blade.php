<li class="timeline-item success">
    <div class="margin-left-15">
        <div class="text-muted text-small">
            {{ date_format($activity->created_at, 'd F Y') }}
        </div>
        <p>
            {{ $activity->user->first_name }} updated {{ $activity->subject->hours }} hour CPD entry - {{ str_limit($activity->subject->source, 80) }}.
        </p>
    </div>
</li>