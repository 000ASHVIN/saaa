<li class="timeline-item success">
	<div class="margin-left-15">
		<div class="text-muted text-small">
			{{ $activity->created_at->diffForHumans() }}
		</div>
		<p>
			@if(isset($activity->subject->city))
				{{ $activity->user->first_name }} added a new {{ $activity->subject->type }} address in {{ $activity->subject->city }}.
			@else
				{{ $activity->user->first_name }} added a new {{ $activity->subject->type }} address.
			@endif
		</p>
	</div>
</li>