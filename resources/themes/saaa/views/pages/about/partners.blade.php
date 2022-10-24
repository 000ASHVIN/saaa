@extends('app')

@section('title')
    Our Partners
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('Partners') !!}
@stop

@section('content')

    <section class="alternate">
        <div class="container">
            <div class="col-md-12">
                <div class="margin-bottom-20"></div>
                <div style="background-color: white; padding: 10px">
                    <div class="row">
                        <div class="col-md-3">
                            <center>
                                <img src="/assets/frontend/images/sponsors/draftworx.jpg" width="80%" class="thumbnail"
                                     alt="Draftworx">
                            </center>
                        </div>
                        <div class="col-md-8">
                            <h4>Data Prime Solutions</h4>
                            <p>
                                Now fully integrated into all of Sage Pastel’s products, Draftworx (by Data Prime
                                Solutions) financial statements and working papers are quick to learn and easy to use.
                                Draftworx provides complete peace of mind on any engagement; audit, review or
                                compilation. Whether you operate on your desktop or in the cloud, Draftworx is fully
                                scalable and suitable for small entities and practices, to the largest firms and
                                corporates. Draftworx Cloud completely removes geographical boundaries and allows you to
                                collaborate with your client down the road, or your audit team on a completely different
                                continent.
                            </p>

                            <a href="/partners/draftworx">Read More..</a>
                        </div>
                    </div>
                </div>
                <div class="margin-bottom-20"></div>
                <div style="background-color: white; padding: 10px">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <center>
                                <img src="/assets/frontend/images/sponsors/saiba.jpg" width="80%" class="thumbnail"
                                     alt="SAIBA">
                            </center>
                        </div>
                        <div class="col-md-8">
                            <h4>The Southern African Institute for Business Accountants</h4>
                            <p>
                                SAIBA supports its members by adopting and implementing international standards relating
                                to ethics, quality, education, financial reporting, assurance and other engagements.
                                SAIBA enables the sharing of knowledge and assists members in understanding all areas
                                affecting accountants and financial professionals.
                            </p>
                            <a href="/partners/saiba">Read More..</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="javascript">
        $(document).ready(function () {
            $('#Carousel').carousel({
                interval: 5000
            })
        });
    </script>
@endsection