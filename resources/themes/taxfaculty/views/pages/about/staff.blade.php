@extends('app')

@section('meta_tags')
    <title>The Team | {{ config('app.name') }}</title>
    <meta name="description" content="Our entire team here at The Tax Faculty are dedicated to enhancing the practice of taxation by enhancing the higher order thinking in tax education.">
    <meta name="Author" content="{{ config('app.name') }}"/>
@endsection

@section('content')

@section('title')
    Our Team
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('staff') !!}
@stop

<style>
    
		img.thumbnail.rounded {
		width: 225px !important;
		height: 225px !important;
}
.staffPage h4 span.label-default-blue {
    white-space: pre-wrap;
    padding: 6px 10px;
	text-transform: none!important;
}

  .staffPage h4 {  text-transform: uppercase;}
  .staffPage h4.small {  text-transform: none!important;}

    </style>
    
<section class="alternate">
    <div class="container text-center staffPage">
        <div class="row">
            <h3 class="text-center">The Tax Faculty Board Members</h3>
        </div>
        
        <div class="row">
            <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/Annett_Ogutto.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>PROF ANET OGUTTU</h4>
                {{-- <h4><span class="label label-default-blue">Executive Dean & CEO</span></h4>
                <h4 class="small"><a href="mailto:sklue@taxfaculty.ac.za">sklue@taxfaculty.ac.za</a></h4> --}}
            </div>

            <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/Lerato_Legadima.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Lerato Legadima</h4>
                {{-- <h4><span class="label label-default-blue">Head of Logistics & Enablement</span></h4>
                <h4 class="small"><a href="mailto:iwhitehead@taxfaculty.ac.za">iwhitehead@taxfaculty.ac.za</a></h4> --}}
            </div>

            <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/Craig_Hirst.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Craig Hirst</h4>
                {{-- <h4><span class="label label-default-blue">Head of Transformation & Development</span></h4>
                <h4 class="small"><a href="mailto:vfox@taxfaculty.ac.za">vfox@taxfaculty.ac.za</a></h4> --}}
            </div>
            <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/Neill Wright.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Neill Wright</h4>
                {{-- <h4><span class="label label-default-blue">Head of Learner Management</span></h4>
                <h4 class="small"><a href="mailto:jvosloo@taxfaculty.ac.za">jvosloo@taxfaculty.ac.za</a></h4> --}}
            </div>
			
            <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/Rodney_Smith.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Rodney Smith</h4>
                {{-- <h4><span class="label label-default-blue">Head of Content </span></h4>
                <h4 class="small"><a href="mailto:kvanwyk@taxfaculty.ac.za">kvanwyk@taxfaculty.ac.za</a></h4> --}}
            </div>

        </div>
    </div>
</section>
<section>
    <div class="container text-center staffPage">
        <div class="row">
            <h3 class="text-center">Executive Office</h3>
        </div>
        
        <div class="row">
            <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/stiaan.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>STIAAN KLUE</h4>
                <h4><span class="label label-default-blue">Executive Dean & CEO</span></h4>
                <h4 class="small"><a href="mailto:sklue@taxfaculty.ac.za">sklue@taxfaculty.ac.za</a></h4>
            </div>

            <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/ingrid.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>INGRID WHITEHEAD</h4>
                <h4><span class="label label-default-blue">Head of Logistics & Enablement</span></h4>
                <h4 class="small"><a href="mailto:iwhitehead@taxfaculty.ac.za">iwhitehead@taxfaculty.ac.za</a></h4>
            </div>

            <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/vanessa.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>VANESSA FOX</h4>
                <h4><span class="label label-default-blue">Head of Transformation & Development</span></h4>
                <h4 class="small"><a href="mailto:vfox@taxfaculty.ac.za">vfox@taxfaculty.ac.za</a></h4>
            </div>
            <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/janet.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>JANET VOSLOO</h4>
                <h4><span class="label label-default-blue">Head of Learner Management</span></h4>
                <h4 class="small"><a href="mailto:jvosloo@taxfaculty.ac.za">jvosloo@taxfaculty.ac.za</a></h4>
            </div>
			
            {{-- <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Karen van Wyk.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Karen van Wyk</h4>
                <h4><span class="label label-default-blue">Head of Content </span></h4>
                <h4 class="small"><a href="mailto:kvanwyk@taxfaculty.ac.za">kvanwyk@taxfaculty.ac.za</a></h4>
            </div> --}}

        </div>
    </div>
</section>

