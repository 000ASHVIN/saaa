<p>Dear lerato,</p>
<p>The following user would like to cancel their subscription:</p>
<p>Full Name: {{ $user['name'] }}</p>
<p>ID Number: {{ $user['id_number'] }}</p>
<p>Email Address {{ $user['email'] }}</p>
<p>Cell: {{ $user['cell'] }}</p>
@include('emails.includes.disclaimer')