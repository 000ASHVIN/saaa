<!
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Updated Billing Information</title>
</head>
<body>

<p>Dear Accounts.</p>
<p>Please note that the following user changed the payment method on their account</p>

<p>User: {{ $user->first_name }} {{ $user->last_name }}</p>

<p>
    Previous Payment Method: {{ str_replace('_', ' ', $previous) }} <br>
    New Payment Method: {{ str_replace('_', ' ', $user->payment_method) }}
</p>
@include('emails.includes.disclaimer')

</body>
</html>