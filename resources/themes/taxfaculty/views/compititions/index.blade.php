@extends('app')

@section('content')

@section('title')
    Update Your Profile and Win 1 of 5 CPD Prizes.
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('competitions') !!}
@stop

<section>
    <div class="container">
        <div class="row mix-grid">
            <div class="col-md-6">
                <div class="heading-title heading-dotted">
                    <h4><span>Update Your Profile and Win 1 of 5 CPD Prizes.</span></h4>
                </div>

                <p>
                    To qualify for a chance to win 1 of 5 CPD prizes, we’re asking you, our valued customer, to update your SA Accounting profile in full.
                </p>

                <p>Follow the steps below to enter:</p>
                <p><strong>How to enter: </strong></p>
                <ol>
                    <li>Log in to your profile <a href="/login" target="_blank">here</a> </li>
                    <li>Update your profile in full before 17 November 2017 at 23:59</li>
                </ol>

                <p><strong>Prize breakdown: </strong></p>
                <ul>
                    <li>1st prize Annual CPD Subscription package worth R4000</li>
                    <li>2nd prize Attend one seminar of your choice worth R950</li>
                    <li>3rd prize Watch two legislation updates of your choice worth R399 each</li>
                    <li>4th prize Attend any two webinars of your choice worth R399</li>
                    <li>5th prize Attend one webinar of your choice worth R399</li>
                </ul>

                <p>Official rules can be found here (insert Ts&Cs .pdf) and on our website. </p>

                <a href="{{ route('dashboard.edit') }}" class="btn btn-block btn-primary">Update my profile now</a>
            </div>
            <div class="col-md-6">
                <img width="90%" src="http://imagizer.imageshack.us/v2/800x600q90/924/khl4KA.png" alt="image" class="thumbnail">
            </div>
        </div>

        <div class="row">
            <div class="margin-bottom-30"></div>
            <div class="col-md-12">
                <div class="heading-title heading-dotted">
                    <h4><span>T&C's: 2017 Update Your Profile: Win 1 of 5 CPD Prizes </span></h4>
                </div>
                <p>
                    By participating in the {{ config('app.name') }} “2017 Update Your Profile: Win 1 of 5 CPD Prizes” promotion you accept the following terms and conditions:
                </p>
                <ul>
                    <li>The “2017 Update Your Profile: Win 1 of 5 CPD Prizes” promotion (“the Promotion”) is operated by {{ config('app.name') }}.</li>
                    <li>The Promotion is open to all {{ config('app.name') }} profile holders (both free and paid) over the age of 18 years. </li>
                    <li>The Promotion shall commence on 23 October 2017 and close on 17 November 17 November 2017 at 23:59 </li>
                    <li>The Promotion is not open to employees and their direct family members of {{ config('app.name') }}, including the employees and direct family members of their respective advertising and promotional agencies and distributors. </li>
                    <li>In order to enter the promotion and qualify to win, {{ config('app.name') }} members must log in to their profile and complete their profile in full. </li>
                    <li>Only profiles completed in full will be eligible for the draw. </li>
                    <li>The winner will be selected by way of a random draw overseen and certified by an independent admitted auditor. The winner will be notified telephonically or by email by no later than 07 November 2017.</li>
                    <li>The prizes are as follows: </li>
                </ul>

                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <td>1st prize</td>
                            <td>Annual CPD Subscription package worth R4000</td>
                        </tr>
                        <tr>
                            <td>2nd prize </td>
                            <td>Attend one seminar of your choice worth R950</td>
                        </tr>
                        <tr>
                            <td>3rd prize </td>
                            <td>Watch two legislation updates of your choice worth R399 each</td>
                        </tr>
                        <tr>
                            <td>4th prize </td>
                            <td>Attend any two webinars of your choice worth R399</td>
                        </tr>
                        <tr>
                            <td>5th prize </td>
                            <td>Attend one webinar of your choice worth R399</td>
                        </tr>
                    </tbody>
                </table>

                <ul>
                    <li>The judge’s decision shall be final and no correspondence will be entered into.</li>
                    <li>{{ config('app.name') }} collects and uses personal information about entrants to allow them to enter the promotion, comply with audit requirements in terms of the Consumer Protection Act and to determine the winner of the promotion. </li>
                    <li>{{ config('app.name') }} may use the winner’s name and/or images in marketing material but the winner will be informed of this beforehand and will be given an opportunity to refuse to permit any use of his/her name and/or image. </li>
                    <li>The prizes are not transferable, non-refundable, non-exchangeable and cannot be redeemed for cash. </li>
                    <li>In the event that the winner cannot be successfully contacted following all reasonable attempts to do so, {{ config('app.name') }} reserves the right to disqualify the winner from the Promotion and to draw another winner in his or her stead.</li>
                    <li>By participating in the Promotion, the winner agrees to release and hold {{ config('app.name') }} harmless against any and all losses, damages, rights, claims and actions of any kind in connection with this Promotion or resulting from acceptance, possession, or use of the prize, including, without limitation, personal injuries, death, property damage and claims based on defamation or invasion of privacy.</li>
                    <li>The prizes are subject to the following terms and conditions:
                        <ul>
                            <li>These prizes may be redeemed between November 2017 and 31 December 2018 </li>
                            <li>These prizes may not be refunded or redeemed for cash </li>
                            <li>These prizes may not be used to make payments against any amounts outstanding on a {{ config('app.name') }} account </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</section>
@endsection