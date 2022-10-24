<li class="timeline-item danger">
	<div class="margin-left-15">
		<div class="text-muted text-small">
			{{ $activity->created_at->diffForHumans() }}
		</div>
		<p>
			{{ $activity->user->first_name }} deleted a {{ $activity->subject->type }} address.
		</p>
	</div>
</li>