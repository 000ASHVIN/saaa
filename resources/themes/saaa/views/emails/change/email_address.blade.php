<!
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>South African Accounting Academy</title>
</head>
<body>

<p>Dear Admin</p>
<p>Please change my email address to {{ $new_email_address }}</p>

<p>My Current details are:</p>
<p>
    Full Name: {{ $user->first_name }} {{ $user->last_name }}<br>
    Old Email Address: {{ $user->email }}<br>
    ID Number: {{ $user->id_number }}<br>
    Contact Number: {{ $user->cell }}
</p>
@include('emails.includes.disclaimer')

</body>
</html>