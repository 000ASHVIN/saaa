<html>

<head>
    <meta charset="UTF-8">
    <title>Certificate</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic'
          rel='stylesheet' type='text/css'>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans', sans-serif;
        }

        table.certificate {
            border-collapse: collapse;
            table-layout: fixed;
            /*max-width: 745px;*/
            max-width: 210mm;
            height: 296mm;
            @if($pdf)
               background: url('{{ Theme::asset('cpd/bg.png') }}') no-repeat;
            @else
               background: url('{{ Theme::asset('cpd/bg.png') }}') no-repeat;
            @endif
            background-size: 100%;
        }

        .line {
            width: 80%;
            border-bottom: 2px solid #e3e3e3;
            line-height: 0.1em;
            margin: 0 72px 0;
            height: 0px !important;
        }

        .line-2 {
            width: 80%;
            text-align: center;
            border-bottom: 2px solid #e3e3e3;
            line-height: 0.1em;
            margin: 5px 50px 0;
        }

        table.certificate td.row {
            width: 100%;
        }

        td.certificate-logo {
            padding: 0;
            margin: 0;
            text-align: center;
        }

        td.certificate-type {
            text-align: center;
            width: 100%;
            font-size: 30px;
            text-transform: uppercase;
            color: #323364;
            line-height: 30px;
            font-weight: bold;
        }

        td.presented-to-title {
            text-align: center;
            text-transform: uppercase;
            color: #323364;
            font-weight: 600;
            font-size: 16px;
            line-height: 25px;
        }

        td.presented-to {
            text-align: center;
            text-transform: uppercase;
        }

        td.certificate-for-title {
            text-align: center;
            text-transform: uppercase;
            color: #323364;
            font-weight: 600;
        }

        td.certificate-for {
            text-align: center;
            text-transform: uppercase;
            font-weight: 600;
        }

        table.sub-table {
            table-layout: fixed;
            width: 100%;
        }

        td.hours-cpd-badge {
            width: 30%;
        }

        td.hours-cpd {
            @if($pdf)
                                 background: url('{{ Theme::asset('cpd/badge-top.png') }}') no-repeat bottom center;
            @else
                                 background: url('{{ Theme::asset('cpd/badge-top.png') }}') no-repeat bottom center;
            @endif
                                 width: 183px;
            height: 76px;
            text-align: center;
            vertical-align: bottom;
            color: white;
            font-size: 26px;
            padding-right: 5px;
        }

        td.hours-cpd-title {
            @if($pdf)
                                       background: url('{{ Theme::asset('cpd/badge-bottom.png') }}') no-repeat top center;
            @else
                                       background: url('{{ Theme::asset('cpd/badge-bottom.png') }}') no-repeat top center;
            @endif
                                       width: 183px;
            height: 43px;
            text-align: center;
            vertical-align: top;
            padding-top: 8px;
            font-size: 11px;
        }

        td.presenter-and-date {
            width: 70%;
            text-align: center;
            text-transform: uppercase;
        }

        div.presenter-title {
            color: #323364;
            font-weight: 600;
            text-transform: uppercase
        }

        div.date-title {
            color: #323364;
            font-weight: 600;
            text-transform: uppercase
        }

        td.logo {
            text-align: center;
            height: 82px;
        }

        .spacing {
            margin: 20px;
        }

        .space-2 {
            margin: 20px;
        }
        td.accredited-logo img {
            width: 50%;
        }
        td.accredited-logo p {
            color: #003865;
        }
    </style>
</head>

