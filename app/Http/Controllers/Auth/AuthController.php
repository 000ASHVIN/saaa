<?php

namespace App\Http\Controllers\Auth;

use App\ActivityLog;
use App\Body;
use App\Http\Requests\CreateFreeAccountFormRequest;
use App\Jobs\SendMembershipConfirmation;
use App\Jobs\SendWelcomeEmail;
use App\Repositories\DebitOrder\DebitOrderRepository;
use App\Repositories\Sendinblue\SendingblueRepository;
use App\Users\Address;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;
use Validator;
use App\DebugLog;
use Carbon\Carbon;
use App\DebitOrder;
use App\Users\User;
use App\Billing\Payment;
use App\Mailers\UserMailer;
use Illuminate\Http\Request;
use App\Jobs\CreateUserAccount;
use App\Jobs\SubscribeUserToPlan;
use App\Billing\InvoiceRepository;
use App\Subscriptions\Models\Plan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Billing\Mygate\Mygate;
use App\Services\Billing\Mygate\MyGateCard;
use App\Billing\CreditCardBillingRepository;
use App\Http\Controllers\Auth\subscribeUser;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Moodle;
use App\CourseProcess;
use App\Users\Industry;

class AuthController extends Controller
{
    protected $gateway;
    protected $userMailer;

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Override default redirect path
     */
    protected $redirectPath = 'dashboard/general';

    protected $user;
    private $sendingblueRepository;

