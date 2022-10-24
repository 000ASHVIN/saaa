@extends('admin.layouts.master')

@section('title', 'Events')
@section('description', 'All events.')

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            {!! Form::open(['method' => 'post', 'route' => 'admin.event.search']) !!}
            <div class="form-group @if ($errors->has('event_name')) has-error @endif">
                {!! Form::label('event_name', 'Search Events') !!}
                {!! Form::input('text', 'event_name', null, ['class' => 'form-control', 'placeholder' => 'Ethics Independence and NOCLAR']) !!}
                @if ($errors->has('event_name')) <p class="help-block">{{ $errors->first('event_name') }}</p> @endif
            </div>

            <button class="btn btn-primary" onclick="spin(this)"><i class="fa fa-search"></i> Search</button>
            {!! Form::close() !!}
            <hr>
            <events-data-grid :events="{{ collect($events->toArray()['data'])->map(function($event) { $event['description'] = ''; return $event; }) }}" inline-template>
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Notifications</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="event in events">
                        <td><a class="label label-success" href="/admin/events/@{{ event.id }}">@{{ event.type | capitalize }}</a></td>
                        <td><a href="/admin/events/@{{ event.id }}">@{{ event.name }}</a></td>
                        <td>@{{ event.start_date | date }}</td>
                        <td>@{{ event.end_date | date }}</td>
                        <td>
                            <span v-if="event.notifications!=null">@{{ event.notifications.status_text }} <br/> 
                                (@{{ event.notifications.schedule_date | date }})</span>
                            <span v-else>Not Scheduled</span>
                        </td>
                        <td class="text-center">@{{ event.tickets.length }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" aria-expanded="true">
                                    Modify <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a v-if="event.reference_id>0" href="#">
                                            Edit event
                                        </a>
                                        <a v-else href="/admin/event/show/@{{ event.slug }}">
                                            Edit event
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/admin/events/@{{ event.id }}">
                                            Event stats
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/admin/event/export/@{{ event.slug }}">
                                            Export stats
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/admin/event/upload/@{{ event.slug }}/sendinBlue">
                                            Upload (SendinBlue)
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/admin/event/publish/@{{ event.slug }}/store">Publish to Store</a>
                                    </li>
                                    <li>
                                        <a href="#" data-id="@{{event.slug}}" name="notify_user">Notify User</a>
                                    </li>
                                    <li v-if="event.end_date > '{{ Carbon\Carbon::now() }}' ">
                                        <a href="#" data-id="@{{event.id}}" data-notification_status="@{{ event.notifications.status }}" name="schedule_notifications">Schedule Notifications</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </events-data-grid>

            <div class="text-center">
                @if(count($events))
                    {!! $events->render() !!}
                @endif
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
        $(document).on("click","a[name='notify_user']", function (e) {
            var slug = $(this).data('id');
			swal({
				title: "Are you sure want to send mail ?",
				text: "Send a mail",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Comfirm",
				cancelButtonText: "No",
				closeOnConfirm: false,
				closeOnCancel: false
			}, function(isConfirm) {
				if(isConfirm) {
                    window.location.href =  "/admin/event/notify/"+ slug +"/store" ;
				} else {
					swal("Cancelled", "Email not send :)", "error");
				}
			});

			e.preventDefault
		});

        $(document).on("click","a[name='schedule_notifications']", function (e) {
            var id = $(this).data('id');
            var notification_status = $(this).data('notification_status');
            console.log(notification_status);
            if(notification_status=='not_scheduled' || notification_status== undefined) {

                swal({
                    title: "Are you sure to schedule notifications for this event ?",
                    text: "Schedule notifications",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Confirm",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if(isConfirm) {
                        window.location.href =  "/admin/event/notifications/create?event="+ id;
                    }
                });

            }
            else {

                swal("Cancelled", "Notifications already scheduled for the event :)", "error");

            }
			e.preventDefault
		});

    </script>

@stop
