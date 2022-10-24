@foreach($member->latestActivities() as $activity)
    @if(isset($activity->subject))
        @include("includes.activities.types.{$activity->name}")
    @endif
@endforeach