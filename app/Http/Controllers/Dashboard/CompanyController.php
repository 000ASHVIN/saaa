<?php

namespace App\Http\Controllers\Dashboard;

use DB;
use App\Invite;
use App\Company;
use App\Users\User;
use App\CompanyUser;
use Illuminate\Http\Request;
use App\Users\UserRepository;
use App\Profession\Profession;
use App\Subscriptions\Models\Plan;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Subscriptions\Models\Subscription;
use App\Http\Requests\CompanyInviteRequest;
use App\Subscriptions\Models\SubscriptionGroup;
use App\Http\Requests\CompanyInformationRequest;
use App\Repositories\InviteRepository\InviteRepository;

use App\Note;
use Carbon\Carbon;
use App\Subscriptions\Models\Period;
use App\Repositories\Subscription\upgradeSubscriptionRepository;
use App\Users\Cpd;
use App\Assessment;
use App\Video;
use App\AppEvents\Ticket;
use Illuminate\Support\Facades\File;
use ZipArchive;

class CompanyController extends Controller
{
    private $inviteRepository;
    private $userRepository;
    private $upgradeSubscriptionRepository;

    public function __construct(InviteRepository $inviteRepository, UserRepository $userRepository,upgradeSubscriptionRepository $upgradeSubscriptionRepository)
    {
        $this->inviteRepository = $inviteRepository;
        $this->userRepository = $userRepository;
        $this->upgradeSubscriptionRepository = $upgradeSubscriptionRepository;
    }

    public function index()
    {
        $user = auth()->user();
        $total_practice_plan = $user->isPracticePlan();
        $subscription_group = SubscriptionGroup::where('admin_id', $user->id)->get()->pluck('user_id')->toArray();
        $cpds = collect();
        $company_staffs = [];
        if($user->company && $user->company->staff) {
            $company_staffs = $user->company->staff->pluck('name', 'id')->toArray();
            $staff_ids = $user->company->staff->pluck('id')->toArray();
            if(isset(request()->employee) && request()->employee!=""){
                $staff_ids = [request()->employee];
            }
            $cpds = Cpd::with('user', 'certificate')->has('certificate')->whereIn('user_id',$staff_ids);
            $cpds = $cpds->orderBy('date','desc')->paginate(10);
            $cpds = $this->assignCategoryToCPD($cpds);
            $cpds->appends(request()->all());
        }
        return view('dashboard.company.index',compact('plans','subscription_group','total_practice_plan', 'cpds', 'user', 'company_staffs'));
    } 

    public function assignCategoryToCPD($cpds) {
        foreach($cpds as $cpd) {

            if($cpd->certificate) {
                $view_path = $cpd->certificate->view_path;
                if($view_path == 'certificates.attendance') {
                    if($cpd->certificate->source) {
                        $ticket = $cpd->certificate->source;
                        if($ticket) {
                            $event = $ticket->event;
                            if($event) {
                                $category = $event->categories()->first();
                                if($category) {
                                    $cpd->category = $category->title;
                                }
                                if($event->reference_id) {
                                    if(env('APP_THEME') == 'taxfaculty'){
                                        $cpd->service_provider = "SA Accounting Academy";
                                    } else {
                                        $cpd->service_provider = "Tax Faculty";
                                    } 
                                }                         
                            }
                        }
                    }
                    
                }
        
                if($view_path == 'certificates.assessment') {
                    if($cpd->certificate->source) {
                        $assessment = $cpd->certificate->source;
                        
                        if($assessment) {
                            $event = $assessment->events()->first();
                            if($event) {
                                $category = $event->categories()->first();
                                if($category) {
                                    $cpd->category = $category->title;
                                }
                                if($event->reference_id) {
                                    if(env('APP_THEME') == 'taxfaculty'){
                                        $cpd->service_provider = "SA Accounting Academy";
                                    } else {
                                        $cpd->service_provider = "Tax Faculty";
                                    } 
                                }                         
                            }
                        }
                    }
                }

                if($view_path == 'certificates.wob') {
                    if($cpd->certificate->source) {
                        $video = $cpd->certificate->source;
                        
                        if($video) {
                            if(count($video->recordings)) {
                                foreach($video->recordings as $recording) {
                                    if($recording->pricing && $recording->pricing->event) {
                                        $event = $recording->pricing->event;
                                        $category = $event->categories()->first();
                                        if($category) {
                                            $cpd->category = $category->title;
                                        }

                                        if($event->reference_id) {
                                            if(env('APP_THEME') == 'taxfaculty'){
                                                $cpd->service_provider = "SA Accounting Academy";
                                            } else {
                                                $cpd->service_provider = "Tax Faculty";
                                            } 
                                        } 
                                    }
                                }                                                        
                            }
                        }
                    }
                }
            }
            
        }
        return $cpds;
    }

