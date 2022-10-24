@extends('app')

@section('title', 'My Support Tickets')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">My Support Tickets</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('content')
<style>
    .btn-category {
        margin-right: 5px;
        margin-bottom: 7px;
        border-width: 1px;
        height: 30px;
        line-height: 1;
    }
    .btn-category.active, .btn-category.active:hover, .btn-category.active:focus {
        background-color:#8cc03c;
        color: #ffffff;
    }
</style>
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::open(['method' => 'get', 'id' => 'search_support_tickets']) !!}
                        <div class="search-form">
                            {!! Form::hidden('search', '1') !!}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="title"><strong>Search</strong></label>
                                        {!! Form::input('text', 'title', request()->title, ['class' => 'form-control event-title-filter', 'placeholder' => 'Search Ticket']) !!}
                                    </div>
                                </div>
                
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="status"><strong>Ticket Status</strong></label>
                                        {!! Form::select('status', $statuses, request()->status, ['class' => 'form-control', 'placeholder' => 'Ticket Status']) !!}
                                        @if ($errors->has('status')) <p class="help-block">{{ $errors->first('status') }}</p> @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6" style="margin-top: 25px">
                                    <div class="form-group">
                                        <button class="btn btn-primary" onclick="search_spin(this)"><i class="fa fa-search"></i>Seach Ticket</button>
                                        @if(request()->search)
                                            <a href="{{ route('dashboard.support_tickets') }}" class="btn btn-warning"><i class="fa fa-close"></i> Clear Search</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if ($categories->count())
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" data-toggle="buttons">
                                            <label for="status"><strong>Ticket Category</strong></label>
                                            @foreach($categories as $key=>$cat)
                                                <?php
                                                    $active = '';
                                                    $checked = '';
                                                    if(request()->category==$cat->id) {
                                                        $active = 'active';
                                                        $checked = 'checked';
                                                    }
                                                ?>
                                                <button class="btn btn-default btn-category {{ $active }}">
                                                    <input type="radio" name="category" value="{{ $cat->id }}" autocomplete="off" {{ $checked }}>{{$cat->title}}
                                                </button>
                                            @endforeach 
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>

                <div class="form-group text-right">
                    <a href="{{ route('support_ticket.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-envelope-o"></i> Ask a Technical Question</a>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#support_ticket" class="btn btn-sm btn-primary"><i class="fa fa-question-circle"></i> Need Help?</a>
                </div>

                @if (count($tickets))

                    <div class="border-box">
                        <table class="table table-hover">
                            <thead>
                                <th>Date submitted</th>
                                <th width="40%">Title / subject</th>
                                <th>Status</th>
                                <th></th>
                                <th></th>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                    <tr style="display: table-row;">
                                        <td>
                                            {{ date( 'd F Y',strtotime($ticket->created_at)) }}
                                        </td>
                                        <td>
                                            {{ str_limit(ucfirst($ticket->title), '250') }}
                                        </td>
                                        <td>
                                            {{ $ticket->statusText }}
                                        </td>
                                        <td>
                                            <div class="label label-round label-success"><i class="fa fa-envelope-o"></i> {{ $ticket->replies }}</div>
                                        </td>
                                        <td>
                                            <a href="{{ route('support_ticket.show', $ticket->id) }}"><div class="label label-round label-primary">View Ticket <i class="fa fa-arrow-right"></i>&nbsp;</div></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="text-left">
                        {!! $tickets->render() !!}
                    </div>

                @else
                    <div class="alert alert-bordered-dotted margin-bottom-30 text-center">
                        <h4><strong><i class="fa fa-support"></i> My Support Tickets</strong></h4>
                        <p>You have no support tickets available</p>
                        <p>Need help? create a new support ticket by clicking on New Ticket</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script>
        $(document).ready(function(){
            $('input[name="category"]').on('change',function(){
                $('#search_support_tickets').submit();
            })
        });
    </script>
@stop