@extends('app')

@section('content')
<style>
        thead tr {
            background: #4472c4;
            color: white;
        }
        #legislation_table th:nth-child(2) {
            width: 20% !important;
        }

        #legislation_table th:nth-child(3) {
            width: 20% !important;
        }
</style>
<section class="page-header hidden-print">
    <div class="container">
        <h1> Updated legislation including Amendments</h1>
    </div>
</section>

<section class="theme-color hidden-print" style="padding: 0px;">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb" style="padding: 0px">
                        <li><a href="{{ route('resource_centre.home') }}">Resource Centre</a></li>
                        <li><a href="{{ route('resource_centre.legislation.index') }}">Legislation List</a></li>
                    </ol>
                </li>
            </ol>
        </div>
    </div>
</section>


@if((auth()->user()->subscribed('cpd') && auth()->user()->subscription('cpd')->plan->price != '0') || (auth()->user()->hasCompany() && auth()->user()->hasCompany()->company && auth()->user()->hasCompany()->company->admin()->subscription('cpd') && auth()->user()->hasCompany()->company->admin()->subscription('cpd')->plan->price != '0'))

    <section>
        <div class="container">
        <app-legislation-index-screen :actlist="{{ $acts->toJson() }}"  inline-template>
            <div class="row mix-grid">
                {{--  <div class="col-md-3">
                    <div class="border-box" style="background-color: #f5f5f5">
                        <br>
                        {!! Form::open(['method' => 'post', 'route' => 'resource_centre.legislation.search']) !!}
                        <div class="form-group @if ($errors->has('search')) has-error @endif">
                            {!! Form::input('text', 'search', null, ['class' => 'form-control', 'placeholder' => 'Search for content', 'style' => 'text-align:center']) !!}
                            @if ($errors->has('search')) <p class="help-block">{{ $errors->first('search') }}</p> @endif
                        </div>
                        <button onclick="spin(this)" style="color: #800000" class="btn-block btn btn-default"><i class="fa fa-search"></i> Search</button>
                        {!! Form::close() !!}
                    </div>
                </div>  --}}
                <div class="col-md-12">
                    <row>
                            <div class="border-box" style="background-color: #f5f5f5">
                                    <br>
                                    <div class="form-group">
                                            <input type="text" v-on:keyup="filterClient" v-model="search" placeholder="Search for content" value="" class="form-control">
                                        </div>
                                </div>
                           
                    </row>

                        <table v-show="filteredList.length > 0" class="table table-striped table-bordered" id="legislation_table">
                                <thead>
                                <tr>
                                    <th>Act Name</th>
                                    <th>Act Year</th>
                                    <th>Category</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="act in filteredList" style="display: table-row;">
                                        <td><a href="{{ url('resource_centre/acts/show') }}/@{{act.slug}}">@{{ act.name }}</a></td>
                                        <td>@{{ act.act_year }}</td>
                                        <td>@{{ act.category }}</td>
                                    </tr>
                                
                                </tbody>
                            </table>

                            <div  v-show="filteredList.length == 0" class="alert alert-info">There are no acts available.</div>
                </div>
            </div>
        </app-legislation-index-screen>
        </div>
    </section>
@else
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="border-box text-center" style="background-color: #fbfbfb; min-height:300px">
                        <i class="fa fa-lock" style="font-size: 12vh"></i>
                        <p>You do not have access to this page. <br> The Technical Resource Centre is part of the Designation CPD Subscription Packages.</p>
                        <a class="btn btn-primary" href="{{ route('resource_centre.home') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        <a class="btn btn-default" href="/cpd"><i class="fa fa-arrow-right"></i> View Packages</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif


@endsection