    public function create()
    {
        return view('dashboard.company.create');
    }

    public function store(CompanyInformationRequest $request)
    {
        DB::transaction(function () use($request){
            Company::create($request->except('_token'));
        });

        alert()->success('Company Setup Successful', 'Thank you!');
        return redirect()->route('dashboard.company.index');
    }

    public function saveCompany(CompanyInformationRequest $request)
    {
        $company = DB::transaction(function () use ($request) {
            return Company::create($request->except('_token'));
        });

        return $company;
    }

    public function invite()
    {
        $invites = auth()->user()->company->invites()->take(10)->paginate(10);
        return view('dashboard.company.invite', compact('invites'));
    }

    public function pending()
    {
        $invites = auth()->user()->company->invites()->paginate(10);
        return view('dashboard.company.pending', compact('invites'));
    }

    public function bulk_invite(Request $request)
    {
        if(auth()->user()->isPracticePlan() <= auth()->user()->totalStaff()){
            alert()->error("You can't invite more users", 'Error!')->persistent('Close');
            return redirect()->back();
        }
        if($request->hasFile('file')) {
            $file = Excel::load($request->file('file'), function($reader) {
            })->get();

            if(count($file) > 0) {
                $failed = 0;
                $success = 0;

                foreach ($file as $staffMember) {
                    do {
                        $token = str_random();
                    }
                    while (Invite::where('token', $token)->first());

                    $data = $staffMember->toArray();

                    if(empty($data['email_address']) || empty($data['id_number'])) {
                        $failed++;
                        continue;
                    }

                    if (str_replace(' ', '', $data['email_address']) != auth()->user()->email){
                        if (auth()->user()->company->staff->contains('email', str_replace(' ', '', $data['email_address'])) != true){
                            $invite = $this->inviteRepository->invite([
                                'cell' => $data['cellphone'],
                                'email' => $data['email_address'],
                                'id_number' => $data['id_number'],
                                'first_name' => $data['first_name'],
                                'last_name' => $data['last_name'],
                                'alternative_cell' => $data['alternative_cell']
                            ], $token);

                            if(isset($invite) && $invite != 'exists') {
                                $success++;
                                $this->inviteRepository->sendInvite($data['email_address'], $invite);
                            }
                        }else{
                            $failed++;
                            continue;
                        }
                    }else{
                        $failed++;
                        continue;
                    }
                }

                if($success == 0) {
                    alert()->error("Something went wrong, we were not able to import any of the staff members that you have listed, please insure that you complete every column within the excel file and try again.", "Whoops!")->persistent('Close');
                    return redirect()->back();
                } else {
                    alert()->success("Your Invitations has been processed, {$success} staff members was invited successfully and {$failed} staff members could not be invited.", 'Success!')->persistent('Close');
                    return redirect()->route('dashboard.company.pending');
                }
            } else {
                alert()->error('No Staff members found in uploaded file, please try again', 'Error!')->persistent('Close');
                return redirect()->back();
            }
        }
    }

    public function process_invite(CompanyInviteRequest $request)
    {
        
        if(auth()->user()->isPracticePlan() > auth()->user()->totalStaff()){
        DB::transaction(function () use($request){
            do {
                $token = str_random();
            }
            while (Invite::where('token', $token)->first());

            if (str_replace(' ','',$request['email']) != auth()->user()->email){
                $invite = $this->inviteRepository->invite($request, $token);

                if (is_null($invite)){
                    alert()->warning('Invitation already exists, Please resend the invite or delete previous invitation sent.', 'Warning!')->persistent('Close');
                }elseif($invite == 'exists'){
                    alert()->warning('The user you are trying to add is already part of your company, please try adding another', 'Error!')->persistent('Close');
                }else{
                    $this->inviteRepository->sendInvite($request['email'], $invite);
                    alert()->success('Your Invitation has been sent to '.$invite->first_name.' '.$invite->last_name, 'Success!')->persistent('Close');
                }
            }else{
                alert()->error('Unable to invite yourself', 'Error!')->persistent('Close');
            }
        });
        }else{
            alert()->error("You can't invite more users", 'Error!')->persistent('Close');
        }
        return redirect()->route('dashboard.company.pending');
    }

