 <html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Order</title>
    <style>
        .main_table .main_table_td {
            border: 2px solid  black;
            text-align: center;
        }

        .main_table {
            border-collapse: collapse;
            width: 90%;
            margin: 0 auto;
        }
tr{page-break-inside: avoid; 
           page-break-after: auto;}
    </style>
</head>
<body>
<table class="table" style="width: 90%">
    <tr style="height: 20px">
        <td></td>
    </tr>
    <tr>
        <td width="85"></td>
        <td style="padding-top: 20px;"><img src="{{ \URL::to('/') . "/assets/themes/taxfaculty/img/logo.png" }}" width="250px" style="margin-left: -50px" alt=""></td>
        <td width="120"></td>
        <td style="font-family: Helvetica, Arial; font-size: 20px; text-align: right;"><strong>CPD</strong></td>
    </tr>
    <tr>
        <td width="85"></td>
        <td></td>
        <td></td>
        <td>
           
        </td>
    </tr>

    <tr style="height: 20px">
        <td></td>
    </tr>
</table>
<?php
$user=auth()->user();
?>
<h2 style="font-family: Helvetica, Arial ;font-size: 26px; font-weight: 600; color: black; text-align: center;padding-bottom: 20px;">My CPD Logbook</h2>

    <table class="main_table">
        <tbody>
            <tr style="height: 35px;"> 
                @if($user->isPracticePlan())
                <td class="main_table_td" style="font-family: Helvetica, Arial ;font-size: 14px; font-weight: 600; color: black; font-size: 14px">Employee</td>
                @endif
                <td class="main_table_td" style="font-family: Helvetica, Arial ;font-size: 14px; font-weight: 600; color: black; font-size: 14px">Date</td>
                <td class="main_table_td" style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: black; font-size: 14px">Service Provider</td>
                <td class="main_table_td" style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: black; font-size: 14px">Category</td>
                <td class="main_table_td" style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: black; font-size: 14px">CPD Source</td>
                <td class="main_table_td" style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: black; font-size: 14px">CPD Hours</td>
                <td class="main_table_td" style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: black; font-size: 14px">Verifiable</td>
            </tr>

        @if(count($cpds))
            @foreach($cpds as $cpd)
            <tr>
                    @if($user->isPracticePlan())
                    <td class="main_table_td" width="10%">{{ $cpd->user->name }}</td>
                    @endif
                    <td class="main_table_td" width="10%">{{ ($cpd->date)?$cpd->date->toFormattedDateString():"" }}</td>
                    <td class="main_table_td" width="10%">{{ $cpd->service_provider }}</td>

                    <td class="main_table_td">{{ ($cpd->category) ? ucfirst(str_replace('_', ' ', $cpd->category)): "-" }}</td>

                    <td class="main_table_td" width="30%">{{ $cpd->source }}</td>

                    <td class="main_table_td">{{ $cpd->hours }} {{ ($cpd->hours > 1) ? 'Hours' : 'Hour' }}</td>
                    <td class="main_table_td">
                        @if($cpd->certificate)
                        Yes
                        @else
                            No
                        @endif
                    </td>
            </tr>
            @endforeach
        @endif
            <tr style="height: 60px">
                <td class="main_table_td" colspan="7" style="font-weight: 600;">Total: {{ $cpds_total_hours }} Hours</td>
            </tr>
    </tbody>

</table>
</body>
</html>