<section class="alternate">
    <div class="container text-center staffPage">
	
       
        <div class="row">
            <!-- <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/Alicia Chabalala.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Alicia Chabalala</h4>
                <h4><span class="label label-default-blue">Compliance Officer</span></h4>
                <h4 class="small"><a href="mailto:compliance@taxfaculty.ac.za">compliance@taxfaculty.ac.za</a></h4>
            </div> -->

            {{-- <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/Anelle Lombard.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Anélle Lombard</h4>
                <h4><span class="label label-default-blue">Executive Secretary</span></h4>
                <h4 class="small"><a href="mailto:iwhitehead@taxfaculty.ac.za">iwhitehead@taxfaculty.ac.za</a></h4>
            </div> --}}

            <div class="col-md-5th col-sm-6 col-xs-12">
                <img src="/assets/themes/taxfaculty/img/staff/Liezl Mclean.png" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Liezl Maclean</h4>
                <h4><span class="label label-default-blue">Communication and Marketing Lead</span></h4>
                <h4 class="small"><a href="mailto:students@taxfaculty.ac.za">students@taxfaculty.ac.za</a></h4>
            </div>
            {{--  <div class="col-md-5th col-sm-6 col-xs-12">
                <img src="/assets/themes/taxfaculty/img/staff/Kim_Bekker.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Kim Bekker</h4>
                <h4><span class="label label-default-blue">Executive Secretary & Business Development Supervisor</span></h4>
                <h4 class="small"><a href="mailto:executiveoffice@taxfaculty.ac.za">executiveoffice@taxfaculty.ac.za</a></h4>
            </div>  --}}
            <div class="col-md-5th col-sm-6 col-xs-12">
                <img src="/assets/themes/taxfaculty/img/staff/Ontiretse_Seroalo.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Ontiretse Seroalo</h4>
                <h4><span class="label label-default-blue">Graphic Designer</span></h4>
                <h4 class="small"><a href="mailto:designer@taxfaculty.ac.za">designer@taxfaculty.ac.za</a></h4>
            </div>

            <div class="col-md-5th col-sm-6 col-xs-12">
                <img src="/assets/themes/taxfaculty/img/staff/Rejoice_Mashigo.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Rejoice Mashigo</h4>
                <h4><span class="label label-default-blue">Financial Controller</span></h4>
                <h4 class="small"><a href="mailto:accounts@taxfaculty.ac.za">accounts@taxfaculty.ac.za</a></h4>
            </div>
        </div>
    </div>
</section>

<section  >
    <div class="container text-center staffPage">
		<div class="row">
			<h3 class="text-center">LOGISTICS & ENABLEMENT</h3>
		</div>
        <div class="row">

		  <div class="col-md-5th col-sm-6 col-xs-12">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Barbara Roccon.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Barbara Roccon</h4>
                <h4><span class="label label-default-blue">Senior LMS Administrator</span></h4>
                 <h4 class="small"><a href="mailto:broccon@taxfaculty.ac.za">broccon@taxfaculty.ac.za</a></h4> 
            </div>
            <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/adel.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>ADÉL MARX</h4>
                <h4><span class="label label-default-blue">Logistics & Enablement Team Leader</span></h4>
                <h4 class="small"><a href="mailto:cpdmanager@taxfaculty.ac.za">cpdmanager@taxfaculty.ac.za</a></h4>
            </div>

			<div class="col-md-5th col-sm-6 col-xs-3">
                <img src="/assets/themes/taxfaculty/img/staff/thabelo.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>THABELO RAIVHOGO</h4>
                <h4><span class="label label-default-blue"> Logistics Administrator</span></h4>
                <h4 class="small"><a href="mailto:cpd@taxfaculty.ac.za">cpd@taxfaculty.ac.za</a></h4>
            </div>
            <div class="col-md-5th col-sm-6 col-xs-3 ">
                <img src="/assets/themes/taxfaculty/img/staff/tshepo.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>TSHEPO MAGOPA</h4>
                <h4><span class="label label-default-blue"> Senior Client Service Representative </span></h4>
                <h4 class="small"><a href="mailto:events@taxfaculty.ac.za">events@taxfaculty.ac.za</a></h4>
            </div>
        
        </div>
    </div>
</section>