    public function store_invite(CompanyInviteRequest $request)
    {
        $subscription = Subscription::where('user_id', auth()->user()->id)->first();
        $max_staff = $subscription->plan->max_staff;
        $invite = Invite::where('company_id', auth()->user()->company->id)->get();
        $invite_count = $invite->count();
        // if ($invite_count < $max_staff) {
        // dd('y');
        $user = auth()->user();
        if(!$request->check_users) {
            if($user->isPracticePlan() <= $user->totalStaff()){
                return ['message' => "Can't add more user", 'user' => []];
            }
        }
        
        $message = DB::transaction(function () use ($request) {
            do {
                $token = str_random();
            } while (Invite::where('token', $token)->first());

            if (str_replace(' ', '', $request['email']) != auth()->user()->email) {
                $invite = $this->inviteRepository->invite($request, $token);

                if (is_null($invite)) {
                    $message = 'Invitation already exists, Please resend the invite or delete previous invitation sent.';
                } elseif ($invite == 'exists') {
                    $message = 'The user you are trying to add is already part of your company, please try adding another';
                } else {
                    // $this->inviteRepository->sendInvite($request['email'], $invite);

                    $message = 'Your Invitation has been sent to '.$invite->first_name.' '.$invite->last_name;
                }
                $invite = $this->check_Invite($token);

                $user = User::where('email', $invite['email']);
                if($invite['id_number'] !=""){
                    $user = $user->orWhere('id_number', $invite['id_number']);
                }
                $user = $user->first();

                if (is_null($user)) {
                    $user = User::create([
                        'first_name' => $invite['first_name'],
                        'last_name' => $invite['last_name'],
                        'email' => $invite['email'],
                        'id_number' => ($invite['id_number'] ? $invite['id_number'] : '0'),
                        'cell' => ($invite['cell'] ? $invite['cell'] : '0'),
                        'alternative_cell' => ($invite['alternative_cell'] ? $invite['alternative_cell'] : '0'),
                    ]);

                    $tempPassword = $this->generateTemporaryPassword(6);
                    $user->password = $tempPassword;
                    $user->temp_password = $tempPassword;
                    $user->password_is_temporary = true;
                    $user->save();

                    // Subscribe use to the free CPD package.
                    $plan = Plan::where('price', '0')->where('interval', 'year')->get()->first();
                    $user->newSubscription('cpd', $plan)->create();

                    $this->userRepository->createProfileFor($user);
                    $this->userRepository->createWalletForUser($user);
                    $this->AssignCompanyComplete($invite, $user);

                //$this->inviteRepository->sendNewProfileEmail($user['email'], $user);
                } else {
                    $this->AssignCompanyComplete($invite, $user);
                }
            } else {
                $message = 'Unable to invite yourself';
            }

            return ['message' => $message, 'user' => $user];
        });

        // } else {
        //     return back()->withErrors('You are trying to add greater member than max staff member');
        //     // dd('n');
        // }

        return response()->json(['message' => $message['message'], 'status' => '0', 'staff' => $message['user']]);
    }
    public function store_invite_company(CompanyInviteRequest $request)
    { 
        $user =User::find($request->user);
        $subscription = Subscription::where('user_id', $user->id)->first();
        $max_staff = $subscription->plan->max_staff;
        $invite = Invite::where('company_id', $user->company->id)->get();
        $invite_count = $invite->count();
        // if ($invite_count < $max_staff) {
        // dd('y');
        if($user->isPracticePlan() <= $user->totalStaff()){
            return ['message' => "Can't add more user", 'user' => []];
        }
            
        $message = DB::transaction(function () use ($request) {
            do {
                $token = str_random();
            } while (Invite::where('token', $token)->first());

            if (str_replace(' ', '', $request['email']) != $user->email) {
                $invite = $this->inviteRepository->invite($request, $token);

                if (is_null($invite)) {
                    $message = 'Invitation already exists, Please resend the invite or delete previous invitation sent.';
                } elseif ($invite == 'exists') {
                    $message = 'The user you are trying to add is already part of your company, please try adding another';
                } else {
                    // $this->inviteRepository->sendInvite($request['email'], $invite);

                    $message = 'Your Invitation has been sent to '.$invite->first_name.' '.$invite->last_name;
                }
                $invite = $this->check_Invite($token);

                $user = User::where('email', $invite['email']);
                if($invite['id_number'] !=""){
                    $user = $user->orWhere('id_number', $invite['id_number']);
                }
                $user = $user->first();

                if (is_null($user)) {
                    $user = User::create([
                        'first_name' => $invite['first_name'],
                        'last_name' => $invite['last_name'],
                        'email' => $invite['email'],
                        'id_number' => ($invite['id_number'] ? $invite['id_number'] : '0'),
                        'cell' => ($invite['cell'] ? $invite['cell'] : '0'),
                        'alternative_cell' => ($invite['alternative_cell'] ? $invite['alternative_cell'] : '0'),
                    ]);

                    $tempPassword = $this->generateTemporaryPassword(6);
                    $user->password = $tempPassword;
                    $user->temp_password = $tempPassword;
                    $user->password_is_temporary = true;
                    $user->save();

                    // Subscribe use to the free CPD package.
                    $plan = Plan::where('price', '0')->where('interval', 'year')->get()->first();
                    $user->newSubscription('cpd', $plan)->create();

                    $this->userRepository->createProfileFor($user);
                    $this->userRepository->createWalletForUser($user);
                    $this->AssignCompanyComplete($invite, $user);

                //$this->inviteRepository->sendNewProfileEmail($user['email'], $user);
                } else {
                    $this->AssignCompanyComplete($invite, $user);
                }
            } else {
                $message = 'Unable to invite yourself';
            }

            return ['message' => $message, 'user' => $user];
        });

        // } else {
        //     return back()->withErrors('You are trying to add greater member than max staff member');
        //     // dd('n');
        // }

        return response()->json(['message' => $message['message'], 'status' => '0', 'staff' => $message['user']]);
    }

