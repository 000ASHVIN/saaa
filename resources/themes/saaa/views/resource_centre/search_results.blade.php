@extends('app')

@section('content')

@section('title')
    Search Results
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('resource_centre') !!}
@stop

<section id="slider" class="hidden-sm hidden-xs">
    <img src="https://imageshack.com/a/img923/3048/80sCiK.jpg" alt="Technical Resource Centre" style="width: 100%">
</section>

@include('resource_centre.includes.search')

<section class="alternate resouce-center-search">
    <div class="container">
        <resource-search resourcerecordes="{{ json_encode($allRecords->toArray()) }}" categories="{{ $categories_json }}" :webinars="{{ $webinars }}" :faqs="{{ $faqs }}" :articles="{{ $articles }}" :events="{{ $events }}" :acts="{{ $acts }}" :courses="{{ $courses }}" :tickets="{{ $tickets }}"  inline-template>
            <div class="row">
                <div class="col-md-3" id="accordion">
                    <div id="card-box" class="card">
                        <div class="card-header head-text">
                            <b>Filter By Content Type:</b>
                        </div>
                        <div class="card-body">
                            <li class="custome_filter bullet" v-if="webinars.length > 0"><a class="list-text" @click="filterContent('webinars')" >Webinar (@{{webinars.length}})</a></li>

                            <li  class="custom_filter bullet" v-if="faqs.length > 0"><a class="list-text" @click="filterContent('faqs')" > FAQ's (@{{faqs.length}})</a></li>
                            
                            <li  class="custom_filter bullet" v-if="articles.length > 0"><a class="list-text" @click="filterContent('articles')" > Articles (@{{articles.length}})</a></li>
                            
                            <li class="custom_filter bullet" v-if="events.length > 0"><a class="list-text" @click="filterContent('events')" > Upcoming Webinar (@{{events.length}})</a></li>

                            <li  class="custom_filter bullet" v-if="acts.length > 0"><a class="list-text" @click="filterContent('acts')" > Legislation (@{{acts.length}})</a></li>

                            <li class="custom_filter bullet" v-if="courses.length > 0"><a class="list-text" @click="filterContent('courses')" >Courses (@{{courses.length}})</a></li>
                            
                            <li class="custom_filter bullet" v-if="tickets.length > 0"><a class="list-text" @click="filterContent('tickets')" >Tickets (@{{tickets.length}})</a></li>
                        </div>
                    </div>
                    <div id="card-box" class="card category" v-if="filterCategoryArray.length > 0">
                        <div class="card-header head-text">
                            <b>Filter By Category:</b>
                        </div>
                        <div class="card-body">
                            <div v-for="category in filterCategoryArray">
                                <div v-if="category.category && category.category.length>0 ">
                                    <div class="title parent @{{category.type}}">
                                        <span  class="category-dropdown-toggle" data-toggle="collapse" role="button" data-parent="#accordion" href="#@{{ category.type }}" aria-expanded="false" aria-controls="@{{category.type}}">
                                            <p class="category-name" v-if="category.type == 'articles' && ( filterCategoryName == '' || filterCategoryName == 'articles' )">Articles (@{{totalcountforarticles}})</p>
                                            <p class="category-name" v-if="category.type == 'faqs' && ( filterCategoryName == '' || filterCategoryName == 'faqs' )">FAQ's (@{{totalcountforfaqs}})</p>
                                            <p class="category-name" v-if="category.type == 'webinars' && ( filterCategoryName == '' || filterCategoryName == 'webinars' )">Webinars On-Demand (@{{totalcountforwebinars}})</p>
                                            <p class="category-name" v-if="category.type == 'events' && ( filterCategoryName == '' || filterCategoryName == 'events' )">Upcoming Webinar (@{{totalcountforevents}})</p>
                                            <p class="category-name" v-if="category.type == 'courses' && ( filterCategoryName == '' || filterCategoryName == 'courses' )">Courses (@{{totalcountforcourse}})</p>
                                        </span>
                                        <span id="category-icon- @{{category.type}}" role="button" class="glyphicon glyphicon-chevron-down dropdown-icon category-dropdown-toggle" data-toggle="collapse" data-parent="#accordion" href="#@{{category.type}}" aria-expanded="false" aria-controls="@{{category.type}}"></span>
                                    </div>
                                    <div id="@{{category.type}}" class="collapse" aria-labelledby=" @{{category.type}}">
                                        <div v-for="cat in category.category">
                                            <li class="category_filter filter_category @{{category.type}} bullet">
                                                <a class="list-text" @click="filterByCategory(cat.slug,category.type)">@{{cat.category_title}}(@{{cat.count }})</a>
                                            </li>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="card-box" class="card sub_category" v-if="filterSubCategoryArray.length >0">
                        <div class="card-header head-text">
                            <b>Filter By Subcategory:</b>
                        </div>
                        <div class="card-body">
                            <div v-for="category in filterSubCategoryArray">
                                <div v-if="category.sub_category && category.sub_category.length>0">
                                    <div class="title child ">
                                        <span class="underline subcategory-dropdown-toggle" role="button" data-toggle="collapse"  data-parent="#accordion" href="#subcategory-@{{category.type}}" aria-expanded="false" aria-controls="subcategory-@{{category.type}}">
                                            <p class="category-name" v-if="category.type == 'articles' && ( filterCategoryName == '' || filterCategoryName == 'articles' )">Articles </p>
                                            <p class="category-name" v-if="category.type == 'faqs' && ( filterCategoryName == '' || filterCategoryName == 'faqs' )">FAQ's </p>
                                            <p class="category-name" v-if="category.type == 'webinars' && ( filterCategoryName == '' || filterCategoryName == 'webinars' )">Webinars On-Demand </p>
                                            <p class="category-name" v-if="category.type == 'events' && ( filterCategoryName == '' || filterCategoryName == 'events' )">Upcoming Webinar </p>
                                            <p class="category-name" v-if="category.type == 'courses' && ( filterCategoryName == '' || filterCategoryName == 'courses' )">Courses </p>
                                        </span>
                                        <span  id="subcategory-icon-subcategory-@{{category.type}}" role="button" class="glyphicon glyphicon-chevron-down dropdown-icon subcategory-dropdown-toggle" data-toggle="collapse" data-parent="#accordion" href="#subcategory-@{{category.type}}" aria-expanded="false" aria-controls="subcategory-@{{category.type}}"></span>
                                    </div>
                                    <div id="subcategory-@{{category.type}}" class="collapse" aria-labelledby="subcategory-@{{category.type}}">
                                        <div v-for="cat in category.sub_category">
                                            <div v-if="cat.count > 0">
                                                <li class="subcategory_filter filter_subcategory bullet parent-category-@{{cat.parent_category_id}}-@{{category.type}}" >
                                                        <a class="list-text" @click="filterBySubCategory(cat.slug,category.type)">@{{cat.category_title}}(@{{cat.count}})</a>
                                                </li>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">

                    <div class="row mix-grid">
                        {{-- <ul class="nav nav-pills mix-filter" style="text-transform: capitalize;">
                            @if (count($events))<li data-filter="events" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Upcoming Events ({{ count($events) }})</a></li>@endif

                            @if (count($webinars))<li data-filter="webinars" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Webinars On-demand ({{ count($webinars) }})</a></li>@endif

                            @if (count($courses))<li data-filter="courses" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Courses ({{ count($courses) }})</a></li>@endif

                            @if (count($articles))<li data-filter="articles" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Articles ({{ count($articles) }})</a></li>@endif

                            @if (count($faqs))<li data-filter="faq" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> FAQ's ({{ count($faqs) }})</a></li>@endif
                            
                            @if (count($acts))<li data-filter="acts" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Legislation ({{ count($acts) }})</a></li>@endif                            
                            
                            @if (count($tickets))<li data-filter="tickets" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Tickets ({{ count($tickets) }})</a></li>@endif
                            
                            
                        </ul> --}}
                        <div class="header_title">
                            <h3>Technical Resource Center Search</h3>
                        </div>

                        <div class="search_term gray_back">
                            <h4>YOU SEARCHED:</h4>
                            <span>{{ $title }}</span>
                        </div>

                        <div class="sord_and_update gray_back">
                            <label for="" class="col-form-label">Sort by: </label>
                            <select name="sort_by" id="sotr_by" class="form-control" @change="sortByRelevanceAndDate($event)">
                                <option value="relevance">Relevance</option>
                                <option value="last_updated">Last updated</option>
                            </select>
                            {{-- <h4><span id="sort_by_relevance" class="btn btn-default btn-sort active">Sort by: Relevance</span> | <span class="btn btn-default btn-sort" id="sort_date_new_to_old">Last updated</span></h4> --}}
                        </div>
                        
                        <div class="toggle toggle-transparent toggle-bordered-simple" id="records" v-if="filterArray.length > 0">

                            <div v-for="item in filterArray">                                
                                <div class=" articles mix filter_result" v-if="item.search_type === 'articles'">
                                    <div class="clearfix search-result" style="margin-bottom: 20px;" >
                                        <h4 class="mb-0"><a target="_blank" href="/news/read/@{{item.slug}}">@{{item.title}}</a></h4>
                                        <button class="search_type">'Articles'</button><small class="text-success"> | @{{item.created}} </small>
                                        
                                        <p> @{{ item.short_description}} | <a href="/news/read/@{{item.slug}}" target="_blank">Read More</a> </p>
                                    </div>
                                </div>

                                <div class=" faq mix filter_result" v-if="item.search_type === 'faqs'"> 
                                    <div class="clearfix search-result" style="margin-bottom: 20px;" >
                                        <h4 class="mb-0"><a target="_blank" href="/resource_centre/technical_faqs">@{{item.question}}</a></h4>
                                        <button class="search_type">'FAQs'</button><small class="text-success"> | @{{item.created}}</small>
                                        
                                        <p> @{{ item.short_description}} | <a href="/resource_centre/technical_faqs" target="_blank">Read More</a> </p>
                                    </div>
                                </div>                                
                                
                                <div class=" acts mix filter_result" v-if="item.search_type === 'acts'"> 
                                    <div class="clearfix search-result" style="margin-bottom: 20px;" >
                                        <h4 class="mb-0"><a target="_blank" href="/resource_centre/acts/show/@{{item.slug}}">@{{item.name}}</a></h4>
                                        <button class="search_type">'Acts'</button><small class="text-success"> | @{{item.created}}</small>
                                        
                                        <p> @{{ item.short_description }} | <a href="/resource_centre/acts/show/@{{item.slug}}" target="_blank">Read More</a> </p>
                                    </div>
                                </div>                        
                                
                                <div class=" webinars  mix filter_result" v-if="item.search_type === 'webinars'"> 
                                    <div class="clearfix search-result" style="margin-bottom: 20px;" >
                                        <h4 class="mb-0"><a target="_blank" href="/webinars_on_demand/show/@{{item.slug}}">@{{item.title}}</a></h4>
                                        <button class="search_type">'Webinar On Demand'</button><small class="text-success"> | @{{item.created}}</small>
                                        <p> @{{ item.short_description }} | <a href="/webinars_on_demand/show/@{{item.slug}}" target="_blank">Read More</a> </p>
                                    </div>
                                </div>

                                <div class=" events mix filter_result" v-if="item.search_type === 'events'"> 
                                    <div class="clearfix search-result" style="margin-bottom: 20px;" >
                                        
                                        <h4 class="mb-0" v-if="item.is_redirect == 0"><a target="_blank" href="/events/@{{item.slug}}" >@{{item.name}}</a></h4>
                                        <button class="search_type">'Upcoming Event''</button><small class="text-success"> | @{{item.created}}</small>
                                        
                                        <p v-if="item.is_redirect == 0"> @{{ item.short_description }} | <a href="/events/@{{item.slug}}"  target="_blank">Read More</a> </p>
                                    </div>
                                </div>

                                <div class=" tickets mix filter_result" v-if="item.search_type === 'tickets'">
                                    <div class="clearfix search-result" style="margin-bottom: 20px;" >
                                        <h4 class="mb-0"><a target="_blank" href="/resource_centre/legislation/show/@{{item.slug}}">@{{item.subject}}</a></h4>
                                        <button class="search_type">'Tickets'</button><small class="text-success"> | @{{item.created}}</small>
                                        <p> @{{ item.short_description }} | <a href="/resource_centre/legislation/show/@{{item.slug}}" target="_blank">Read More</a> </p>
                                    </div>
                                </div>

                                <div class=" courses mix filter_result" v-if="item.search_type === 'courses'">
                                    <div class="clearfix search-result" style="margin-bottom: 20px;" >
                                        <h4 class="mb-0" ><a target="_blank" href="/courses/show/@{{item.slug}}" >@{{item.title}}</a></h4>
                                        <button class="search_type">'Courses'</button><small class="text-success"> | @{{item.created}}</small>
                                        <p> @{{ item.short_description }} | <a href="/courses/show/@{{item.slug}}" target="_blank">Read More</a> </p>
                                    </div>
                                </div> 
                                 
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <a href="{{ route('resource_centre.home') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back Home</a>
                    </div>
                </div>
            </div>
        </resource-search>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function spin(this1) {
        this1.closest("form").submit();
        this1.disabled = true;
        this1.innerHTML = `<i class="fa fa-spinner fa-spin"></i> Working..`;
    }

    
</script>
@stop