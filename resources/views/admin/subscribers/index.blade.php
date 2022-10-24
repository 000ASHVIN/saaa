@extends('admin.layouts.master')

@section('title', 'Email Subscribers')
@section('description', 'List all email subscribers below')

@section('content')
    <div class="container-fluid container-fullw bg-white">
       @if(count($subscribers))
            <table class="table">
                <thead>
                <th>Email Address</th>
                <th>Signed up</th>
                <th>Remove From List</th>
                </thead>
                <tbody>
                @foreach($subscribers as $subscriber)
                    <tr>
                        <td>{!! $subscriber->email !!}</td>
                        <td>{!! date_format($subscriber->created_at, 'F d Y') !!}</td>
                        <td>
                            {!! Form::open(['Method' => 'Post', 'route' => ['DestroyNewsletterSubscribers', $subscriber->id]]) !!}
                                {!! Form::submit('Remove From List', ['class' => 'btn-xs btn btn-danger']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
           @else
        <p>Sorry, there are no email subscribers from our webiste yet..</p>
       @endif
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/assets/admin/vendor/moment/moment.min.js"></script>
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@endsection