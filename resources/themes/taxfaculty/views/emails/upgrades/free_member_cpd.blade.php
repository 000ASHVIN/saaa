<!

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Upgrade took place for a member</title>
</head>
<body>

<p>
    <strong>This is the note on the account</strong>
</p>
<p>
    {!! ucfirst(str_replace('_', ' ', $note['type'])) !!}<br>
    {!! $note['description'] !!}<br>
    Agent: {!! $note['logged_by'] !!}<br>
</p>

<p>
    <strong>This is the member details</strong>
</p>
<p>
    Name: {!! $user['first_name'].' '.$user['last_name'] !!} <br>
    Cellphone: {!! $user['cell'] !!} <br>
    Email Address: {!! $user['email'] !!} <br>
    ID Number: {!! $user['id_number'] !!} <br>
    Payment Method: {!! ucfirst(str_replace('_', ' ', $user['payment_method'])) !!} <br>
</p>

<p>
    <strong>The Subscription details is as follows:</strong>
</p>

<p>
    New Package: {!! $user->subscription('cpd')->fresh()->plan->name !!} {!! $user->subscription('cpd')->fresh()->plan->interval !!}ly <br>
    New Price: R {!! number_format($user->subscription('cpd')->fresh()->plan->price, 2) !!}<br>
</p>

<p>
    <strong>If Payment method is **Debit Order** you need to update stratcol accordingly.</strong>
</p>

</body>
</html>