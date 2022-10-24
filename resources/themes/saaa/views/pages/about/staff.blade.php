@extends('app')

@section('content')

@section('title')
    Our Staff
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('staff') !!}
@stop

<section  class="alternate">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-4">
                <img src="https://accountingacademy.co.za/assets/frontend/images/teams/stiaan.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Stiaan Klue</h4>
                <h4><span class="label label-default-blue">Director</span></h4>
                <h4 class="small"><a href="mailto:info@accountingacademy.co.za">Info@accountingacademy.co.za</a></h4>
            </div>


            <div class="col-md-3 col-sm-6 col-xs-4">
                <img src="https://imageshack.com/a/img923/9567/9L938B.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Rob Murray</h4>
                <h4><span class="label label-default-blue">Director</span></h4>
                <h4 class="small"><a href="mailto:info@accountingacademy.co.za">Info@accountingacademy.co.za</a></h4>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container text-center">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-3">
                <img src="{{ asset('assets\themes\saaa\img\staff\Pieter Fourie_edited.jpg') }}" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Pieter Fourie</h4>
                <h4><span class="label label-default-blue">Accountant</span></h4>
                <h4 class="small"><a href="mailto:pieter@accountingacademy.co.za">pieter@accountingacademy.co.za</a></h4>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-3 ">
                <img src="http://imageshack.com/a/img922/6269/n7usMd.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Linky Chabalala</h4>
                <h4><span class="label label-default-blue">Trainee Accountant</span></h4>
                <h4 class="small"><a href="mailto:linky@accountingacademy.co.za">Linky@accountingacademy.co.za</a></h4>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-3">
                <img src="http://imageshack.com/a/img922/6763/vBreXR.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Agnes Mdaka</h4>
                <h4><span class="label label-default-blue">Debt Management</span></h4>
                <h4 class="small"><a href="mailto:agnes@accountingacademy.co.za">Agnes@accountingacademy.co.za</a></h4>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-3 ">
                <img src="https://accountingacademy.co.za/assets/frontend/images/teams/lerato.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Lerato Hlapolosa</h4>
                <h4><span class="label label-default-blue">Admin Assistant</span></h4>
                <h4 class="small"><a href="mailto:lerato@accountingacademy.co.za">Lerato@accountingacademy.co.za</a></h4>
            </div>
        </div>
    </div>
</section>


<section class="alternate">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-3 ">
                <img src="{{ asset('assets\themes\saaa\img\staff\Jeremiah Mgiba_edited.jpg') }}" class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Jeremiah Mgiba</h4>
                <h4><span class="label label-default-blue">CPD Coordinator</span></h4>
                <h4 class="small"><a href="mailto:jeremiah@accountingacademy.co.za">jeremiah@accountingacademy.co.za</a></h4>
            </div>
        </div>
    </div>
</section>

<section >
    <div class="container text-center">
        <div class="row">


            <div class="col-md-3 col-sm-6 col-xs-12 ">
                <img src="http://imageshack.com/a/img924/9726/8TEJi1.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Dayaan Pema</h4>
                <h4><span class="label label-default-blue">Sales Team Leader</span></h4>
                <h4 class="small"><a href="mailto:dayaan@accountingacademy.co.za">Dayaan@accountingacademy.co.za</a></h4>
            </div>

            {{-- <div class="col-md-3 col-sm-6 col-xs-12">
                <img src="http://imageshack.com/a/img922/1271/STvS0L.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Jarrod Victor</h4>
                <h4><span class="label label-default-blue">Sales Consultant</span></h4>
                <h4 class="small"><a href="jarrod:jarrod@accountingacademy.co.za">Jarrod@accountingacademy.co.za</a></h4>
            </div> --}}

            {{-- <div class="col-md-3 col-sm-6 col-xs-12 ">
                <img src="http://imageshack.com/a/img923/2447/6BgWPJ.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Wesley de Kock</h4>
                <h4><span class="label label-default-blue">Sales Consultant</span></h4>
                <h4 class="small"><a href="mailto:wesley@accountingacademy.co.za">Wesley@accountingacademy.co.za</a></h4>
            </div> --}}

            <div class="col-md-3 col-sm-6 col-xs-12 ">
                <img src="{{ asset('assets\themes\saaa\img\staff\Mbalentle Busakwe_edited.jpg') }}" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Mbalentle Busakwe</h4>
                <h4><span class="label label-default-blue">Sales Consultant</span></h4>
                <h4 class="small"><a href="mailto:mbalentle@accountingacademy.co.za">mbalentle@accountingacademy.co.za</a></h4>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12 ">
                <img src="{{ asset('assets\themes\saaa\img\staff\Priscelia Venketramen_edited.jpg') }}" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Priscelia Venketramen</h4>
                <h4><span class="label label-default-blue">Sales Consultant</span></h4>
                <h4 class="small"><a href="mailto:priscelia@accountingacademy.co.za">priscelia@accountingacademy.co.za</a></h4>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12 ">
                <img src="{{ asset('assets\themes\saaa\img\staff\Abdus Carelse_edited.jpg') }}" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Abdus Carelse</h4>
                <h4><span class="label label-default-blue">Sales Consultant</span></h4>
                <h4 class="small"><a href="mailto:abdus@accountingacademy.co.za">abdus@accountingacademy.co.za</a></h4>
            </div>

           

        </div>
    </div>
</section>

<section class="alternate">
    <div class="container text-center">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12 ">
                <img src="{{ asset('assets\themes\saaa\img\staff\Siphiwe Zwane_edited.jpg') }}" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Siphiwe Zwane</h4>
                <h4><span class="label label-default-blue">Sales Consultant</span></h4>
                <h4 class="small"><a href="mailto:siphiwe@accountingacademy.co.za">siphiwe@accountingacademy.co.za</a></h4>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12 ">
                <img src="{{ asset('assets\themes\saaa\img\staff\Cassabaine Petersen_edited.jpg') }}" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Cassabaine Petersen</h4>
                <h4><span class="label label-default-blue">Sales Consultant</span></h4>
                <h4 class="small"><a href="mailto:cassabaine@accountingacademy.co.za">cassabaine@accountingacademy.co.za</a></h4>
            </div>
        </div>
    </div>
</section>
@endsection