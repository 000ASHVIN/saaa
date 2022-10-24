@extends('admin.layouts.master')

@section('title', 'Donations')
@section('description', 'Donations list')

@section('content')  

    <div class="row" style="padding-top:20px;">

        {!! Form::open(['method' => 'get']) !!}

            <div class="col-md-3">
                <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                    {!! Form::label('full_name', 'Search Name') !!}
                    {!! Form::input('text', 'full_name', \Input::has('full_name')?\Input::get('full_name'):"", ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if ($errors->has('full_name')) <p class="help-block">{{ $errors->first('full_name') }}</p> @endif
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    {!! Form::label('email', 'Search Email') !!}
                    {!! Form::input('text', 'email', \Input::has('email')?\Input::get('email'):"", ['class' => 'form-control', 'placeholder' => '']) !!}
                    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                </div>
            </div>
            
            <div class="col-md-3">
                    <div class="form-group">
                        <button type="button" style="width: 100%;top: 20px;" onclick="spin(this)" class="btn btn-primary btn-wide btn-scroll btn-scroll-top ti-search"><span>Search Now</span></button>
                    </div>
            </div>

            
        {!! Form::close() !!}

    </div>
    <br/>
    <div class="row" style="padding-top:20px;">

        {!! Form::open(['method' => 'get']) !!}

            <div class="col-md-3">
                <div class="form-group @if ($errors->has('from')) has-error @endif">
                    {!! Form::label('from', 'Select From Date') !!}
                    {!! Form::input('text', 'from', null, ['class' => 'form-control is-date']) !!}
                    @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group @if ($errors->has('to')) has-error @endif">
                    {!! Form::label('to', 'Select To Date') !!}
                    {!! Form::input('text', 'to', null, ['class' => 'form-control is-date']) !!}
                    @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
                </div>
            </div>
            
            <div class="col-md-3">
                    <div class="form-group">
                        <button type="button" style="width: 100%;top: 20px;" onclick="spin(this)" class="btn btn-primary btn-wide btn-scroll btn-scroll-top ti-search"><span>Search By Date</span></button>
                    </div>
            </div>

            
        {!! Form::close() !!}

    </div>
    <br/>
    <div class="row">
        <div style="padding: 10px; line-height: 34px;">
            <span>Total Donation: <strong>R{{ number_format($total_donations, 2) }}</strong></span>
            <span style="margin-left: 20px;">Today's Donation: <strong>R{{ number_format($todays_donations, 2) }}</strong></span>

            <div style="float: right; margin-bottom: 10px;">
                {!! Form::open(['method' => 'get', 'route' => 'admin.donations.export']) !!}
                    <input type="hidden" name="from" value="{{ isset($from) ? $from : '' }}">
                    <input type="hidden" name="to" value="{{ isset($to) ? $to : '' }}">
                    <button type="submit" class="btn btn-primary">Export</button>
                {!! Form::close() !!}
                {{-- <a href="{{ route('admin.donations.export')}}" class="btn btn-primary">Export</a> --}}
            </div>
        </div>
        <div class=" panel-white col-sm-12">
            <table class="table table-striped" id="sample-table-2">
                <thead>
                    <th>Results</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Cellphone</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date Time</th>
                </thead> 
                <br>
                <tbody>
                @if(count($donations)) 
                    <span style="display: none;">
                        {{ $i = 0 }}
                    </span>
                    @foreach($donations as $donation)

                  
                    @if($donation)
                        <span style="display: none;">
                            {{ $i++ }}
                        </span>
                        <tr>
                            <td width="1px"><span class="label label-body">{!! $i !!}</span></td>

                            <td>{{ $donation->first_name }} {{ $donation->last_name }}</td>

                            <td class="hidden-xs">
                                <a href="mailto:{{ $donation->email }}" rel="nofollow" target="_blank">
                                    {{ $donation->email }}
                                </a>
                            </td>
                            <td>{{ $donation->company_name }}</td>

                            <td>{{ $donation->cell }}</td>

                            <td>{{ $donation->amount }}</td>

                            <td>{{ $donation->status == 1 ? 'Successfull' : 'Unsuccessfull' }}</td>

                            <td>{{ $donation->created_at->format('d-m-Y H:i') }}</td>
 
                        </tr>
                        @endif
                    @endforeach
                @endif
                </tbody>
            </table>
            {!! $donations->appends(request()->except('page'))->render() !!}
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