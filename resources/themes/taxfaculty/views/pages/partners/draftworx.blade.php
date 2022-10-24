@extends('app')

@section('title')
    Data Prime Solutions
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('DraftWorx') !!}
@stop

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <aside class="col-md-3">
                    <div class="sidebar">
                        <div class="block clearfix border-box">
                            <p><i class="fa fa-phone"></i> Tel: 011 201 2704 </p>
                            <p><i class="fa fa-envelope-o"></i> Email: <a href="mailto: info@dataprime.co.za">info@dataprime.co.za</a></p>
                            <p><i class="fa fa-link"></i> Web: <a href="www.dataprime.co.za" target="_blank">www.dataprime.co.za</a></p>
                        </div>

                        <br>
                        <img style="max-width: 100%" class="thumbnail" src="/assets/frontend/images/partners/draftworx.png" alt="">
                        <img style="max-width: 100%" class="thumbnail" src="/assets/frontend/images/partners/draftworx/sage.jpg" alt="">
                    </div>
                </aside>

                <div class="col-md-7 main">
                    <img src="/assets/frontend/images/partners/draftworx/header.jpg" style="max-width: 100%" alt="">
                    <br>

                    <p>
                        <b>Now fully integrated into all of Sage Pastel’s products,</b> Draftworx (by Data Prime
                        Solutions) <b>financial statements</b> and <b>working papers</b> are quick to learn and easy to
                        use. Draftworx provides complete peace of mind on any engagement; audit, <b>review or
                        compilation</b>. Whether you operate on your desktop or in the cloud, Draftworx is fully
                        scalable and suitable for small entities and practices, to the largest firms and corporates.
                        Draftworx Cloud completely removes geographical boundaries and allows you to collaborate with
                        your client down the road, or your audit team on a completely different continent.
                    </p>

                    <p>
                        Add on our premier audit methodology, R.A.C (developed in conjunction with Protect-A-Partner
                        International) for the ultimate in guided ISA compliance and automation.
                    </p>

                    <p>
                        Visit <a href="www.draftworx.com" target="_blank">www.draftworx.com</a> for more information on
                        why Draftworx is the new technology benchmark for the smart accounting practice of the future!
                    </p>

                    @if($products)
                        <br>
                        <div class="heading-title heading-dotted text-center">
                            <h4>Available Shop <span>Items</span></h4>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead>
                            <th>Product Year</th>
                            <th>Product Title</th>
                            <th colspan="2">Stock Quantity</th>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                @foreach($product->listings as $listing)
                                    @foreach($listing->products as $product)
                                        <tr>
                                            <td>{{ $product->year }}</td>
                                            <td>{{ $product->title }}</td>
                                            <td>{{ $product->stock }} in stock</td>
                                            <td><a href="/store/show/26?product=135">Purchase Now</a></td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </section>
@stop