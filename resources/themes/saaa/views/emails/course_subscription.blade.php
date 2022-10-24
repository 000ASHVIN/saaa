<!
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>South African Accounting Academy</title>
</head>
<body>

<p>Dear Admin</p>
<p>Please Check follow details for new course enrollment</p>

<p>User Details:</p>
<p>
    Full Name: {{ $subscription->user->first_name }} {{ $subscription->user->last_name }}<br>
    Email: {{ $subscription->user->email }}<br>
    Course Name: {{ $subscription->plan->name }}<br>
    Contact Number: {{ $subscription->user->cell }}
</p>
@include('emails.includes.disclaimer')

</body>
</html>