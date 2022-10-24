@extends('admin.layouts.master')

@section('title', 'Professional Bodies')
@section('description', 'All Professional Bodies')

@section('content')
    <section>
        <br>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <table class="table table-responsive table-striped">
                <thead>
                    <th style="width: 20%">Professional Body</th>
                    <th style="width: 20%">Email Address</th>
                    <th style="width: 5%" class="text-center">CPD Packages</th>
                    <th style="width: 20%" class="text-center" colspan="1">Tools</th>
                </thead>
                <tbody>
                @if(count($bodies))
                    @foreach($bodies as $body)
                        <tr>
                            <td>{{ $body->title }}</td>
                            <td>{{ $body->email }}</td>
                            <td class="text-center">{{ count($body->plans)}} Available </td>
                            <td style="width: 5%;" class="text-center">
                                <a class="btn btn-info" href="{{ route('admin.professional_bodies.edit', $body->id) }}"><i class="ti-pencil"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">No Professional Bodies Available</td>
                    </tr>
                @endif
                </tbody>

            </table>

            <a href="{{ route('admin.professional_bodies.create') }}" class="btn btn-wide btn-success">Create new</a>
        </div>    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop