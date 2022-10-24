<div id="overview" class="tab-pane fade in active">
   <div class="row">
       {!! Form::model($event, ['method' => 'post', 'route' => ['admin.event.update', $event->slug]]) !!}
            @include('admin.event.includes.event-information', ['submit' => 'Update Event'])
       {!! Form::close() !!}
   </div>
</div>