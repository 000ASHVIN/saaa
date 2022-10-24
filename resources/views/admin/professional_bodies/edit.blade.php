@extends('admin.layouts.master')

@section('title', 'Edit Professional Body')
@section('description', 'Professional Body: '.ucfirst($body->title))

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                {!! Form::model($body, ['method' => 'PUT', 'route' => ['admin.professional_bodies.update', $body->id]]) !!}
                    @include('admin.professional_bodies.includes.form', ['button' => 'Save Professional Body'])
                {!! Form::close() !!}
            </div>
        </div>
    </section>

    <section class="alternate">
        <div class="container-fluid container-fullw padding-bottom-10">
            <div class="title">
                <div class="pull-left clearfix">
                    <h4>Professional Body designations</h4>
                </div>

                <div class="pull-right clearfix">
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#designation_new">Add new Designation to {{ $body->title }}</a>
                    @include('admin.professional_bodies.includes.create_designation_modal', ['submit' => 'Create New'])
                </div>
            </div>

            <br>
            <br>
            <hr>

            @if(count($body->designations))
                <table class="table table-striped">
                    <thead>
                        <th>Title</th>
                        <th class="text-center">Tax</th>
                        <th class="text-center">Ethics</th>
                        <th class="text-center">Auditing</th>
                        <th class="text-center">Accounting</th>
                        <th class="text-center">Verifiable</th>
                        <th class="text-center">Non Verifiable</th>
                        <th class="text-center">Total Hours</th>
                        <th></th>
                        <th></th>
                    </thead>

                    @foreach($body->designations as $designation)
                        <tr>
                            <td>{{ $designation->title }}</td>
                            <td class="text-center">{{ $designation->tax }}</td>
                            <td class="text-center">{{ $designation->ethics }}</td>
                            <td class="text-center">{{ $designation->auditing }}</td>
                            <td class="text-center">{{ $designation->accounting }}</td>
                            <td class="text-center">{{ $designation->verifiable }}</td>
                            <td class="text-center">{{ $designation->non_verifiable }}</td>
                            <td class="text-center">{{ $designation->total_hours }}</td>

                            <td width="10"><a data-target="#designation_update_{{$designation->id}}" data-toggle="modal" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a></td>
                            @include('admin.professional_bodies.includes.update_designation_modal', ['submit' => 'Update Designation'])

                            {!! Form::open(['method' => 'DELETE', 'route' => ['admin.professional_bodies.designation.destroy', $designation->id]]) !!}
                                <td width="10"><button class="btn btn-sm btn-warning"><i class="fa fa-close"></i></button></td>
                            {!! Form::close() !!}
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="alert alert-success">
                    <p>No Designations found for {{ ucfirst($body->title) }}</p>
                </div>
            @endif
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
        $('.select2').select2();
    </script>
@stop