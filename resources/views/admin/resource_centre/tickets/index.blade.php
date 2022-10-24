@extends('admin.layouts.master')

@section('title', 'Ask an Expert')
@section('description', 'Support Tickets')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div class="container-fluid padding-top-10">
        <div class="row">
            {!! Form::open(['method' => 'get']) !!}
                <div class="col-md-3">
                    <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                        {!! Form::label('title', 'Search Title') !!}
                        {!! Form::input('text', 'title', \Input::has('title')?\Input::get('title'):"", ['class' => 'form-control', 'placeholder' => 'Searching']) !!}
                        @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
                    </div>
                </div>
        
                <div class="col-md-3">
                    <div class="form-group @if ($errors->has('priority')) has-error @endif">
                    {!! Form::label('priority', 'Priority Filter') !!}
                    {!! Form::select('priority', getThreadPriorities(),\Input::has('priority')?\Input::get('priority'):"", ['class' => 'form-control', 'placeholder' => 'Select Priority']) !!}
                    @if ($errors->has('priority')) <p class="help-block">{{ $errors->first('priority') }}</p> @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group @if ($errors->has('status')) has-error @endif">
                        {!! Form::label('status', 'Status') !!}
                        {!! Form::select('status', getThreadStatuses(),\Input::has('status')?\Input::get('status'):"", ['class' => 'form-control', 'placeholder'=>'Select Status']) !!}
                        @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
                    </div>
                </div>
                <div class="row">
                </div>
                @if (auth()->user()->is('admin'))
                    
                    <div class="col-md-3">
                        <div class="form-group @if ($errors->has('agent_group_id')) has-error @endif">
                                {!! Form::label('agent_group_id', 'Agent Group') !!}
                                {!! Form::select('agent_group_id[]', $agentGroups,\Input::has('agent_group_id')?\Input::get('agent_group_id'):"", ['class' => 'form-control select2', 'multiple'=>'multiple']) !!}
                                @if ($errors->has('agent_group_id')) <p class="help-block">{{ $errors->first('agent_group_id') }}</p> @endif
                            @if ($errors->has('agent_group_id')) <p class="help-block">{{ $errors->first('agent_group_id') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group @if ($errors->has('agent_id')) has-error @endif">
                            {!! Form::label('agent_id', 'Agent') !!}
                            {!! Form::select('agent_id[]', $agents,\Input::has('agent_id')?\Input::get('agent_id'):"", ['class' => 'form-control select2', 'multiple'=>'multiple']) !!}
                            @if ($errors->has('agent_id')) <p class="help-block">{{ $errors->first('agent_id') }}</p> @endif
                            @if ($errors->has('agent_id')) <p class="help-block">{{ $errors->first('agent_id') }}</p> @endif
                        </div>
                    </div>

                @endif
            
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="button" style="width: 100%;top: 20px;" onclick="spin(this)" class="btn btn-primary btn-wide btn-scroll btn-scroll-top ti-search"><span>Search Now</span></button>
                    </div>
                </div>  
            {!! Form::close() !!}
            <hr/>
        </div>
    </div>
    <div class="container-fluid container-fullw padding-bottom-10 bg-white" style="overflow-x:auto;">

        @if (auth()->user()->handesk_token)
            <div class="col-md-12 text-right" style="margin-bottom:10px; margin-top:-10px;">
            <a href="{{ config('services.handesk.web_url') }}/users/impersonate/{{ auth()->user()->handesk_token }}" class="btn btn-success" target="_blank" style="margin-top:-10px;">Go To Handesk</a>
            </div>
        @endif

        <div class="col-md-12">
            <div class="row">
                <table class="table table-striped" id="support_tickets">
                    <thead>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Agent Group</th>
                        <th>Agent</th>
                        <th>Messages</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Public</th>
                        <th></th>
                        <th></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="update_thread_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Thread</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {!! Form::open(['method' => 'get','id' => 'updte_form']) !!}
                            <input type="hidden" name="thread_id" value="" id="thread_id">
                            <div class="col-md-12">
                                <div class="form-group @if ($errors->has('priority')) has-error @endif">
                                {!! Form::label('priority', 'Priority') !!}
                                {!! Form::select('priority', getThreadPriorities(),null, ['class' => 'form-control', 'id'=>'t_priority', 'placeholder'=>'Select Priority']) !!}
                                @if ($errors->has('priority')) <p class="help-block">{{ $errors->first('priority') }}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @if ($errors->has('status')) has-error @endif">
                                    {!! Form::label('status', 'Status') !!}
                                    {!! Form::select('status', getThreadStatuses(),null, ['class' => 'form-control', 'id'=>'t_status', 'placeholder'=>'Select Status']) !!}
                                    @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group @if ($errors->has('agent_group_id')) has-error @endif">
                                        {!! Form::label('agent_group_id', 'Agent Group') !!}
                                        {!! Form::select('agent_group_id', [null=>'Please select'] + $agentGroups,null, ['class' => 'form-control', 'id'=>'t_agent_group_id']) !!}
                                        @if ($errors->has('agent_group_id')) <p class="help-block">{{ $errors->first('agent_group_id') }}</p> @endif
                                </div>
                            </div>
            
                            <div class="col-md-12">
                                <div class="form-group @if ($errors->has('agent_id')) has-error @endif">
                                    {!! Form::label('agent_id', 'Agent') !!}
                                    {!! Form::select('agent_id', [null=>'Please select'] + $agents,null, ['class' => 'form-control', 'id'=>'t_agent_id']) !!}
                                    @if ($errors->has('agent_id')) <p class="help-block">{{ $errors->first('agent_id') }}</p> @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    {!! Form::label('category', 'Category') !!}
                                    {!! Form::select('category', [null=>'Please select'] + $categories,null, ['class' => 'form-control', 'id'=>'t_category_id']) !!}
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="javascript:saveThread();">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>

    <script>
        $('#support_tickets').DataTable( {
            "bFilter": true,
            serverSide: true,
            ajax: '/resource_centre/tickets/get_threads?{!! $searchString !!}',
            pageLength: 50,
            order: [[ 0, "desc" ]],
            columns: [
                {data: 'id'},
                {data: 'title'},
                {data: 'category'},
                {
                    data: 'agent_group',
                    "sortable": false
                },
                {   
                    data: 'agent',
                    "sortable": false
                },
                {data: 'replies'},
                {
                    data: 'priority',
                    "render": function (id, type, full, meta) {
                        if(id!='' && id!=null) {
                            return '<label class="label label-body">'+full.priorityText+'</label>';
                        }
                        else {
                            return '';
                        }
                    }
                },
                {
                    data: 'status',
                    "render": function (id, type, full, meta) {
                        if(id!='') {
                            var labelClass='label-success';
                            if(id=='closed') {
                                labelClass='label-danger';
                            }
                            return '<label class="label '+labelClass+'">'+full.statusText+'</label>';
                        }
                        else {
                            return '';
                        }
                    }
                },
                {data: 'open_to_public'},
                {data: "id",
                    "searchable": false,
                    "sortable": false,
                    "render": function (id, type, full, meta) {
                        return '<a href="javascript:editThread('+id+');" id="edit_thread_'+id+'" data-status="'+full.status+'" data-priority="'+full.priority+'" data-agent_id="'+full.agent_id+'" data-agent_group_id="'+full.agent_group_id+'" data-category_id="'+full.category_id+'" class="btn btn-sm btn-info btn-circle" ><i class="fa fa-pencil"></i></a>';
                    }
                },
                {data: "id",
                    "searchable": false,
                    "sortable": false,
                    "render": function (id, type, full, meta) {
                        return '<a href="/resource_centre/tickets/thread/show/'+id+'" class="btn btn-sm btn-secondary btn-circle" style="background-color: #21679b; border-color: #21679b; color: white"><i class="fa fa-eye"></i></a>';
                    }
                },
            ],
            'processing': true,
            'language': {
                'loadingRecords': '&nbsp;',
                'processing': '<div class="spinner"></div>'
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        $('.select2').select2();
        function editThread(thread_id) {
            var status          = $("#edit_thread_"+thread_id).data('status');
            var priority        = $("#edit_thread_"+thread_id).data('priority');
            var agent_id        = $("#edit_thread_"+thread_id).data('agent_id');
            var agent_group_id  = $("#edit_thread_"+thread_id).data('agent_group_id');
            var category_id  = $("#edit_thread_"+thread_id).data('category_id');
            if(agent_id=="0") {
                agent_id="";
            }
            if(agent_group_id=="0") {
                agent_group_id="";
            }

            $("#update_thread_modal #t_status").val(status);
            $("#update_thread_modal #t_priority").val(priority);
            $("#update_thread_modal #t_agent_id").val(agent_id);
            $("#update_thread_modal #t_agent_group_id").val(agent_group_id);
            $("#update_thread_modal #t_category_id").val(category_id);
            $("#update_thread_modal #thread_id").val(thread_id);
            $("#update_thread_modal").modal();
        }

        $("#updte_form").on('submit', function(e){
            e.preventDefault();
        });

        function saveThread() {

            var form = $("#update_thread_modal #updte_form");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': window.App.csrfToken
                }
            });

            $.ajax({
                url: "{{ route('admin.resource_centre.tickets.update')}}",
                method: 'post',
                data: $("#update_thread_modal #updte_form").serialize(),
                success: function(response){
                    if(response.status) {
                        $("#update_thread_modal").modal('hide');
                        $('#support_tickets').DataTable().ajax.reload();
                    }
                }
            });

        }
    </script>
@stop