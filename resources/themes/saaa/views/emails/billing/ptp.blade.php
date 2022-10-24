<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Promise to pay clients</title>
</head>
<body>

<p>Dear Accounts.</p>

<p>The following clients has promissed to pay but is still outstanding.</p>
@foreach($notes as $note)
    <p>Type: {{ $note->type }}</p>
    <p>User: {{ $note->user->first_name }} {{ $note->user->last_name }}</p>
    <p>Email: {{ $note->user->email }}</p>
    <p>Cellphone: {{ $note->user->cell }}</p>
    <p>Descritpion: {{ $note->description }}</p>
    <br>
    <hr>
    <br>
@endforeach

<p>Please note that if you did receive the funds, please mark this payment arrangement as completed on the user profile.</p>

<p>
    Kind Regards <br>
    The System!
</p>
@include('emails.includes.disclaimer')

</body>
</html>