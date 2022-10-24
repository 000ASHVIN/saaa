<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>South African Accounting Academy</title>
</head>
<body>

<p>Dear Admin</p>
<p>Please check following user details for upgrade request</p>

<p>User Details:</p>
<p>
    Full Name: {{ $user->first_name }} {{ $user->last_name }}<br>
    Email: {{ $user->email }}<br>
    Current Plan Name: {{ $user->subscription('cpd')->plan->name }}<br>
    Requested Plan Name: {{ $plan->name }}<br>
    Contact Number: {{ $user->cell }}<br>
    Package Type: {{ $plan->package_type }}
</p>

</body>
</html>