<body>
<table class="certificate" width="100%">
    <tr style="height: 140px;">
        <td class="certificate-logo">
            @if($pdf)
                <img src="{{ Theme::asset('cpd/logo.png') }}"
                     alt="{{ config('app.name') }}">
            @else
                <img src="{{ Theme::asset('cpd/logo.png') }}" alt="{{ config('app.name') }}">
            @endif
        </td>
    </tr>
    <tr>
        <td class="certificate-type row">
            Certificate <br> of <br> Completion
        </td>
    </tr>
    <tr style="height: 10px">
        <td class="presented-to-title row">
            Presented To
        </td>
    </tr>
    <tr style="height: 10px">
        <td class="presented-to row">{{ $cpd->user->first_name . ' ' . $cpd->user->last_name }}</td>
    </tr>
    <tr style="height: 20px">
        <td>
            <div class="line"></div>
        </td>
    </tr>
    <tr style="height: 20px;">
        <td>
            <div class="spacing"></div>
        </td>
    </tr>
    <tr style="height: 10px">
        <td class="certificate-for-title">
            for completing the assessment
        </td>
    </tr>
    <tr style="height: 10px">
        <td class="certificate-for">
            {{ $cpd->certificate->source->events()->first()->name ?? $cpd->certificate->source->title }}
        </td>
    </tr>
    <tr style="height: 20px">
        <td>
            <div class="line"></div>
        </td>
    </tr>
    <tr>
        <td class="row">
            <table class="sub-table">
                <tr>
                    <td class="hours-cpd-badge">
                        <table class="sub-table" style="table-layout: fixed; border-collapse: collapse;">
                            <tr>
                                <td class="hours-cpd">
                                    {{ $cpd->hours }}
                                </td>
                            </tr>
                            <tr>
                                <td class="hours-cpd-title">
                                    {{$cpd->certificate->source->hasPassed() ? "Verifiable CPD Hour(s)" : "Hour(s) CPD"}}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="presenter-and-date">
                        <div class="presenter">
                            {{ $cpd->certificate->source->passedAttempts->where('user_id', $cpd->user_id)->first()->percentage }}%
                        </div>
                        <div class="line-2"></div>
                        <div class="presenter-title">Assessment Result</div>
                        <div class="space-2"></div>
                        <div class="date">
                            {{ $cpd->certificate->
                            source->
                            passedAttempts->
                            where('user_id', $cpd->user_id)->
                            first()->
                            created_at->
                            toFormattedDateString() }}
                        </div>
                        <div class="line-2"></div>
                        <div class="date-title">Date</div>

                        @if ($cpd->certificate->source && $cpd->certificate->source->events()->count() &&count($cpd->certificate->source->events()->first()->presenters))
                            <div class="space-2"></div>
                            <div class="presenter">
                                {{ implode(', ',$cpd->certificate->source->events()->first()->presenters->pluck('name')->toArray()) }}
                            </div>
                            <div class="line-2"></div>
                            <div class="presenter-title">Presenter</div>        
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    @if($cpd->certificate->source && $cpd->certificate->source->events()->count() && ($cpd->certificate->source->events()->first()->id == 355 || $cpd->certificate->source->events()->first()->id == 354))
    <tr style="height: 140px;">
        <td class="accredited-logo">
            <p>Accredited by:</p>
            @if($pdf)
                <img src="{{ Theme::asset('cpd/FISA.jpg') }}"
                     alt="{{ config('app.name') }}">
            @else
                <img src="{{ Theme::asset('cpd/FISA.jpg') }}" alt="{{ config('app.name') }}">
            @endif
        </td>
    </tr>
    @endif
    @if($cpd->certificate->source && $cpd->certificate->source->events()->count() && in_array($cpd->certificate->source->events()->first()->id,[339,345,346,347,348]))
    <tr style="height: 140px;">
        <td class="accredited-logo">
            {{-- <p>Accredited by:</p> --}}
            @if($pdf)
                <img src="{{ Theme::asset('cpd/fpi.png') }}"
                     alt="{{ config('app.name') }}">
            @else
                <img src="{{ Theme::asset('cpd/fpi.png') }}" alt="{{ config('app.name') }}">
            @endif
        </td>
    </tr>
    @endif
    {{-- <tr>
        <td class="logos row">
            <table class="sub-table">
                <tr>
                    <td class="saiba logo">
                        Accredited Tax CPD provider by:
                        @if($pdf)
                            <img style="width: 100%"
                                 src="{{ Theme::asset('cpd/sait.png') }}"
                                 alt="SAIT">
                        @else
                            <img style="width: 100%" src="{{ Theme::asset('cpd/sait.png') }}"
                                 alt="SAIT">
                        @endif
                        <img src="" alt="">
                    </td>
                    <td></td>
                    <td class="acca logo"> --}}
                        {{--@if($pdf)--}}
                        {{--<img src="{{ public_path('assets/frontend/certificates/cpd/images/acca.png') }}" alt="ACCA">--}}
                        {{--@else--}}
                        {{--<img src="/assets/frontend/certificates/cpd/images/acca.png" alt="ACCA">--}}
                        {{--@endif--}}
                    {{-- </td>
                </tr>
            </table>
        </td>
    </tr> --}}
</table>

</body>

</html>
