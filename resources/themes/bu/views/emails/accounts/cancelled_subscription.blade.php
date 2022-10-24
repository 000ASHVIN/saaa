<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cancelled CPD subscription</title>
</head>
<body>
    <p style="font-family: Helvetica, Arial; font-size: 14px">Dear Admin</p>
    <p style="font-family: Helvetica, Arial; font-size: 14px">Please note that the following user has cancelled CPD Subscription. If the client payment method is on Debit Order, please remove from stratcol immediately</p>
    <p style="font-family: Helvetica, Arial; font-size: 14px">
        First Name: {{ $user['first_name'] }}<br>
        Last Name: {{ $user['last_name'] }}<br>
        Email : {{ $user['email'] }}<br>
        ID Number: {{ $user['id_number'] }}<br>
        Contact Number: {{ $user['cell'] }}<br>
        Payment Method: {{ ucfirst(str_replace('_', ' ', $user['payment_method'])) }}<br>
    </p>
    <p style="font-family: Helvetica, Arial; font-size: 14px">Kind Regards, <br> System Administrator</p>
</body>
</html>