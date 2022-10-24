<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Credit Note</title>
</head>
<body>
<table class="table" style="width: 90%">
    <tr style="height: 100px">
        <td></td>
    </tr>
    <tr>
        <td width="85"></td>
        <td style="padding-top: 20px;"><img src="{{ \URL::to('/') . "/assets/frontend/images/logo_light.png" }}" alt=""></td>
        <td width="180"></td>
        <td style="font-family: Helvetica, Arial; font-size: 20px; text-align: right;"><strong>Credit Note #{{ $credit->id }}</strong></td>
    </tr>
    <tr>
        <td width="85"></td>
        <td></td>
        <td></td>
        <td>
            <table class="table" width="100%" style="text-align: right">
                <tbody>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #173175">REFERENCE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464">{!! $credit->reference !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #173175">BILLING DATE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464"> {!! $credit->created_at->toFormattedDateString() !!}</td>
                </tr>
                <tr>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #173175">DUE DATE:</td>
                    <td colspan="1"></td>
                    <td style="font-family: Helvetica, Arial; font-size: 14px; color: #656464"> {!! $credit->created_at->toFormattedDateString() !!}</td>
                </tr>
                </tbody>
            </table>
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
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #173175; padding-bottom: 10px; border-bottom: 2px solid; border-color: #173175">OUR INFORMATION</td>
        <td style="width: 20px"></td>
        <td style="width: 400px ;font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #173175; padding-bottom: 10px; border-bottom: 2px solid; border-color: #173175">BILLING TO</td>
    </tr>
    <tr>
        <td style="width: 95px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px;"><br> SA Accounting Academy</td>
        <td style="width: 20px"></td>
        <td style="width: 400px ;font-family: Helvetica, Arial; font-size: 14px;"><br> {{ ($credit->invoice->user->profile->company) ?: $credit->invoice->user->first_name . ' ' . $credit->invoice->user->last_name }}</td>
    </tr>
    <tr>
        <td style="width: 95px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px;">
            <table>
                <br>
                <span style="line-height: 1.7em; font-family: Helvetica, Arial; font-size: 14px; color: #656464;">
                Vat Number: 4850255789 <br>
                Tel: 010 593 0466 <br>
                The Broadacres Business Centre <br>
                Cnr 3rd Ave & Cedar Road, Broadacres, <br>
                2191
            </span>
            </table>
        </td>
        <td style="width: 20px"></td>
        <td style="width: 400px; font-family: Helvetica, Arial; font-size: 14px; color: #656464; vertical-align: text-top;">
            <table>
                <br>
                @if($credit->invoice->user->getAddress("billing"))
                    <span style="line-height: 1.7em; font-family: Helvetica, Arial;">
                    {{ $credit->invoice->user->getAddress("billing")->line_one }} <br>
                        {{ $credit->invoice->user->getAddress("billing")->line_two }} <br>
                        {{ $credit->invoice->user->getAddress("billing")->city }} {{ $credit->invoice->user->getAddress("billing")->area_code }}<br>
                    </span>
                @else
                    <span style="line-height: 1.7em; font-family: Helvetica, Arial;">
                    {{ ($credit->invoice->user->profile->tax) ? 'Vat Number:' . $credit->invoice->user->profile->tax : $credit->invoice->user->email }}
                    </span>
                @endif
            </table>
        </td>
    </tr>
    <tr style="height: 50px">
        <td></td>
    </tr>
    </tbody>
</table>
<table style="width: 90%">
    <tbody>
    <tr>
        <td style="width: 95px"></td>
        <td style="font-family: Helvetica, Arial ;font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">PRODUCT</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">QUANTITY</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">UNIT PRICE</td>
        <td style="font-family: Helvetica, Arial ;text-align: center; font-size: 14px; font-weight: 600; color: #173175; font-size: 14px">TOTAL</td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td colspan="5" style="font-family: Helvetica, Arial; font-size: 14px; font-weight: 600; color: #173175; padding-bottom: 10px; border-bottom: 2px solid; border-color: #173175"></td>
    </tr>

    <tr style="height: 5px">
        <td></td>
    </tr>

    <tr>
        <td style="width: 95px"></td>
        <td width="45%" style="padding: 10px; font-family: Helvetica, Arial; font-size: 14px" bgcolor="#eaeaea">{{ "CN".$credit->id .' '.' - '.'Invoice #'. $credit->invoice->reference }}</td>
        <td width="15%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea"> 1 </td>
        <td width="15%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea"> - </td>
        <td width="15%" style="text-align: center; padding: 10px; font-family: Helvetica, Arial; font-size: 12px" bgcolor="#eaeaea">R {{ number_format($credit->amount / 100, 2) }}</td>
    </tr>
    </tbody>
</table>
</body>
</html>