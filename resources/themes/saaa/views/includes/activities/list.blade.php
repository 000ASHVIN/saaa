@foreach($member->latestActivities() as $activity)
    @if(isset($activity->subject))
        @include("includes.activities.types.{$activity->name}")
    @elseif($activity->subject == null)
        @include("includes.activities.types.activity_name") 
    @endif
@endforeach