    public function accept_invite($company, $token)
    {
        $invite = $this->check_Invite($token);
        $user = User::where('email', $invite['email']);
        if($invite['id_number'] !=""){
            $user = $user->orWhere('id_number', $invite['id_number']);
        }
        $user = $user->first();


        if (is_null($user)){
            $user = User::create([
                'first_name' => $invite['first_name'],
                'last_name' => $invite['last_name'],
                'email' => $invite['email'],
                'id_number' => ($invite['id_number'] ? $invite['id_number'] : "0"),
                'cell' => ($invite['cell'] ? $invite['cell'] : "0"),
                'alternative_cell' => ($invite['alternative_cell'] ? $invite['alternative_cell'] : "0"),
            ]);

            $tempPassword = $this->generateTemporaryPassword(6);
            $user->password = $tempPassword;
            $user->temp_password = $tempPassword;
            $user->password_is_temporary = true;
            $user->save();
            auth()->login($user);
            // // Subscribe use to the free CPD package.
            // $plan = Plan::where('price', '0')->where('interval', 'year')->get()->first();
            // $user->newSubscription('cpd', $plan)->create();
            $this->allocatePlan($company,$user);

            $this->userRepository->createProfileFor($user);
            $this->userRepository->createWalletForUser($user);
            $this->AssignCompanyComplete($invite, $user);

            $this->inviteRepository->sendNewProfileEmail($user['email'], $user);

        }else{
            auth()->login($user);
            $this->allocatePlan($company,$user);
            $this->AssignCompanyComplete($invite, $user);
        }

       
        alert()->info('Thank you for joining the company', 'Success');

        return redirect()->route('home');
    }
    public function allocatePlan($company,$user)
    {
			$company = Company::find($company);
            $admin = $company->admin();
            
            $subscription_group = SubscriptionGroup::where('admin_id',$admin->id)->groupBy('user_id')->get()->count();
            $total = $admin->isPracticePlan();
            if ($company->admin()->subscription('cpd') && $company->admin()->PracticePlan() && $subscription_group <= $total && $total>0) {
                $plan = $company->admin()->subscription('cpd')->plan;
                if ($user->subscription('cpd')) {
                    if ($user->subscription('cpd')->plan->price > 0) {
                        $oldPlan = $user->subscription('cpd')->plan;
                        $newPlan = $plan;
                        $data = ['email'=>$user->email,
                    'reason'=>'Upgrade due to Practice Plan invitation',
                    'features'=>[],
                    'payment_method'=>'','is_practice_plan'=>1];
    
                        $request = new \Illuminate\Http\Request($data);
                        $this->upgradeSubscriptionRepository->submitRequest($request, $oldPlan, $newPlan);
                    // $user->newSubscription('cpd', $plan)->create();
                    } elseif ($user->subscription('cpd')->plan->price == 0) {
                        $user->subscriptions->where('name', 'cpd')->each(function ($subscription) {
                            $subscription->delete();
                        });
            
                         $subscriptions =$user->newSubscription('cpd', $plan)->create();
                         $subscriptions->ends_at = $company->admin()->subscription('cpd')->ends_at;
                         $subscriptions->save();
                         $subscriptions->setPlanInGroup(true);
                        $this->assignCompany($user,$company,$plan,$subscriptions);
                    }
                } else {
                    $subscriptions =$user->newSubscription('cpd', $plan)->create();
                    $subscriptions->ends_at = $company->admin()->subscription('cpd')->ends_at;
					$subscriptions->save();
                    $subscriptions->setPlanInGroup(true);
                    $this->assignCompany($user,$company,$plan,$subscriptions);

                }
               
            } else {
                if (!$user->subscribed('cpd')) {
                    $plan = Plan::where('price', '0')->where('interval', 'year')->get()->first();
                    $user->newSubscription('cpd', $plan)->create();
                }
            }
            
            $admin = $company->admin();
            if ($admin->additional_users > 0) {
               // $admin->additional_users = $admin->additional_users - 1;
                //$admin->save();
            }
        
    }
    public function assignCompany($user,$company,$plan,$subscriptions){
		
        $subscription_id = $subscriptions->id;
        $subscriptionExist = SubscriptionGroup::where('user_id',$user->id)->where('admin_id',$company->admin()->id)->first();
        if(!$subscriptionExist){
        $subscriptionGroup = SubscriptionGroup::create([
        'user_id'=> $user->id,
        'admin_id'=> $company->admin()->id,
        'plan_id'=> $plan->id,
        'status'=>'1',
        'pricing_group_id'=> $company->admin()->getPricingGroup(),
        'subscription_id'=> @$subscription_id
    ]);
        }
    }

