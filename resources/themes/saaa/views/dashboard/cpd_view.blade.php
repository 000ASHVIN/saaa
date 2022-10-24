 <html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Order</title>
</head>
<body>
<table class="table" style="width: 90%">
    <tr style="height: 20px">
        <td></td>
    </tr>
    <tr>
        <td width="85"></td>
        <td style="padding-top: 20px;"><img src="{{ \URL::to('/') . "/assets/frontend/images/logo_light.png" }}" width="250px" style="margin-left: -50px" alt=""></td>
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

<table style="width: 90%">
    <tbody>
    <tr>
        <td style="width: 95px"></td>
        <td style="font-family: Helvetica, Arial ;font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">Date</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">Service Provider</td>

        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">Category</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">CPD Source</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">CPD Hours</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">File</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">Verifiable</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td colspan="7" style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #173175; padding-bottom: 10px; border-bottom: 2px solid; border-color: #173175"></td>
    </tr>

    <tr style="height: 5px">
        <td></td>
    </tr>


    
    @if($cpds->count())
        @foreach($cpds as $cpd)
            <tr>
                    <td style="width: 95px"></td>
                <td width="10%">{{ ($cpd->date)?$cpd->date->toFormattedDateString():"" }}</td>
                <td  width="10%">{{ $cpd->service_provider }}</td>

                <td class="text-center">{{ ($cpd->category) ? ucfirst(str_replace('_', ' ', $cpd->category)): "-" }}</td>

                <td>{{ str_limit($cpd->source, 25) }}</td>

                <td>{{ $cpd->hours }} {{ ($cpd->hours > 1) ? 'Hours' : 'Hour' }}</td>
                    @if($cpd->attachment)
                        <td><a class="label label-default" href="{{ $cpd->attachment }}" target="_blank">Download</a></td>
                    @elseif($cpd->hasCertificate())
                        <td>
                            <a class="label label-default" href="{{ route('dashboard.cpd.certificate',[$cpd->id]) }}">Certificate</a>
                        </td>
                    @else
                        <td class="text-center">
                            &nbsp;-&nbsp;
                        </td>
                    @endif


                <td class="text-center">
                    @if($cpd->certificate)
                       Yes
                    @else
                        No
                    @endif
                </td>

           
              
            </tr>
        @endforeach

        @endif

    </tbody>
</table>

{{--<table width="90%">--}}
{{--<tr style="height: 10px">--}}
{{--<td></td>--}}
{{--</tr>--}}
{{--<tr>--}}
{{--<td style="width: 0px"></td>--}}
{{--<td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #173175; padding-bottom: 10px;">SNAPSCAN</td>--}}
{{--</tr>--}}

{{--<tr>--}}
{{--<td style="width: 0px"></td>--}}
{{--<td colspan="5" style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #173175; padding-bottom: 10px; border-bottom: 2px solid; border-color: #173175"></td>--}}
{{--</tr>--}}

{{--<tr>--}}
{{--<td style="width: 0px"></td>--}}
{{--<td style="width: 90%;  font-family: Helvetica, Arial; font-size: 14px;">--}}
{{--<table>--}}
{{--<br>--}}
{{--<span style="line-height: 1.7em; font-family: Helvetica, Arial; font-size: 14px; color: #656464;">--}}
{{--<img src="https://imageshack.com/a/img924/934/ePqYDz.png" alt="" style="float: left; max-width: 15%;">--}}
{{--Did you know that you can settle this purchase order using snapscan ? Please use the purchase order reference as the reference and the purchase order balance as the amount. If the incorrect reference is used, this will result in a delay in allocating your payment--}}
{{--</span>--}}
{{--</table>--}}
{{--</td>--}}
{{--</tr>--}}
{{--</table>--}}
</body>
</html>