<section class="alternate">
    <div class="container text-center staffPage">
	    <div class="row">
			<div class="row">
				<h3 class="text-center">LEARNER MANAGEMENT & DEVELOPMENT</h3>
            </div>
            <div class="col-md-5th col-sm-6 col-xs-3 ">
                <img src="/assets/themes/taxfaculty/img/staff/Foto.png" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Roelánnie van Deventer</h4>
                <h4><span class="label label-default-blue"> Learner Management Team Leader </span></h4>
                <h4 class="small"><a href="mailto:courses@taxfaculty.ac.za">courses@taxfaculty.ac.za</a></h4>
            </div>
		    
			{{--  <div class="col-md-5th col-sm-6 col-xs-12">
                <img src="/assets/themes/taxfaculty/img/staff/smouse.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>KAMOGELO SMOUSE</h4>
                <h4><span class="label label-default-blue">Client Service Representative</span></h4>
                <h4 class="small"><a href="mailto:taxprof@taxfaculty.ac.za">taxprof@taxfaculty.ac.za</a></h4>
            </div>  --}}
           
            <div class="col-md-5th col-sm-6 col-xs-12">
                <img src="/assets/themes/taxfaculty/img/staff/thato.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>THATO TSHWAANA</h4>
                <h4><span class="label label-default-blue">Course Consultant</span></h4>
                <h4 class="small"><a href="mailto:courseadmin1@taxfaculty.ac.za">courseadmin1@taxfaculty.ac.za</a></h4>
            </div>
            
            <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/maria.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Maria Molekwa</h4>
                <h4><span class="label label-default-blue">Course Consultant</span></h4>
                <h4 class="small"><a href="mailto:coursadmin@taxfaculty.ac.za">coursadmin@taxfaculty.ac.za</a></h4>
            </div>

        </div>
    </div>
</section>

<section  >
    <div class="container text-center staffPage">
        <div class="row">
			<div class="row">
				<h3 class="text-center">SALES</h3>
			</div>
			{{--  <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/priscilla.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>PRISCILLA MAMPURU</h4>
                <h4><span class="label label-default-blue">Senior Sales Consultant</span></h4>
                <h4 class="small"><a href="mailto:consultant@taxfaculty.ac.za">consultant@taxfaculty.ac.za</a></h4>
            </div>  --}}
            <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/kathy.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>KATHY SMITH</h4>
                <h4><span class="label label-default-blue">Sales Consultants</span></h4>
                <h4 class="small"><a href="mailto:sales@taxfaculty.ac.za">sales@taxfaculty.ac.za</a></h4>
            </div>

           
			

            {{-- <div class="col-md-5th col-sm-6 col-xs-12">
                <img src="/assets/themes/taxfaculty/img/staff/Buys.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Evan Buys</h4>
                <h4><span class="label label-default-blue">Sales Consultants</span></h4>
                <h4 class="small"><a href="jarrod:sales2@taxfaculty.ac.za">sales2@taxfaculty.ac.za</a></h4>
            </div> --}}
            <!-- <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/Georgina Tsutsu.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Georgina Tsutsu</h4>
                <h4><span class="label label-default-blue">Sales Consultant</span></h4>
                <h4 class="small"><a href="mailto:salescpd@taxfaculty.ac.za">salescpd@taxfaculty.ac.za</a></h4>
            </div> -->
            {{-- <div class="col-md-5th col-sm-6 col-xs-4">
                <img src="/assets/themes/taxfaculty/img/staff/Nkululeko K Bengequla.jpg"  class="thumbnail rounded" alt="Image" style="max-width: 100%; filter: grayscale(100%);">
                <h4>Nkululeko Bengequla</h4>
                <h4><span class="label label-default-blue">Sales Consultant</span></h4>
                <h4 class="small"><a href="mailto:registrations@taxfaculty.ac.za">registrations@taxfaculty.ac.za</a></h4>
            </div> --}}

            <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/Jennifer_Nkalanga.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Jennifer Nkalanga</h4>
                <h4><span class="label label-default-blue">Sales Consultants</span></h4>
                <h4 class="small"><a href="mailto:sales2@taxfaculty.ac.za">sales2@taxfaculty.ac.za</a></h4>
            </div>
            <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/Lucian_Devenish.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Lucian Devenish</h4>
                <h4><span class="label label-default-blue">Sales Consultants</span></h4>
                <h4 class="small"><a href="mailto:cpdsales@taxfaculty.ac.za">cpdsales@taxfaculty.ac.za</a></h4>
            </div>

            <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/IMG_20210528_144704.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Taurik Blignaut </h4>
                <h4><span class="label label-default-blue">Sales Consultants</span></h4>
                <h4 class="small"><a href="mailto:consultant2@taxfaculty.ac.za">consultant2@taxfaculty.ac.za</a></h4>
            </div>
        </div>
    </div>
</section>






