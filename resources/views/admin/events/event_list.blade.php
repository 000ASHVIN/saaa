@extends('admin.layouts.master')

@section('title', 'All Events')
@section('description', 'Sync events')

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <form role="form" method="POST" action="/admin/event-list-sync">
            {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <select class="form-control" name="event" id="event">
                                @foreach($api_events as $event)
                                   @if(in_array($event->name,$syncedEvent))
                                    <option value="{{ $event->name }}" >{{ $event->name }}</option>
                                   @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group center">
                            <div class="btn btn-o btn-primary" onclick="checkevent()";>
                                Check Event
                            </div>
                        </div>
                        <div class="form-group center">
                            <button type="submit" class="btn btn-o btn-primary" onclick="spin(this)";>
                                Event Sync
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <h3>Synced Event List</h3>
            {{--  <ul>
                @foreach($syncedEventList as $event)
                        <li>{{ $event }}</li>
                @endforeach
            </ul>  --}}
           <table>
                <thead>
                    <th>Events</th>
                </thead>
                <tbody>
                    @foreach($syncedEventList as $event)
                    <tr>
                        <td>{{ $event }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table> 

        </div>
    </section>
@stop 


@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
        function checkevent(){
            var name  = $('#event').val();
            var host = "{{URL::to('/')}}"
            $.ajax({
                type: "POST",
                url: host + '/admin/check-event',
                data: {name: name, _token: "{{ csrf_token() }}"},
                success: function (object) {
                   if(object == 'Event Exist'){
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: "Event exist in your system, please try again"
                    });
                   }else{
                    swal({
                        type: 'info',
                        title: 'New Event',
                        text: "You can sync event and pull data"
                    });
                   }
                }
            });
        }
    </script>
@stop