    /**
     * Setup Middleware
     * @param UserMailer $userMailer
     * @param SendingblueRepository $sendingblueRepository
     */
    public function __construct(UserMailer $userMailer, SendingblueRepository $sendingblueRepository)
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        $this->userMailer = $userMailer;
        $this->sendingblueRepository = $sendingblueRepository;
    }

    /**
     * Show the application registration form.
     *
     */
    public function getRegister(Request $request)
    {
        $data = Body::all()->sortBy('title');
        $bodies = $data->reject(function ($body){
           if (str_contains($body->title, 'Other')){
               return $body;
           }
        });
        $interest = config('signup.interest');
        $employment = config('signup.employment');
        // $industry = config('signup.industry');
        $industry = Industry::all()->pluck('title', 'id')->toArray();

        $view = 'auth.registration.simple';
        $plan = null;
        if(isset($request->subscription) || isset($request->event)) {
            $view = 'auth.sub_events.registration';
            if($request->has('subscription')) {
                $plan = Plan::find($request->subscription);
            }
        }
        $selectedPlan = $plan;

        return view($view, compact('bodies', 'interest', 'employment', 'industry', 'selectedPlan'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param CreateFreeAccountFormRequest|Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */

    public function postRegister(Request $request)
    {
        \Input::merge(array_map('trim', \Input::except('interest')));
        $rules = $this->validationRules();
        $messages = $this->validationMessages();

        if(config('app.theme')=='taxfaculty'){
            if($request->has('taxpractitioner')) {
                if($request->taxpractitioner == 'on') {
                    $rules['interest'] = 'required';
                    $rules['employment'] = 'required';
                } else {
                    $rules['industry'] = 'required';
                }
            } else {
                $rules['industry'] = 'required';
            }
        } else {
            $rules['industry'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules, $messages);
        if($request->has('previousUrl')) {
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator, 'register')
                    ->withInput();
            }
        } else {
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        if ($request->has('verified')){
            if ($request->verified == 'true'){
                return $this->tryRegistration($request);
            }else{
                alert()->error('Your ID number is not valid.', 'Invalid ID Number');
                return redirect()->back()->withInput();
            }
        }else{
            return $this->tryRegistration($request);
        }
    }

    /**
     * Create address for user and set as primary.
     */
    public function createUserAddress(Request $request, $user)
    {
        $address = new Address(
            [
                'type' => 'billing',
                'primary' => true,
                'line_one' => $request->address_line_one,
                'line_two' => $request->address_line_two,
                'city' => $request->city,
                'province' => $request->province,
                'country' => $request->country,
                'area_code' => $request->area_code,
            ]
        );
        $user->addresses()->save($address);
    }

      /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        if(env('APP_DEBUG')) {
            $this->validate($request, [
                $this->loginUsername() => 'required', 'password' => 'required',
            ]);
        }else{
            $this->validate($request, [
                $this->loginUsername() => 'required', 'password' => 'required'
            ]);
        }

        
         
        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            if($request->has('previousUrl')) {
                if($request->has('subscription')) {
                    $this->redirectPath = $request->previousUrl."?subscription=".$request->subscription;
                }  
            }
            $this->setCurrentLoginLogActivity();
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }
        $redirectTo = redirect($this->loginPath());
        if($request->has('previousUrl')) {
            $redirectTo = redirect()->back();
        }

        return $redirectTo->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    public function setCurrentLoginLogActivity(){
        ActivityLog::create([
            'user_id'=> (auth()->check())?auth()->user()->id:0,
            'model'=> get_class(new User()),
            'model_id'=> (auth()->check())?auth()->user()->id:0,
            'action_by'=> 'manually',
            'action'=> 'logged in',
            'data'=> json_encode( ['email'=>request()->email]),
            'request_url'=> request()->path()
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request $request
     * @return User
     */
    protected function create(Request $request)
    {
        // Create Account
        $user = $this->user = $this->dispatchFromArray(CreateUserAccount::class, [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => trim($request->email),
            'password' => $request->password,
            'cell' => $request->full_number,
            'alternative_cell' => ($request->alternative_cell)?$request->alternative_cell:'',
            'id_number' => ($request->id_number)?$request->id_number:'',
            'membership_number' => $request->membership_number,
            'specified_body' => $request->specified_body,
            'interest' => ($request->interest)? json_encode(@$request->interest):"",
            'employment' =>($request->employment)? @$request->employment:"",
            'industry' => ($request->industry) ? ($request->industry == 'Other' ? $request->industry.': '.$request->other_industry : $request->industry):"",
        ]);

        $moodleUser = new \stdClass();
        $moodleUser->username = strtolower($user->email);
        $moodleUser->password = $request->password;
        $moodleUser->firstname = $user->first_name;
        $moodleUser->lastname = $user->last_name;
        $moodleUser->email = strtolower($user->email);
        // $moodleUser->city = 'apo';
        $moodleUser->country = 'SA';
        $moodleUser->auth = 'manual';
        $moodle = New Moodle();
        $userMoodle =  $moodle->register($moodleUser);
        
        $moodle_id = 0;
        if(isset($userMoodle[0])) {
            $moodle_id = $userMoodle[0]['id'];
        }

        $user->moodle_user_id = $moodle_id;
        $user->save();

        if ($request->has('body') && $request->body != 'null' && $request->body != 'other'){
            $body = Body::find($request->body);
            $body->user()->save($user);
        }

        return $user;
    }

    public function sendWelcomeEmail($user)
    {
        
          // Welcome Email disabled asked by Stiaan 2019/05/09
           $job = (new SendWelcomeEmail($user))->delay(300);
          $this->dispatch($job);
         
    }

    public function sendConfirmationEmailToProfessionalBody($user)
    {
        $job = (new SendMembershipConfirmation($user))->delay(300);
        $this->dispatch($job);
    }

    /**
     * @param CreateFreeAccountFormRequest|Request $request
     * @return RedirectResponse
     * @throws \Exception
     */
    public function tryRegistration(Request $request)
    {
        DB::transaction(function () use ($request) {
            $user = $this->create($request);
            //$this->createUserAddress($request, $user);
            $this->sendWelcomeEmail($user);

            if ($user->body){
                $this->sendConfirmationEmailToProfessionalBody($user);
            }

            // Subscribe use to the free CPD package.
            $plan = Plan::where('price', '0')->where('interval', 'year')->get()->first();
            $user->newSubscription('cpd', $plan)->create();
        });

        $this->sendingblueRepository->createSubscriber($this->user, $listId = [17]);
        Auth::login($this->user);

        // Get or create new lead
        $course_process = CourseProcess::createOrUpdate([
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'email' => $this->user->email,
            'mobile' => $this->user->cell,
            'user_id' => $this->user->id,
            'course_id' => 0,
            'type'=>'profile_created'
        ]);

        // Add activity for the lead
        $courseData = [];
        $course_process->addActivity('profile_created', $courseData);

        if($request->has('previousUrl')) {
            if($request->has('subscription')) {
                $newRedirectPath = $request->previousUrl."?subscription=".$request->subscription;
                return redirect($newRedirectPath);
            }  
        }

        return redirect()->route('profession.plans_and_pricing');
    }

    /**
     * @return array
     */
    public function validationRules()
    {
        $rules = [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'full_number' => 'required|digits_between:10,15',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'terms' => ['required', 'accepted'],
            // 'g-recaptcha-response'=>'required|recaptcha'
            
        ];
        return $rules;
    }

    /**
     * @return array
     */
    public function validationMessages()
    {
        $messages = [
            'required' => 'The :attribute field is required.',
            'required_unless' => 'Please check your ID Number and try again',
            'required_if' => 'Please sepcify the name of your professional body'
        ];
        return $messages;
    }

    public function verify(Request $request)
    {
        if($request->ajax()){
            $email = $request->email;
            $number = $request->number;

            $exists = User::where('email', $email)->orWhere('id_number', $number)->get();

            if (count($exists)){
                $response = array(
                    'status' => 'error',
                    'msg' => 'The email or ID number is already in use. <br> Lets reset it now, <a href="/password/email">click here</a>',
                );
                return Response::json($response);
            }else{
                $response = array(
                    'status' => 'success',
                    'msg' => 'Setting created successfully',
                );
                return Response::json($response);

            }
        }
    }
    public function checklogin(Request $request)
    {
        try{
            $email = $request->email;
            $exists = User::where('email','like','%'.$email.'%')->first();
            
            if (count($exists)){
                $response = array(
                    'status' => 'success',
                    'msg' => 'Please login',
                );
                return Response::json($response);
            }else{
                $response = array(
                    'status' => 'error',
                    'msg' => 'No user Found',
                );
                return Response::json($response);

            }
        }catch(\Exception $e)
        {

        }
    }
}