<section  class="alternate">
    <div class="container text-center staffPage">
			<div class="row">
				<h3 class="text-center">CONTENT</h3>
			</div>

        <div class="row">
            <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/Simphiwe_Mili.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Simphiwe Mili</h4>
                <h4><span class="label label-default-blue">Associate Lecturer</span></h4>
                 <h4 class="small"><a href="mailto:simphiwe@taxfaculty.ac.za">simphiwe@taxfaculty.ac.za</a></h4> 
            </div>

            <!-- <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Aneesa Peerbhai.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Aneesa Peerbhai</h4>
                <h4><span class="label label-default-blue">Senior Lecturer</span></h4>
                 <h4 class="small"><a href="mailto:aneesa@taxfaculty.ac.za">aneesa@taxfaculty.ac.za</a></h4> 
            </div> -->

            <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Illana Kretzschmar.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Illana Kretzschmar CA(SA)</h4>
                <h4><span class="label label-default-blue">Senior Lecturer</span></h4>
                 <h4 class="small"><a href="mailto:ikretzschmar@taxfaculty.ac.za">ikretzschmar@taxfaculty.ac.za</a></h4> 
            </div>

            <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Anika.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Anika Bester</h4>
                <h4><span class="label label-default-blue">Lecturer</span></h4>
                 <h4 class="small"><a href="mailto:abester@taxfaculty.ac.za">abester@taxfaculty.ac.za</a></h4> 
            </div>

            {{-- <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Marita Jordaan.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Marita Jordaan</h4>
                <h4><span class="label label-default-blue">Lecturer</span></h4>
                 <h4 class="small"><a href="mailto:students@taxfaculty.ac.za">students@taxfaculty.ac.za</a></h4> 
            </div> --}}

            {{-- <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Roline van Jaarsveld.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Roline van Jaarsveld CA(SA)</h4>
                <h4><span class="label label-default-blue">Lecturer </span></h4>
                 <h4 class="small"><a href="mailto:registrations@taxfaculty.ac.za ">registrations@taxfaculty.ac.za </a></h4> 
            </div> --}}
			
        </div>
    </div>
</section>

{{-- <section class="alternate">
    <div class="container text-center staffPage">
        <div class="row"> --}}
            {{-- <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Tumi Motumi.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Tumi Motumi</h4>
                <h4><span class="label label-default-blue">Lecturer</span></h4>
                 <h4 class="small"><a href="mailto:students@taxfaculty.ac.za">students@taxfaculty.ac.za</a></h4> 
            </div> --}}
            {{-- <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Brigitte Webb.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Brigitte Webb CA(SA)</h4>
                <h4><span class="label label-default-blue">Lecturer</span></h4>
                 <h4 class="small"><a href="mailto:students@taxfaculty.ac.za">students@taxfaculty.ac.za</a></h4> 
            </div> --}}
            {{-- <div class="col-md-5th col-sm-6 col-xs-12">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Lutfiyya Hattia.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Lutfiyya Hattia CA(SA)</h4>
                <h4><span class="label label-default-blue">Lecturer</span></h4>
                 <h4 class="small"><a href="mailto:taxprof@taxfaculty.ac.za">taxprof@taxfaculty.ac.za</a></h4> 
            </div> --}}
            {{-- <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Casne Viljoen.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Casne Viljoen CA(SA)</h4>
                <h4><span class="label label-default-blue">Lecturer </span></h4>
                 <h4 class="small"><a href="mailto:registrations@taxfaculty.ac.za ">registrations@taxfaculty.ac.za </a></h4> 
            </div> --}}
            {{--  <div class="col-md-5th col-sm-6 col-xs-12 ">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Annelie Laage.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Annelie Laage</h4>
                <h4><span class="label label-default-blue">Lecturer</span></h4>
                {{--  <h4 class="small"><a href="mailto:annelie@taxfaculty.ac.za">annelie@taxfaculty.ac.za </a></h4>  
            </div>  --}}

           

            
              
           
			{{-- <div class="col-md-5th col-sm-6 col-xs-12">
                <img src="/assets/themes/taxfaculty/img/staff/mentors/Chantal Butner.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                <h4>Chantal Butner</h4>
                <h4><span class="label label-default-blue">Lecturer</span></h4>
                 <h4 class="small"><a href="mailto:taxprof@taxfaculty.ac.za">taxprof@taxfaculty.ac.za</a></h4> 
            </div> --}}

           
         
            {{-- <div class="row">
            
                <div class="col-md-5th col-sm-6 col-xs-12">
                  <img src="/assets/themes/taxfaculty/img/staff/mentors/Samantha King.jpg" class="thumbnail rounded" alt="Image" style="width: 100%; max-width: 100%; filter: grayscale(100%);">
                  <h4>Samantha King</h4>
                  <h4><span class="label label-default-blue">Lecturer</span></h4>
                   <h4 class="small"><a href="mailto:taxprof@taxfaculty.ac.za">taxprof@taxfaculty.ac.za</a></h4> 
              </div>
            
            </div> --}}
    {{-- </div>
</section> --}}


@endsection