    public function resend_invite($token)
    {
        $invite = $this->check_Invite($token);
        $this->inviteRepository->sendInvite($invite['email'], $invite);

        alert()->success('Your Invitation has been sent to '.$invite->first_name.' '.$invite->last_name, 'Success!')->persistent('Close');
        return back();
    }

    public function cancel_invite($token)
    {
        $invite = $this->check_Invite($token);
        $invite->delete();

        alert()->success('Your Invitation has been deleted', 'Success!')->persistent('Close');
        return back();
    }

    public function cancel_membership($company, $user)
    {
        $member = User::where('id', $user)->first();
        $SubscriptionGroup = SubscriptionGroup::where('admin_id',auth()->user()->id)->where('user_id',$user)->first();
        if($SubscriptionGroup)
        {
            if($member->subscription('cpd')->plan->id == $SubscriptionGroup->plan_id)
            {
                $member->subscription('cpd')->changePlan(45)->save();
                $SubscriptionGroup->delete();
            }
        }
        $membership = CompanyUser::where('company_id', $company)->where('user_id', $user)->delete();
        $invite = Invite::where('email', $member['email'])->orWhere('id_number', $member['id_number'])->first();

      //  $membership->delete();
        if ($invite) {
            $invite->delete();
        }
        

        alert()->success('Your member has been deleted', 'Success!')->persistent('Close');
        return back();
    }

