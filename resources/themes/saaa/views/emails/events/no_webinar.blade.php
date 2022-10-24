<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>No Webinar Links Available</title>
</head>
<body>

<p style="font-size: 12px; font-family: Helvetica, Arial;">Dear Admin</p>
<p style="font-size: 12px; font-family: Helvetica, Arial;">We could not send the webinar invites for {{ $event->name }} as there were no webinar links available to use.</p>
@include('emails.includes.disclaimer')
</body>
</html>