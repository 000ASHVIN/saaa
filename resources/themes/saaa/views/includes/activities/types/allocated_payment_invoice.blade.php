<li class="timeline-item success">
    <div class="margin-left-15">
        <div class="text-muted text-small">
            {{ $activity->created_at->diffForHumans() }}
        </div>
        <p>
            {{ $activity->user->first_name }} added a new {{ $activity->subject->hours }} Payment.
        </p>
    </div>
</li>