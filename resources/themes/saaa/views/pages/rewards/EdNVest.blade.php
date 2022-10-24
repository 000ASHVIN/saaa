@extends('app')

@section('content')

@section('title')
    EdNVest
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('rewards') !!}
@stop

@section('styles')
    <style>
        .verticalLine {
            border-right: thick solid #e3e3e3;
        }
    </style>
@endsection

<section class="alternate">
    <div class="container">
        <div class="row">
            <div class="col-md-8 verticalLine">
                <div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
                    <div class="row">
                        <div class="col-md-12">
                           
                            <p><strong><span style="font-size:24px;">EdNVest</span></strong></p>

                            <p><span style="font-size:20px;"><strong>If it&rsquo;s too good to be true, then it probably is. Or is it?</strong></span></p>

                            <p><br />
                            EdNVest offers an exciting and unique product that leverages Section 10(1)(q) of the Income Tax Act, to create an additional stream of revenue and opportunities to attract new advisory clients.&nbsp;<br />
                            Section 10(1)(q) makes provision for a certain portion of education expenditure, incurred by a guardian for the benefit of a relative, to be non-taxable should they meet the requirements prescribed in the Income Tax Act. Simply put, if you or your Client qualify for the benefit, an amount of between R20,000 and R60,000 could be deducted from taxable income. Allowing you or your Client to receive more in the bank every month.</p>

                            <p><br />
                            <strong><span style="font-size:16px;">Who is EdNVest?</span></strong></p>

                            <p><br />
                            EdNVest is a team of people who are committed to empowering and creating unrealized value for the people of South Africa in a meaningful and easily accessible way. We do this by providing the platform for all qualifying individuals to make the most of the mechanisms available in the Income Tax Act, with particular focus on the bursary benefit in Section 10(1)(q) of the Income Tax Act. We are proud South Africans and our purpose is to inform and educate as many people as possible about the benefits of Section 10(1)(q), as a means to improving accessibility to quality education for all South Africans.&nbsp;</p>

                            <p><br />
                            <span style="font-size:16px;"><strong>Is there a cost to becoming an EdNVest Agent or Client?</strong></span></p>

                            <p><br />
                            The only cost to implementing the bursary benefit or acquiring new clients and generating revenue is the cost of your time. There are no setup fees, hidden costs, embedded commissions or membership fees. EdNVest carries all the costs of the infrastructure that supports its clients and agents and we pride ourselves in being a high-touch, easily accessible support partner to all our stakeholders. Sounds too good to be true? Keep reading.</p>

                            <p><br />
                            <span style="font-size:16px;"><strong>What do we offer?</strong></span></p>

                            <p><br />
                            &nbsp; &nbsp; &bull; EdNVest offers all our Agents and Clients the tools and expertise to seamlessly present, implement and manage non-taxable bursary benefits for all their clients or employees. The EdNVest tools and expertise allow for rapid sign up, training and activation of bursary benefits for all customers and employees on a digital platform.&nbsp;<br />
                            &nbsp; &nbsp; &bull; EdNVest provides an Online platform with own Multi-Company Admin for Agents to sign up, activate, manage and administrate the clients that have implemented the bursary benefit.<br />
                            &nbsp; &nbsp; &bull; EdNVest provides all the necessary marketing, presentation and training collateral for lead generation, conversion and implementation of the bursary benefit.<br />
                            &nbsp; &nbsp; &bull; EdNVest provides carefully prepared training and activation material relevant to Clients for Agents who have signed up Clients.<br />
                            &nbsp; &nbsp; &bull; EdNVest provides Agents and Clients with high-touch first tier support across the pre-sales, sales, activation and shared service functions.<br />
                            &nbsp; &nbsp; &bull; EdNVest provides the Agent with relevant legislative and compliance information.<br />
                            &nbsp; &nbsp; &bull; EdNVest provides the Agent with monthly sales reports on active bursaries and commission payouts.<br />
                            &nbsp; &nbsp; &bull; EdNVest distributes commissions monthly, upon receipt of the Agents valid tax invoice.<br />
                            &nbsp; &nbsp; &bull; EdNVest&rsquo;s Online Platform calculates the tax exemption and estimated PAYE for each bursary.<br />
                            &nbsp; &nbsp; &bull; EdNVest&rsquo;s Online Platform eliminates the administrative burden for Agents and Clients.<br />
                            &nbsp; &nbsp; &bull; EdNVest&rsquo;s platform serves as a repository for supporting documentation required by SARS and has an integrated expense tracker for monitoring actual expenditure.</p>

                            <p><br />
                            <span style="font-size:16px;"><strong>Where to from here?</strong></span></p>

                            <p><br />
                            Sign into your SA Accounting Academy profile and claim this reward.<br />
                            &nbsp;</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="/assets/frontend/images/sponsors/EdNVest logo.png" width="100%" class="thumbnail" alt="EdNVest">
                <h4>Need more information ?</h4>
                {!! Form::open(['method' => 'post', 'route' => ['rewards.store', 'saiba']]) !!}
                @include('questionnaire.includes.form', ['product' => ['product' => $product]])
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
    <script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@endsection