    public function allocate_membership($company, $user)
    {  
        $users = User::find($user);
        $plan = Plan::find(auth()->user()->subscription('cpd')->plan->id); //52
        $this->allocateSubscription($users,$plan);
        $subscription_id = $users->subscription('cpd')->id;
        $subscriptionGroup = SubscriptionGroup::create([
            'user_id'=> $users->id,
            'admin_id'=> auth()->user()->id,
            'plan_id'=> $plan->id,
            'status'=>'1',
            'pricing_group_id'=> auth()->user()->getPricingGroup(),
            'subscription_id'=> @$subscription_id
        ]);
        $user = auth()->user();
        if($user->additional_users > 0)
        {
     //       $user->additional_users = $user->additional_users - 1;
       //     $user->save();
        }
        return redirect()->back();
    }

    public function allocateSubscription($user,$plan)
    {
        
        if (! $user->subscribed('cpd'))
        {
            /* Safety Check */
            $user->subscriptions->where('name','cpd')->each(function ($subscription){
                $subscription->delete();
            });
   
            $user->newSubscription('cpd', $plan)->create();
           
            $description = 'New CPD Subscription started for '.$plan->name.' (' . ucfirst($plan->interval) . 'ly)';
            $note = $this->saveUsernote($type = 'new_subscription', $description);
            $user->notes()->save($note);

            if ($plan->price > 0){
               // $note->invoice()->associate($invoice);
                //$user->fresh()->subscription('cpd')->setInvoiceId($invoice);
            }

            $viewFile = 'emails.upgrades.free_member_cpd';
            $whereTo = env('APP_TO_EMAIL');
            $subject = 'new subscription upgrade processed';
            $from = config('app.email');

           // $this->dispatch(new sendUpgradeNotification($viewFile, $user->fresh(), $note, $from, $whereTo, $subject, '', $plan));

            // Set the agent to the new subscription
            if ($user->fresh()->subscription('cpd')->agent_id == null){
               // $user->fresh()->subscription('cpd')->setAgent(auth()->user());
            }

            
        }else{
        // Set the old plan.
        $old = Plan::find($user->subscription('cpd')->plan->id);

        if ($user->subscription('cpd')->plan->is_custom){
            $user->custom_features->delete();
        }

        $user->subscriptions->where('name','cpd')->each(function ($subscription){
            $subscription->delete();
        });

        // Change the plans for the user.
        $user->subscription('cpd')->plan_id = $plan->id;
        $user->subscription('cpd')->save();

        //$this->saveComprehensiveTopics($request, $plan, $user);

        $new = $plan;

        // Save Note for upgrading subscription
        $description = 'CPD subscription upgraded from ' . $old->name . ' (' . ucfirst($old->interval) . 'ly) to ' . $new->name . ' (' . ucfirst($new->interval) . 'ly)';
        $note = $this->saveUserNote($type = 'subscription_upgrade', $description);

        if ($new->price > 0){
          //  $note->invoice()->associate($invoice);
           // $user->subscription('cpd')->setInvoiceId($invoice);
        }

     
            $period = new Period($new->interval, $new->interval_count, Carbon::now());
            $user->subscription('cpd')->starts_at = $period->getStartDate();
            $user->subscription('cpd')->ends_at = $period->getEndDate();
            $user->subscription('cpd')->canceled_at = NULL;
            $user->subscription('cpd')->save();
      
        if ($user->fresh()->subscription('cpd')->agent_id == null){
           // $user->fresh()->subscription('cpd')->setAgent(auth()->user());
        }

        $viewFile = 'emails.upgrades.notify_staff';
        $oldPlan = $old;
        $newPlan = $user->fresh()->subscription('cpd')->plan;
        $whereTo = env('APP_TO_EMAIL');
        $subject = 'new subscription upgrade processed';
        $from = config('app.email');
        //$this->dispatch(new sendUpgradeNotification($viewFile, $user->fresh(), $note, $from, $whereTo, $subject, $oldPlan, $newPlan));
    }
}

 /*
     * Adding a new note the user profile.
     */
    public function saveUserNote($type, $description)
    {
        $note = new Note([
            'type' => $type,
            'description' => $description,
            'logged_by' => auth()->user()->first_name." ".auth()->user()->last_name,
        ]);
        return $note;
    }

    public function check_Invite($token)
    {
        if (!$invite = Invite::where('token', $token)->first()) {
           return [];
        }
        return $invite;
    }

