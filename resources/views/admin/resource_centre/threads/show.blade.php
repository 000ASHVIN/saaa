@extends('admin.layouts.master')

@section('title', 'Ask an Expert')
@section('description', 'Thread: ' .ucwords($thread->title))

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="col-md-12">
            <div class="row mb-3">
                <div class="col-md-12 col-sm-12">
                    {!! Form::model($thread,['method' => 'post', 'route' => ['admin.resource_centre.tickets.assign',$thread->id], 'id' => 'assign_agents']) !!}
                        <input type="hidden" name="thread_id" value="{{ $thread->id }}" id="thread_id">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                {!! Form::label('priority', 'Priority') !!}
                                {!! Form::select('priority', getThreadPriorities(),null, ['class' => 'form-control', 'id'=>'priority', 'placeholder'=>'Select Priority']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('status', 'Status') !!}
                                    {!! Form::select('status', getThreadStatuses(),null, ['class' => 'form-control', 'id'=>'status', 'placeholder'=>'Select Status']) !!}
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    {!! Form::label('category', 'Category') !!}
                                    {!! Form::select('category', [null=>'Please select'] + $categories,null, ['class' => 'form-control', 'id'=>'category_id']) !!}
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('agent_group_id', 'Agent Group') !!}
                                    {!! Form::select('agent_group_id', [null=>'Please select'] + $agentGroups,null, ['class' => 'form-control select2']) !!}
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    {!! Form::label('agent_id', 'Agent') !!}
                                    {!! Form::select('agent_id', [null=>'Please select'] + $agents,null, ['class' => 'form-control select2']) !!}
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    <hr>
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <h4><i class="fa fa-reply"></i> Reply to this thread</h4>
                    <hr>
                    {!! Form::open(['method' => 'post', 'route' => ['thread.store', $thread], 'id'=>'frm_reply']) !!}
                    <input type="hidden" value="" name="close_ticket" id="hdn_close_ticket">
                    <div class="form-group @if ($errors->has('description')) has-error @endif">
                        {!! Form::textarea('description', null, ['class' => 'ckeditor form-control', 'id' => 'ticket']) !!}
                        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
                    </div>

                    <div class="form-group">
                        <input type="checkbox" name="chk_notify" id="chk_notify" value="1"> 
                        <label for="chk_notify" style="cursor: pointer;">Notify client for the action</label>
                    </div>

                    <button class="btn btn-primary" type="submit"><i class="fa fa-reply"></i> Reply</button> &nbsp;
                    <button class="btn btn-success" type="button" id="btn_close_ticket"><i class="fa fa-reply"></i> Close Ticket</button>
                    {!! Form::close() !!}
                    <br/><br/>
                </div>
            </div>

            <div class="row">

                @foreach($tickets as $ticket)
                    <div class="col-sm-12">
                        <div class="panel panel-white no-radius">
                            <div class="panel-heading border-light">
                                <h6 class="panel-title">
                                    @if($ticket->agent)
                                        <i class="fa fa-user"></i> {{ ucwords($ticket->agent->first_name.' '.$ticket->agent->last_name) }} &lt;{{ $ticket->agent->email }}&gt;
                                    @elseif($ticket->user)
                                        <i class="fa fa-user"></i> {{ ucwords($ticket->user->first_name.' '.$ticket->user->last_name) }} &lt;{{ $ticket->user->email }}&gt;
                                    @else
                                        N/A
                                    @endif
                                </h6>
                                <div class="panel-tools">
                                    <a data-placement="top" class="btn btn-transparent btn-sm" href="javascript:void(0);">
                                        <i class="fa fa-calendar-o"></i> {{ date_format($ticket->created_at, 'd F Y H:i') }}
                                    </a>
                                </div>
                            </div>
                            <div class="panel-body">
                                {!! $ticket->description !!}
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-sm-12">
                    <div class="panel panel-white no-radius">
                        <div class="panel-heading border-light">
                            <h4 class="panel-title">
                                @if($thread->user)
                                    <i class="fa fa-user"></i> {{ ucwords($thread->user->first_name.' '.$thread->user->last_name) }} &lt;{{ $thread->user->email }}&gt;
                                @else
                                    N/A
                                @endif
                            </h4>
                            <div class="panel-tools">
                                <a class="btn btn-transparent btn-sm" href="javascript:void(0);">
                                    <i class="fa fa-calendar-o"></i> {{ date_format($thread->created_at, 'd F Y H:i') }}
                                </a>
                            </div>
                        </div>
                        <div class="panel-body">
                            {!! $thread->description !!}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

        // When dropdowns are changed
        $("#priority, #status, #agent_group_id, #agent_id, #category_id").on('change',function(){
            
            var form = $("#assign_agents");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': window.App.csrfToken
                }
            });

            $.ajax({
                //url: "{{ route('admin.resource_centre.tickets.assign',$thread->id)}}",
                url: "{{ route('admin.resource_centre.tickets.update')}}",
                method: 'post',
                data: $("#assign_agents").serialize(),
                success: function(response){
                    if(response.status) {
                        swal(
                            {"timer":1800,
                                "title":"Success!",
                                "text":"Thread updated successfully.",
                                "showConfirmButton":false,
                                "type":"success"
                            }
                        );         
                    }
                }
            });
    
        });

        // When close ticket button is clicked
        $('#btn_close_ticket').on('click', function(){
            $("#hdn_close_ticket").val('1');
            $('#frm_reply').submit();
        });

    </script>
@stop