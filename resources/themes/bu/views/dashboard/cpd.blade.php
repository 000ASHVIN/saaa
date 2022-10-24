@extends('app')

@section('title', 'Manage My CPD Hours')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">My CPD Hours</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">

                @if($user->body && count($user->body->designations) && $user->body->id != 5)
                    @if(count($user->cycles) <= 0)
                        <div class="heading-title heading-dotted text-center">
                            <h4>CPD <span>Cycle</span></h4>
                        </div>

                        <div class="alert alert-bordered-dotted margin-bottom-30 text-center">
                            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                            <h4><strong>Track your CPD compliance</strong></h4>

                            <p>Start tracking your CPD compliance today by simply creating a new cycle and selecting your designation.</p>
                            <hr>

                            <div class="text-center">
                                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#create_new_cpd_cycle">Create My Cycle</a>
                                @include('dashboard.includes.CpdCycle.create_cycle')
                            </div><br>
                        </div>

                    @else
                        @foreach($user->cycles as $cycle)
                            <div class="heading-title heading-border" style="margin-bottom: 10px;">
                                <h4><i class="fa fa-graduation-cap"></i> {{ $cycle->designation->title }}</h4>
                                <p class="font-lato size-16"> {{ date_format($cycle->start_date, 'd F Y') }} - {{ date_format($cycle->end_date, 'd F Y') }} </p>
                            </div>
                            @include('dashboard.includes.CpdCycle.cycle_progess')
                        @endforeach
                    @endif
                        <hr>
                @endif

                <div class="text-right">
                    <a href="#cpd" class="btn btn-primary btn-sm" data-target="#ajax"
                       data-toggle="modal"><span class="fa fa-plus"></span> Allocate CPD</a>
                    @include('dashboard.includes.cpd')
                </div>
                    
                <div class="margin-bottom-10"></div>

                <div class="table-responsive">
                    <table class="table table-bordered table-vertical-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th class="text-center">Category</th>
                                <th>CPD Source</th>
                                <th>CPD Hours</th>
                                <th>File</th>
                                <th class="text-center" colspan="3">Verifiable</th>
                            </tr>
                        </thead>
                        <tbody>

                        @if(count($cpds))
                            @foreach($cpds as $cpd)
                                <tr>
                                    <td>{{ $cpd->date->toFormattedDateString() }}</td>

                                    <td class="text-center">{{ ($cpd->category) ? ucfirst(str_replace('_', ' ', $cpd->category)): "-" }}</td>

                                    <td>{{ str_limit($cpd->source, 25) }}</td>

                                    <td>{{ $cpd->hours }} {{ ($cpd->hours > 1) ? 'Hours' : 'Hour' }}</td>
                                        @if($cpd->attachment)
                                            <td><a class="label label-default" href="{{ $cpd->attachment }}" target="_blank">Download</a></td>
                                        @elseif($cpd->hasCertificate())
                                            <td>
                                                <a class="label label-default" href="{{ route('dashboard.cpd.certificate',[$cpd->id]) }}">Certificate</a>
                                            </td>
                                        @else
                                            <td class="text-center">
                                                &nbsp;-&nbsp;
                                            </td>
                                        @endif


                                    <td class="text-center">
                                        @if($cpd->certificate)
                                           Yes
                                        @else
                                            No
                                        @endif
                                    </td>

                                    @if(! $cpd->certificate)
                                        <td class="text-center"><a style="color: white!important;" href="#" data-toggle="modal" data-target="#update_cpd_entry_{{$cpd->id}}" class="btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>
                                        {!! Form::open(['method' => 'Post', 'route' => ['dashboard.cpd.destroy', $cpd->id]]) !!}
                                            <td>
                                                <button type="button" class="btn-xs btn-primary" onclick="spin(this)"><i class="fa fa-close"></i></button>
                                            </td>
                                        {!! Form::close() !!}
                                    @else
                                        <td class="text-center"><a href="#" style="text-decoration: none; background-color: #777777; cursor: default" class="btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>
                                        <td class="text-center"><a href="#" style="text-decoration: none; background-color: #777777; cursor: default"  class="btn-xs btn-primary"><i class="fa fa-close"></i></a></td>
                                    @endif
                                    @include('dashboard.includes.cpd.update_cpd_entry')
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">
                                    <p>You currently have no CPD entries, would you like to add some now ?</p>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                    {!! $cpds->render() !!}
                </div>

            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $( ".cpd_date" ).datepicker().on('changeDate', function(e){
                $(this).datepicker('hide');
            });;
        });
    </script>
@endsection