    protected function generateTemporaryPassword($length)
    {
        return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode($this->getRandomBytes($length + 1))), 0, $length);
    }

    protected function getRandomBytes($nbBytes = 32)
    {
        $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
        if (false !== $bytes && true === $strong) {
            return $bytes;
        } else {
            throw new \Exception("Unable to generate secure token from OpenSSL.");
        }
    }
    public function add_users(Request $request)
    {
         $this->validate($request, [
            'add_user' => 'required'
        ]);
        $user = auth()->user();
        $user->update(['additional_users' => $user->additional_users + $request->add_user]);
        $user->save();
        alert()->success("Additional user added successfully", 'Success!')->persistent('Close');
        return redirect()->back();
    }

    public function AssignCompanyComplete($invite, $user)
    {
        CompanyUser::create([
            'company_id' => $invite->company_id,
            'user_id' => $user->id
        ]);
        $invite->complete();
    }

    public function downloadAllCertificates($userId) {
        $user = User::find($userId);
        $cpds = collect();
        $company_staffs = [];
        if($user->company && $user->company->staff) {
            $company_staffs = $user->company->staff->pluck('name', 'id')->toArray();
            $staff_ids = $user->company->staff->pluck('id')->toArray();
            
            $cpds = Cpd::with('user', 'certificate')->whereIn('user_id',$staff_ids)->get();

            $path = "certificates/$user->id";
            $snappy = app('snappy.pdf.wrapper');
            $pdf = true;
            if (\File::exists(storage_path("certificates/$user->id"))) \File::cleanDirectory(storage_path("certificates/$user->id"));
            foreach($cpds as $cpd) {
                if($cpd->attachment) {
                    $user_name = $cpd->user->name;
                    $filename = "Attachment - ($user_name) - $cpd->id.pdf";
                    $location = $path."/".$filename;
                
                    if(File::exists(public_path($cpd->attachment))) {
                        File::copy(public_path($cpd->attachment), storage_path($location));
                    }
                } elseif($cpd->certificate) {
                    $certificate_name = $this->getCPDCertificateName($cpd);
                $user_name = $cpd->user->name;
                $filename = "$certificate_name - ($user_name) - $cpd->id.pdf";
                $location = $path."/".$filename;
                try {
                    $pdf = $snappy->loadView($cpd->certificate->view_path, compact('pdf', 'cpd'));
                    $pdf->save(storage_path($location));
                } catch(\Exception $e){}
            }
        }
        }

        $files = File::files(storage_path("certificates/$user->id"));
        if(count($files) > 0) {
            $zip = new \ZipArchive();
            $fileName = "$user->name - (staff certificates).zip";
            if ($zip->open(storage_path("certificates/$user->id/$fileName"), \ZipArchive::CREATE)== TRUE)
            {
                foreach ($files as $key => $value){
                    $relativeName = basename($value);
                    $zip->addFile($value, $relativeName);
                }

                $zip->close();
            }
            return response()->download(storage_path("certificates/$user->id/$fileName")); 
        }
        alert()->error('Sorry, No certificates are available.', 'Error!');
        return redirect()->back();
    }

    public function getCPDCertificateName($cpd) {
        $certificate_name = '';
        $view_path = $cpd->certificate->view_path;
        if($view_path == 'certificates.attendance' || $view_path == 'certificates.competence') {
            $ticket = Ticket::with('event', 'event.categories')->where('id', $cpd->certificate->source_id)->first();
            if($ticket) {
                $event = $ticket->event;
                if($event) {
                    $certificate_name = $event->name;
                }
            }                
        }

        if($view_path == 'certificates.assessment') {
            $assessment = Assessment::with('events', 'events.categories')->where('id', $cpd->certificate->source_id)->first();
            if($assessment) {
                $event = $assessment->events()->first();
                if($event) {
                    $certificate_name = $event->name;                
                } else {
                    $certificate_name = $assessment->title;
                }
            }
        }

        if($view_path == 'certificates.wob') {
            $video = Video::with('recordings', 'recordings.pricing', 'recordings.pricing.event', 'recordings.pricing.event.categories')->where('id', $cpd->certificate->source_id)->first();
            if($video) {
                $certificate_name = $video->title;
            }
        }
        return $certificate_name;
    }
}
