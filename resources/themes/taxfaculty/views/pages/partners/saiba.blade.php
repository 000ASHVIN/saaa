@extends('app')

@section('title')
    The Southern African Institute for Business Accountants
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('SAIBA') !!}
@stop

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <aside class="col-md-3">
                    <div class="sidebar">
                        <div class="block clearfix border-box">
                            <p><i class="fa fa-phone"></i> Tel: 012 643 1800 </p>

                            <p><i class="fa fa-envelope-o"></i> Email: <a
                                        href="mailto: support@saiba.org.za">support@saiba.org.za</a></p>

                            <p><i class="fa fa-link"></i> Web: <a href="www.saiba.org.za/" target="_blank">www.saiba.org.za/</a>
                            </p>
                        </div>

                        <br>
                        <a href="https://saiba.org.za/" target="_blank">
                            <img style="max-width: 100%" class="thumbnail" src="/assets/frontend/images/partners/saiba.png" alt="">
                        </a>
                        <a href="http://accountingweekly.com/" target="_blank">
                            <img style="max-width: 100%" class="thumbnail" src="/assets/frontend/images/partners/saiba/aw.jpg" alt="">
                        </a>

                    </div>
                </aside>

                <div class="col-md-7 main">
                    <h4>What we do..</h4>
                    <p>
                        SAIBA supports its members by adopting and implementing international standards relating to
                        ethics, quality, education, financial reporting, assurance and other engagements. SAIBA enables
                        the sharing of knowledge and assists members in understanding all areas affecting accountants
                        and financial professionals.
                    </p>
                    <p>
                        In a practical sense SAIBA offers:
                    </p>
                    <ul>
                        <li>A range of professional designations</li>
                        <li>Practicing licensing for independent review engagements</li>
                        <li>Career development</li>
                        <li>Quality control and monitoring</li>
                        <li>Enforcement of a code of conduct</li>
                        <li>Networking</li>
                        <li>Publications and events</li>
                        <li>Limited technical support</li>
                        <li>Member offers and discounts</li>
                        <li>An online community</li>
                    </ul>
                    <p>
                        Members can utilise the SAIBA online community to share information and organise events. Log in to access our community groups and news.
                    </p>
                </div>
            </div>
        </div>
    </section>
@stop