<!
<html>
<head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>Webinar Invite Report</title>
</head>
<body>
        <p><strong>The following users was notified about the webinar {{ $event->name }}</strong></p>
        <table class="table">
        <thead>
                <th style="text-align: left">Attendee Name</th>
                <th style="text-align: left">Attendee Email</th>
                <th style="text-align: left">Event</th>
        </thead>
        <tbody>
        @foreach($users as $user)
                <tr>
                        <td>{{ $user->first_name.' '.$user->last_name }}</td>
                        <td>{{ strtolower($user->email) }}</td>
                        <td>{{ ucfirst($event->name) }}</td>
                </tr>
        @endforeach
        </tbody>
        </table>
        @include('emails.includes.disclaimer')
</body>
</html>