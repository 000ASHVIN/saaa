<!
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>South African Accounting Academy</title>
</head>
<body>

<p>Dear Admin</p>
<p>Please change my ID Number to {{ $new_id_number }}</p>

<p>My Current details are:</p>
<p>
    Full Name: {{ $user->first_name }} {{ $user->last_name }}<br>
    Email Address: {{ $user->email }}<br>
    Old ID Number: {{ $user->id_number }}<br>
    Contact Number: {{ $user->cell }}
</p>
@include('emails.includes.disclaimer')

</body>
</html>