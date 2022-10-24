@extends('admin.layouts.master')

@section('title', 'Leads Search')
@section('description', 'Leads Search')

@section('content')  

    <div class="container-fluid container-fullw padding-bottom-20">
        <div class="row">
            {!! Form::open(['method' => 'get', 'route' => 'admin.leads.index']) !!}
                <div class="col-md-3">
                    <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                        {!! Form::label('full_name', 'Search Full Name') !!}
                        {!! Form::input('text', 'full_name', \Input::has('full_name')?\Input::get('full_name'):"", ['class' => 'form-control', 'placeholder' => 'Searching']) !!}
                        @if ($errors->has('full_name')) <p class="help-block">{{ $errors->first('full_name') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group @if ($errors->has('email')) has-error @endif">
                        {!! Form::label('email', 'Search Email') !!}
                        {!! Form::input('text', 'email', \Input::has('email')?\Input::get('email'):"", ['class' => 'form-control', 'placeholder' => 'Searching']) !!}
                        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group @if ($errors->has('cell')) has-error @endif">
                        {!! Form::label('cell', 'Search Cell Phone') !!}
                        {!! Form::input('text', 'cell', \Input::has('cell')?\Input::get('cell'):"", ['class' => 'form-control', 'placeholder' => 'Searching']) !!}
                        @if ($errors->has('cell')) <p class="help-block">{{ $errors->first('cell') }}</p> @endif
                    </div>
                </div>  

                <div class="col-md-3">
                    <div class="form-group @if ($errors->has('status')) has-error @endif">
                    {!! Form::label('status', 'Search Status') !!}
                    {!! Form::select('status', $statuses->pluck('name', 'id'),\Input::has('status')?\Input::get('status'):"", ['class' => 'form-control', 'placeholder' => 'Select Status']) !!}
                    @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group @if ($errors->has('is_converted')) has-error @endif">
                    {!! Form::label('status', 'Converted') !!}
                    {!! Form::select('is_converted', ['1' => 'Yes', '0' => 'No'],\Input::has('is_converted')?\Input::get('is_converted'):"", ['class' => 'form-control', 'placeholder' => '-- Select --']) !!}
                    @if ($errors->has('is_converted')) <p class="help-block">{{ $errors->first('is_converted') }}</p> @endif
                    </div>
                </div>

                @if ($user->is('super'))
                    <div class="col-md-3">
                        <div class="form-group @if ($errors->has('owner_id')) has-error @endif">
                        {!! Form::label('status', 'Lead Owner') !!}
                        {!! Form::select('owner_id', $reps->pluck('name', 'user_id'),\Input::has('owner_id')?\Input::get('owner_id'):"", ['class' => 'form-control', 'placeholder' => '-- Select Lead Owner--']) !!}
                        @if ($errors->has('owner_id')) <p class="help-block">{{ $errors->first('owner_id') }}</p> @endif
                        </div>
                    </div>
                @endif

                <div class="col-md-6">
                    <div class="form-group" style="padding-top: 20px;">
                        <button type="button" style="" onclick="spin(this)" class="btn btn-primary btn-wide btn-scroll btn-scroll-top ti-search"><span>Search Now</span></button>
                        @if (isset(request()->full_name))
                            <a href="{{ route('admin.leads.index') }}" class="btn btn-warning btn-wide"><span>Clear Search</span></a>
                        @endif
                    </div>
                </div>  


            {!! Form::close() !!}
        </div>
    </div>

    <section>

        <div class="padding-bottom-10 bg-white">
            <table class="table table-striped" id="sample-table-2" style="margin-top:20px;">
                <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    @if ($user->is('super'))
                        <th>Owner</th>
                    @endif
                    <th>Status</th>
                    <th>Coverted</th>
                    <th>Source</th>
                    <th>Created</th>
                    <th>Updated</th>
                    <th>Actions</th>
                </thead> 
                <br>
                <tbody>
                @if(count($leads)) 
                    <?php $i = $leads->firstItem(); ?>
                    @foreach($leads as $lead)

                        <tr>
                            <td width="1px"><span class="label label-body"> {{ $i++ }} </span></td>

                            <td>{{ $lead->first_name.' '.$lead->last_name }}</td>

                            <td>{{ $lead->email }}</td>

                            <td>{{ $lead->mobile }}</td>

                            @if ($user->is('super'))
                                <td>{{ $lead->leadOwner ? $lead->leadOwner->first_name.' '.$lead->leadOwner->last_name : '-' }}</td>
                            @endif

                            <td>{{ $lead->leadStatus ? $lead->leadStatus->name : '-' }}</td>

                            <td>{{ $lead->is_converted ? 'Yes' : 'No' }}</td>

                            <td>{{ $lead->source }}</td>

                            <td>{{ $lead->created_at->diffForHumans() }}</td>

                            <td>{{ $lead->updated_at->diffForHumans() }}</td>

                            <td>
                                <a href="{{ route('admin.leads.activity', $lead->id) }}" class="btn btn-xs btn-primary">Activity</a>
                                <a href="javascript:editLead({{ $lead->id }})" class="btn btn-xs btn-info" data-lead_status="{{ $lead->leadStatus ? $lead->leadStatus->id : '' }}" data-lead_owner="{{ $lead->leadOwner ? $lead->leadOwner->id : '' }}" id="edit_lead_{{ $lead->id }}">Edit</a>
                            </td>
 
                        </tr>
                        
                    @endforeach
                
                @else
                    <tr>
                        <td colspan="11" class="text-center">
                            No leads available.
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            {!! $leads->appends(request()->except('page'))->render() !!}
        </div>

        <div class="modal fade" id="update_lead_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Lead</h5>
                    </div>
                    {!! Form::open(['method' => 'post','id' => 'updte_form', 'route' => 'admin.leads.update']) !!}
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="lead_id" value="" id="lead_id">
                                <div class="col-md-12">
                                    <div class="form-group @if ($errors->has('lead_status_id')) has-error @endif">
                                    {!! Form::label('lead_status_id', 'Lead Status') !!}
                                    {!! Form::select('lead_status_id', $statuses->pluck('name', 'id'),null, ['class' => 'form-control', 'id'=>'t_lead_status_id', 'placeholder'=>'Select status']) !!}
                                    @if ($errors->has('lead_status_id')) <p class="help-block">{{ $errors->first('lead_status_id') }}</p> @endif
                                    </div>
                                </div>
                                
                                @if (auth()->user()->is('super'))
                                    <div class="col-md-12">
                                        <div class="form-group @if ($errors->has('owner_id')) has-error @endif">
                                            {!! Form::label('owner_id', 'Lead Owner') !!}
                                            {!! Form::select('owner_id', $reps->pluck('name', 'user_id'),null, ['class' => 'form-control', 'id'=>'t_owner_id', 'placeholder'=>'Select lead owner']) !!}
                                            @if ($errors->has('owner_id')) <p class="help-block">{{ $errors->first('owner_id') }}</p> @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

        function editLead(lead_id) {
            var lead_status        = $("#edit_lead_"+lead_id).data('lead_status');
            var lead_owner  = $("#edit_lead_"+lead_id).data('lead_owner');
            if(lead_status=="0") {
                lead_status="";
            }
            if(lead_owner=="0") {
                lead_owner="";
            }
            console.log(lead_status);
            $("#update_lead_modal #t_lead_status_id").val(lead_status);
            $("#update_lead_modal #t_owner_id").val(lead_owner);
            $("#update_lead_modal #lead_id").val(lead_id);
            $("#update_lead_modal").modal();
        }
    </script>
@stop