@extends('app')

@section('meta_tags')
<title>Subscription Plans and Pricing</title> 
@endsection
@section('content')

@section('title')
   Subscription Plans and Pricing
@stop

@section('breadcrumbs')
  
@stop

@section('styles')
<style>
        .price-clean p {
            min-height: 50px;
            height: auto;
        }
</style>
@endsection 
<?php $profession=New \App\Profession\Profession(); 
$staff = collect();
$company = collect();
if(auth()->check()){ 
    if(auth()->user()->company_admin()){
       $company[] =  auth()->user()->company;
       $staff =  auth()->user()->company->staff;
    }
}
?>

<section style="background-image: url('/assets/frontend/images/demo/wall2.jpg'); padding-bottom: 10px; padding-top: 30px" class="plan_and_pricing">
    <div class="container">
            <!--<h4 class="plans_title">Subscription Plans and Pricing</h4>-->

<app-subscriptions-screen-plan :staff="{{ $staff }}" :companys="{{ $company }}" :plans="{{ $business }}"  :subscriptions="{{(auth()->user()?auth()->user()->subscription('cpd')->plan:auth()->user())}}" :profession="{{ $profession }}"
            :user="{{ (auth()->user() ? auth()->user()->load('cards') : auth()->user()) }}" :bank_codes="{{ json_encode(getBankBranches()) }}" :donations="{{ env('DONATIONS_AMOUNT') }}" inline-template>  

    <ul class="nav nav-tabs">
            <li class="active"><a class="tab_li" data-toggle="tab" @click.prevent="makeactive('individual','chartered-accountant')" href="#individuals" >Individual Subscription Plans</a></li>
             <li><a class="tab_li"@click.prevent="makeactive('business','business-accountant-in-practice')" data-toggle="tab" href="#business" >Practice Subscription Plans</a></li>
            <li><a class="tab_li" @click.prevent="makeactive('trainee','Trainee-Accountant')"  data-toggle="tab" href="#students" >Corporate Tax Department: Learning & Development and Resource Centre Plans</a></li> 
    </ul>
        <!-- <div id="exTab2" class="container">	
            <ul class="nav nav-tabs">
                <li class="active">
                <a  href="#1" data-toggle="tab">Overview</a>
                </li>
                <li><a href="#2" data-toggle="tab">Without clearfix</a>
                </li>
                <li><a href="#3" data-toggle="tab">Solution</a>
                </li>
		</ul>
        </div> -->

        

        @include('subscriptions.2017.include.planhelp')
    
    <div class="tab-content">  
        <div id="individuals" class="tab-pane fade in active">
            <h3>Individual Subscription Plans</h3>
            <p class="p_paragraph">These subscription plans give you access to professional and technical content that ensures both your knowledge and skills are maintained so you remain professionally competent.</p>
            <div v-if="Plantype == 'individual'">
            @include('professions.common')
            </div>
           

            <a href="#business"  @click.prevent="makeactive('business','business-accountant-in-practice')" data-toggle="tab"  class="btn btn-primary fullwidth nomargin bopadding noradius">Buying for a business or practice? See the practice plans.</a>
           
            
        </div> 
 
        <div id="business" class="tab-pane fade">
            <h3>Practice Subscription Plans</h3>
            <p class="p_paragraph">These subscription plans provide you and your employees with access to professional and technical content that ensures both accounting knowledge and skills are maintained so your business remains relevant and professionally competent.</p>
            <div v-if="Plantype == 'business'">
                @include('professions.common')
                </div>

            <a href="#individuals" data-toggle="tab" @click.prevent="makeactive('individual','chartered-accountant')" class="btn  btn-primary fullwidth nomargin bopadding noradius ">Buying for yourself? See the individual plans.</a>

           
        </div>
        
        <div id="students" class="tab-pane fade">
            <h3>Corporate Tax Department: Learning & Development and Resource Centre Plans</h3>
            <p class="p_paragraph">This subscription plan gives you access to professional and technical content that ensures you cover all the knowledge and skills needed as a Trainee Accountant.</p>
            <div v-if="Plantype == 'trainee'">
                @include('professions.common')
                </div>
        </div> 

</div>

</app-subscriptions-screen-plan>

</div>
</section>
@endsection


@section('scripts')
<script>
    $(document).ready(function(){
        var max=50;
        $('.price-clean p').each(function(){
            if($(this).height()>max){
                max=$(this).height();
            }
        })

        $('.price-clean p').each(function(){
            $(this).css('min-height',max+'px'); 
        })

         $('.price-clean h5').each(function(){
            if($(this).height()>max){
                max=$(this).height();
            }
        })

        $('.price-clean h5').each(function(){
            $(this).css('min-height',max+'px'); 
        })
    })
</script>
@stop