@extends('admin.layouts.master')

@section('title', 'Members')
@if(isset($members) && $members instanceof \Illuminate\Pagination\LengthAwarePaginator)
@section('description', 'Found '.$members->total().' member(s)')
@else
@section('description', 'Found '.count($members).' member(s) for "'.$search.'"')

@endif

@section('content')  

   
          
            {!! Form::open(['method' => 'get', 'route' => 'admin.searchData']) !!}
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
            {!! Form::select('status', [
                '' => 'Select Status',
                'active' => 'active',
                'suspended' => 'suspended',
                'cancelled' => 'cancelled',
                'outstanding' => 'outstanding',
                'banned' => 'banned'

            ],\Input::has('status')?\Input::get('status'):"", ['class' => 'form-control']) !!}
            @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group @if ($errors->has('subscription')) has-error @endif">
            <label>Search Subscription</label>
            <select name="subscription" id="subscription" class="form-control">
                <option value="">Select Plan</option>
                @foreach($plan as $key=>$p)
                <option value="{{ $key }}">{{ $p }}</option>
                @endforeach
            </select>
            </div>
        </div>


        <div class="col-md-3">
            <div class="form-group @if ($errors->has('account')) has-error @endif">
            <label>Search Account</label>
            <select name="account" id="account" class="form-control">
                <option value="">Select Account</option>
                @foreach($rep as $k=>$r)
                <option value="{{ $k }}">{{ $r }}</option>
                @endforeach
            </select>
            </div>
        </div>

          <div class="col-md-3">
            <div class="form-group @if ($errors->has('body')) has-error @endif">
            <label>Search Professional Body</label>
            <select name="body" id="body" class="form-control">
                <option value="">Select Professional Body</option>
                @foreach($body as $b)
                <option value="{{ $b }}">{{ $b }}</option>
                @endforeach
            </select>
            </div> 
        </div>
        
        <div class="col-md-3">
                <div class="form-group">
                    <button type="button" style="width: 100%;top: 20px;" onclick="spin(this)" class="btn btn-primary btn-wide btn-scroll btn-scroll-top ti-search"><span>Search Now</span></button>
                </div>
        </div>  

        
           {!! Form::close() !!}
    
        
    <div class="row">
        <div class=" panel-white col-sm-12">
            <table class="table table-striped" id="sample-table-2">
                <thead>
                    <th>Results</th>
                    <th>Full Name</th>
                    <th class="hidden-xs">Email</th>
                    <th>Cellphone Number</th>
                    <th>Status</th>
                    <th>Subscription</th>
                    <th>Account</th>
                    <th>Professional body</th>
                    <th>View Profile</th>
                </thead> 
                <br>
                <tbody>
                @if(count($members)) 
                    <span style="display: none;">
                        {{ $i = 0 }}
                    </span>
                    @foreach($members as $member)

                  
                    @if($member)
                        <span style="display: none;">
                            {{ $i++ }}
                        </span>
                        <tr>
                            <td width="1px"><span class="label label-body">{!! $i !!}</span></td>

                            <td>{{ $member->first_name }} {{ $member->last_name }}</td>

                            <td class="hidden-xs">
                                <a href="mailto:{{ $member->email }}" rel="nofollow" target="_blank">
                                    {{ $member->email }}
                                </a>
                            </td>
                            <td>{{ $member->cell }}</td>

                            <td>
                                @if($member->status === 'active')
                                    <div class="label label-success">{{ $member->status }}</div>
                                @else
                                    <div class="label label-danger">{{ $member->status }}</div>
                                @endif
                            </td>
 
                            <td>
                                <div class="label label-plan">
                                      {{ ($member->subscription('cpd')?$member->subscription('cpd')->plan->name: "Member has no plan" ) }}
                                </div>
                            </td>  
 
                            <td>
         
                                    {{ ($member->subscription('cpd')?(($member->subscription('cpd')->agent_id>0)?($member->subscription('cpd')->SalesAgent()?$member->subscription('cpd')->SalesAgent()->name:""):""):"") }}
                            </td> 
 
                            <td>
                                <div class="label label-body">
                                    @if($member->profile && $member->profile->body)
                                        Member of {{ $member->profile->body }}
                                    @else
                                        None
                                    @endif
                                </div>
                            </td>
                            <td> 
                                @if($member->status != 'cancelled')
                                <a class="btn btn-default" href="{{ route('admin.members.show', $member->id) }}">View profile</a>
                                @else
                                <a href="#" data-toggle="modal" data-target="#whyIsThisUserBeenDeleted{{$member->id}}" class="btn btn-default">View Notes</a>
                                    <div id="whyIsThisUserBeenDeleted{{$member->id}}" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Account Information</h4>
                                                </div>
                                                <div class="modal-body">
                                                    {!! ($member->deleted_at_description)? : "No information to display"  !!}

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>

                        </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
            @if(isset($members) && $members instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {!! $members->appends(request()->except('page'))->render() !!}
            @endif
        </div>
    </div>
@stop

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop