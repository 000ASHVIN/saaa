@extends('admin.layouts.master')

@section('title', 'Sales Reps')
@section('description', 'View all sales reps')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="col-md-12">
            <div class="row">
                <div class="alert alert-info">
                    <p>This sales agents will be emailed when the <strong>"HELP"</strong> form is submitted on our website</p>
                    <p>To create a new sales rep, simply click on <a href="{{ route('admin.reps.create') }}">"Add Sales Rep"</a></p>
                    <hr>
                    <p>To add a new "Actual User Account" to the sales rep, simply assign the Role <strong>sales</strong> to the member in the edit profile section.</p>
                </div>
                <table class="table">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Last Emailed</th>
                    <th class="text-center">Tools</th>
                    <tbody>
                    @foreach($reps->sortBy('emailedLast') as $rep)
                        <tr>
                            <td>{{ ucwords($rep->name) }}</td>
                            <td>{{ ucwords($rep->email) }}</td>
                            <td><i class="fa fa-envelope-o"></i> {{ $rep->emailedLast }}</td>
                            <td class="text-center">
                                @include('admin.reps.includes.edit')
                                {!! Form::open(['method' => 'POST', 'route' => ['admin.reps.destroy', $rep->id]]) !!}
                                    <div class="form-inline">
                                        <button type="button" class="form-control btn btn-sm btn-primary" data-target="#rep_{{$rep->id}}" data-toggle="modal"><i class="fa fa-pencil"></i> Edit Rep</button>
                                        <button class="form-control btn btn-sm btn-danger"><i class="fa fa-close"></i> Delete</button>
                                    </div>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <a href="{{ route('admin.reps.create') }}" class="btn btn-primary">Add Sales Rep</a>

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
    </script>
@stop