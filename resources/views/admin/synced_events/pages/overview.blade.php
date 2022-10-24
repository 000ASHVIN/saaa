<div id="overview" class="tab-pane fade in active">
   <div class="row">
       {!! Form::model($event, ['method' => 'post', 'route' => ['admin.synced_events.update', $event->slug]]) !!}
            @include('admin.synced_events.includes.event-information', ['submit' => 'Update Event'])
       {!! Form::close() !!}
   </div>
</div>