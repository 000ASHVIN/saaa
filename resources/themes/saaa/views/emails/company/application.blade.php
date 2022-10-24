<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>New Company Application</title>
</head>
<body>

<p>Hi There</p>
<p>We have received a new company application for {{ $user['company'] }}</p>

<p>Below details for the client</p>
<p>
    First Name: {{ $user['last_name'] }} <br>
    Last Name: {{ $user['first_name'] }} <br>
    ID Number: {{ $user['id_number'] }} <br>
    Email Address: {{ $user['email'] }} <br>
    Cell: {{ $user['cell'] }} <br>
    Alternative Cell: {{ $user['alternative_cell'] }} <br>
    Plan: {{ $user['selected_plan'] }} <br>
</p>
<br>
<p>Company Information</p>
<p>
   Company: {{ $user['company'] }} <br>
   Company VAT: {{ $user['company_vat'] }} <br>
   Total Employees: {{ $user['employees'] }} <br>
</p>
<br>
<p>Address Information</p>
<p>
    City: {{ $user['city'] }} <br>
    Country: {{ $user['country'] }} <br>
    Province: {{ $user['province'] }} <br>
    Area Code: {{ $user['area_code'] }} <br>
    Address Line One: {{ $user['address_line_one'] }} <br>
    Address Line Two: {{ $user['address_line_two'] }} <br>
</p>
@include('emails.includes.disclaimer')

</body>
</html>