<li class="timeline-item success">
    <div class="margin-left-15">
        <div class="text-muted text-small">
            {{ date_format($activity->created_at, 'd F Y') }}
        </div>
        <p>
            {{ $activity->user->first_name }} deleted CPD entry.
        </p>
    </div>
</li>