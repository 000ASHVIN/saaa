<!
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New billing inormation</title>
</head>
<body>

<p>Dear Accounts.</p>
<p>Please note that the following user has entered debit order information</p>

<p>User: {{ $user->first_name }} {{ $user->last_name }}</p>

<p>
    Bank: {{ $user->debit->bank }} <br>
    Account Number: {{ $user->debit->number }} <br>
    Type: {{ $user->debit->type }} <br>
    Branch: {{ $user->debit->branch_name }} <br>
    Branch Code: {{ $user->debit->branch_code }} <br>
    Debit Date: {{ $user->debit->billable_date }} <br>
    OTP: {{ $user->debit->otp }} <br>
</p>
@include('emails.includes.disclaimer')

</body>
</html>