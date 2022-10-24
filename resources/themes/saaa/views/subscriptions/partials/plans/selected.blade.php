<div class="panel panel-default">
    <div class="panel-heading">Your Plan</div>
    <div class="panel-body">

        <!-- Current Plan -->
        <div class="pull-left" style="line-height: 36px;">
            You have selected the <strong>@{{ selectedPlan.name }}</strong>
            (@{{ selectedPlanPrice }} /<span v-if="selectedPlan.is_practice"> user /</span> @{{ selectedPlan.interval | capitalize}}) plan.
        </div>


        <!-- Select Another Plan -->
        <div class="pull-right" style="line-height: 32px;">
            @if (isset($_REQUEST['renew']))
                <a class="btn btn-primary" href="{{ route('profession.plans_and_pricing') }}">
                    <i class="fa fa-btn fa-arrow-left"></i>Change Plan
                </a>
            @else
                <button class="btn btn-primary" @click.prevent="selectAnotherPlan">
                    <i class="fa fa-btn fa-arrow-left"></i>Change Plan
                </button>
            @endif
        </div>

        <div class="clearfix"></div>
    </div>
</div>

<div v-if="selectedPlan.is_practice">
<div class="panel panel-default" v-if=" companys.length == 0">
        <div class="panel-heading">Company</div>
        <div class="panel-body">
            @if(auth()->user())
            @include('subscriptions.partials.plans.company')
            @endif
        </div>
    </div>
     
    
<div class="panel panel-default" v-if="!is_practice_plan && companys.length > 0">
    <div class="panel-heading">Company Staff  <a href="#" v-on:click.prevent="toggleStaff()" class="btn btn-primary">Add Staff</a></div>
    <div class="panel-body">

        <div class="col-md-12 " v-for="staffs in staff">
            <div> <label class="checkbox pt-20" for="check@{{{ staffs.id }}}@{{{selectedPlan.id}}}">
                <input type="checkbox" value="@{{{ staffs.id }}}" id="check@{{{ staffs.id }}}@{{{selectedPlan.id}}}"  @change.prevent="setSelectedStaff($event)">
                <i></i> @{{{ staffs.first_name }}} @{{{ staffs.last_name }}}</div>
                </label>
            
        </div>
        <div v-if="staff.length == 0">
            No staff Member found. Please add New staff Members
        </div>
        <div class="clearfix"></div>
        
        <div class="col-lg-12 col-md-12 col-sm-12" v-if="inviteStaff" >
                <hr>
                @include('dashboard.company.forms.invite_form', ['button' => 'Send Invite'])
                </div>
    </div> 
</div>


<div class="panel panel-default" v-if="forms.subscription.pricing_group.length>0">
    <div class="panel-heading">Pricing Groups</div> 
    <div class="panel-body">

        <div class="col-md-12 ">
            <table class="table table-responsive table-bordered">
                <thead>
                    <tr>
                        <th>Practice Size</th>
                        <th>Maximun Users</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr  v-for="pricing in forms.subscription.pricing_group">
                        <td> @{{{ pricing.name }}}</td>
                        <td>@{{{ pricing.max_user }}}</td>
                        <td>@{{{ pricing.price }}}</td>
                    </tr>
                </tbody>
            </table>
          
            
        </div>
        <div class="clearfix"></div>
    </div>
</div>
</div>

