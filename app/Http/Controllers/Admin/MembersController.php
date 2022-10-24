<?php

namespace App\Http\Controllers\Admin;

use App\AppEvents\Date;
use App\AppEvents\DietaryRequirement;
use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use App\Body;
use App\DebitOrder;
use App\InvoiceOrder;
use App\Jobs\NotifyClientAboutPTPArrangement;
use App\Jobs\sendEmailAddressChnageToUserJob;
use App\Jobs\sendIdNumberChangeJobToUser;
use App\Jobs\SendOTP;
use App\Note;
use App\NumberValidator;
use App\OTP;
use App\Rep;
use App\Repositories\DebitOrder\DebitOrderRepository;
use App\Repositories\SmsRepository\SmsRepository;
use App\Repositories\Tracking\TrackingRepository;
use App\Store\Cart;
use App\Store\Listing;
use App\Store\Product;
use App\Subscriptions\Models\Feature;
use App\Subscriptions\Models\Subscription;
use Auth;
use App\AuthCode;
use Carbon\Carbon;
use App\Users\User;
use App\Billing\Item;
use App\Http\Requests;
use App\Users\Profile;
use App\Billing\Invoice;
use Bican\Roles\Models\Role;
use Illuminate\Http\Request;
use App\Users\UserRepository;
use App\Subscriptions\Models\Plan;
use App\Http\Controllers\Controller;
use App\Models\Course;
use DB as DBTable;
use App\AppEvents\PromoCode;
use App\Models\CouponDiscount;
use App\Events\CourseSubscibed;
use Illuminate\Support\Facades\DB;
use App\Video; 
use App\AppEvents\Webinar;
use App\Users\Address;
use App\ActivityLog;
use App\Activities\Activity;
use App\MergedProfiles;
use App\Moodle;
use App\Repositories\Sendinblue\SendingblueRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Users\Cpd;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Subscriptions\Models\Period;

class MembersController extends Controller
{
    protected $userRepository;
    private $debitOrderRepository;
    private $smsRepository;
    private $numberValidator;
    private $trackingRepository;
    private $subscriptionRepository;

    public function __construct(UserRepository $userRepository, DebitOrderRepository $debitOrderRepository, SmsRepository $smsRepository, NumberValidator $numberValidator, TrackingRepository $trackingRepository, SubscriptionRepository $subscriptionRepository)
    {
        $this->userRepository = $userRepository;
        $this->debitOrderRepository = $debitOrderRepository;
        $this->smsRepository = $smsRepository;
        $this->numberValidator = $numberValidator;
        $this->trackingRepository = $trackingRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $members = $this->userRepository->allPaginate(10);
        $free = User::whereDoesntHave('subscriptions')->get();

        $subscriptions = Subscription::where('canceled_at', null)->get();

        $plans = Plan::with('subscriptions', 'subscriptions.user')->get()->sortBy('interval');
        return view('admin.members.index', compact('members', 'subscriptions', 'free', 'plans'));
    }

    public function getSubscribers()
    {
        $plans = Plan::with('subscriptions', 'subscriptions.user')->get();
        return view('admin.members.subscribers', compact('plans'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($memberId)
    {
        $member = $this->userRepository->find($memberId);
        if($member) {
            $reps = Rep::all()->pluck('name', 'id');
            $this->trackingRepository->trackProfileView($type = 'profile_viewed', $member, 'Profile has been viewed by ' . auth()->user()->first_name . ' ' . auth()->user()->last_name, auth()->user());
            return view('admin.members.show', compact('member', 'reps'));
        }
        alert()->error('This user is not available, Please check.', 'Error');
        return redirect()->route('admin.search');
    }

    public function activity_log($memberId)
    {
        $member = $this->userRepository->find($memberId);
        $last_login = $member->lastLoginActivity() ? $member->lastLoginActivity()->created_at->format('d-m-y g:i A') : 'N/A';
        return view('admin.members.pages.user_activity', compact('member','last_login'));
    }
    public function createAddress($memberId)
    {
        $member = $this->userRepository->find($memberId);
        return view('admin.members.pages.create_address', compact('member'));
    }
    public function editAddress($memberId,$id)
    {
        $member = $this->userRepository->find($memberId);
        $addresses = $member->addresses()->where('id',$id)->first();
        
        return view('admin.members.pages.edit_address', compact('member','addresses'));
    }

    public function storeAddress(Requests\AddAddressRequest $request,$memberId)
    {
        $member = $this->userRepository->find($memberId);
        $address = [
            'type' => $request->type,
            'line_one' => preg_replace("/[^A-Za-z0-9 ]/", '', $request->line_one),
            'line_two' => preg_replace("/[^A-Za-z0-9 ]/", '', $request->line_two),
            'province' => $request->province,
            'country' => $request->country,
            'city' => $request->city,
            'area_code' => $request->area_code,
        ];

        $member->addresses()->create($address);
        alert()->success('Your new address has been added.', 'Success')->persistent('Close');
        return redirect()->route('member.addresses', [$memberId]);
    }

    public function updateAddress(Request $request,$memberId)
    {
        $address = Address::find($memberId);
        $address->update([
            'type' => $request->type,
            'line_one' => preg_replace("/[^A-Za-z0-9 ]/", '', $request->line_one),
            'line_two' => preg_replace("/[^A-Za-z0-9 ]/", '', $request->line_two),
            'province' => $request->province,
            'country' => $request->country,
            'city' => $request->city,
            'area_code' => $request->area_code,
        ]);
        
         alert()->success('Your new address has been Updated.', 'Success')->persistent('Close');
        return redirect()->route('member.addresses', [$address->user_id]);
    }
    public function account_notes($memberId)
    {
        $member = $this->userRepository->find($memberId);
        return view('admin.members.pages.account_notes', compact('member'));
    }

    public function addresses($memberId)
    {
        $member = $this->userRepository->find($memberId);
        return view('admin.members.pages.addresses', compact('member'));
    }

    public function payment_method($memberId)
    {
        $member = $this->userRepository->find($memberId);
        return view('admin.members.pages.payment_method', compact('member'));
    }

    public function edit($memberId)
    {
        $bodies = Body::all();
        $roles = Role::all()->pluck('name', 'id');
        $member = $this->userRepository->find($memberId);

        $industry = config('signup.industry');
        $member_industry = $member->industry;
        if(substr($member_industry, 0, 5) == 'Other') {
            $member->industry = 'Other';
            $member->other_industry = substr($member_industry, 7);
        }
        return view('admin.members.pages.edit', compact('member', 'roles', 'bodies', 'industry', 'member_industry'));
    }

    public function wallet($memberId)
    {
        $member = $this->userRepository->find($memberId);
        return view('admin.members.pages.wallet', compact('member'));
    }

    public function invoices($memberId)
    {
        $member = $this->userRepository->find($memberId);
        $invoices = Invoice::with(['salesNotes','transactions','creditMemos','salesNotes'])->where('user_id', $memberId)->orderBy('created_at','desc')->paginate(10);
        return view('admin.members.pages.invoices', compact('member', 'invoices'));
    }

    public function orders($memberId)
    {
        $member = $this->userRepository->find($memberId);
        $orders = InvoiceOrder::where('user_id', $memberId)->get();
        return view('admin.members.pages.orders', compact('member', 'orders'));
    }

    public function generate_invoices($memberId)
    {
        $member = $this->userRepository->find($memberId);
        $dietaries = DietaryRequirement::all();
        $items = $this->additionalInvoiceItems();
        $subscription_plans = Plan::where('inactive', false)->get()->sortBy('interval');
        $courses = Course::orderBy('id', 'desc')->get();
        foreach($courses as $course) {
            $price = $course->yearly_enrollment_fee;
            if($course->type_of_course == 'semester') {
                if($course->course_type == 'partially') {
                    $price = $course->semester_price;
                }elseif($course->course_type == 'full') {
                    $price = ($course->semester_price)*($course->no_of_semesters);
                }
            }
            $course->yearly_enrollment_fee = $price * 100;
        }

        return view('admin.members.pages.new_invoice', compact('member', 'dietaries', 'items', 'subscription_plans', 'courses'));
    }

    public function generate_order($memberId)
    {
        PromoCode::clear();
        $events = Event::with(['venues', 'venues.dates', 'extras','promoCodes'])->get();
        $products = collect();// Product::with(['listings'])->has('listings')->get();

        $member = $this->userRepository->find($memberId);
        $dietaries = DietaryRequirement::all();
        return view('admin.members.pages.generate_order', compact('member', 'events', 'products', 'dietaries'));
    }


    public function generate_course_order($memberId)
    {
        PromoCode::clear();
        $course = Course::with('promoCodes')->get();

        $course->each(function ($course){
            unset($course->description);
        });
        $member = $this->userRepository->find($memberId);
        $dietaries = DietaryRequirement::all();
        return view('admin.members.pages.generate_course_order', compact('member', 'course', 'dietaries'));
    }


    public function generate_practice_plan($memberId)
    {
        $plan = Plan::where('is_practice',1)->get();
        
        $plan->each(function ($plans) {
            $new = $plans->features->reject(function ($feature) {
                if ($feature->selectable == false) {
                    return $feature;
                }
            });
            $plans->pricingGroup = $plans->pricingGroup;
            if($plans->pricingGroup->count() && $plans->is_practice){
                //$plans->price=$plans->pricingGroup->min('price');
            }
            unset($plans->features);
            $plans->features = $new;
        });
        $member = $this->userRepository->find($memberId);
        $dietaries = DietaryRequirement::all();
        return view('admin.members.pages.generate_practice_plan', compact('member', 'plan', 'dietaries'));
    }
    public function statement($memberId)
    {
        $member = $this->userRepository->find($memberId);
        return view('admin.members.pages.statement', compact('member'));
    }

    public function upgrade_subscription($memberId)
    {
        $member = $this->userRepository->find($memberId);
        $subscription_plans = Plan::where('invoice_description','NOT LIKE','%Course:%')->where('inactive', false)->where('is_practice','0')->get()->sortBy('interval');

        $subscription_plans->each(function ($plan){
            $new = $plan->features->reject(function ($feature){
                if ($feature->selectable == false){
                    return $feature;
                }
            });
            unset($plan->features);
            $plan->features = $new;
        });

        $topics = Feature::where('year', '2019')->where('name', 'LIKE', '%'.'2019'.'%')->where('selectable', true)->get();
        return view('admin.members.pages.upgrade_subscription', compact('member', 'subscription_plans', 'topics'));
    }


    

    public function courses($memberId)
    {
        $member = $this->userRepository->find($memberId);
        $memmberCourses = $member->courses()->get();
        $courses = Course::all();
       // dd($memmberCourses);
        
        return view('admin.members.pages.courses', compact('member', 'memmberCourses', 'courses'));
    }
    public function update_subscriptions($id,Request $request)
    {
            $data = $request->all();
            $subscription =Subscription::find($id);
            if ($subscription->plan->interval == 'year'){
                alert()->success('You can`t Apply coupon on yearly Plan!', 'Error!');
                return redirect()->back();
            }
            $course = $subscription->course();
            $code = PromoCode::findByCode($data['coupon_code']);
            if(!$course){
                alert()->success('Error to apply coupon!', 'Error!');
                return redirect()->back();
            }
            $CouponDiscount = CouponDiscount::where('course_id',$course->id)->where('user_id',$subscription->user_id)->where('promo_code_id',$code->id)->first();
            
            if($CouponDiscount)
            {
                alert()->success('Coupon Code Already Applied to Course!', 'Error!');
                return redirect()->back();
            }
            $No_of_debit = $course->getPromoCodesDiscountDebitByCode($data['coupon_code']);
            if(!$CouponDiscount){
                $CouponDiscount = CouponDiscount::create(['course_id'=>$course->id,'user_id'=>$subscription->user_id,'promo_code_id'=>$code->id]);
            }
            $subscription->no_of_debit_order = (int) $No_of_debit;
            $subscription->save();
            alert()->success('Coupon applied successfully!', 'Success!');
            return redirect()->back();
    }

    public function event_code_apply($id,Request $request)
    {
            $data = $request->all();
            $code = PromoCode::findByCode($data['coupon_code']);
            if(!$code){
                alert()->success('Error to apply coupon!', 'Error!');
                return redirect()->back();
            }
            $order = InvoiceOrder::find($id);
            $user = User::find($order->user_id);
            if($order->type == 'course'){
                $course = $order->items->where('description','Online Course Access')->first();
                if($course)
                {
                    $courses = Course::where('title',$course->name)->first();
                    if($courses){
                        $discount = $courses->getPromoCodesDiscountAmountByCode($data['coupon_code'],$order->total);
                        $order->discount = $discount;
                        
                    }
                }
            }else{
                $pricing = $order->ticket->event->pricings()->where('venue_id',$order->ticket->venue_id)->first();
           
                $order->discount = ($pricing->getDiscountForUser($user) + $pricing->getPromoCodesDiscount());
            }
            
            $order->save();
           
            alert()->success('Coupon applied successfully!', 'Success!');
            return redirect()->back();
    }

    public function change_course($memberId,$course)
    {
        $member = $this->userRepository->find($memberId);
        $memmberCourses = $member->courses()->where('course_id',$course)->get();
        $courses = Course::all();
        
        
        return view('admin.members.pages.change_course', compact('member', 'memmberCourses', 'courses','course'));
    }
    public function assign_course(Request $request)
    {
        $data = $request->all();
        
        $oldCourse = $data['course_id'];
        $newCourse = $data['newcourse_id'];
        if($oldCourse ==  $newCourse){
            alert()->error('You can`t change same course', 'Error');
            return redirect()->back();
        }
        $memberId =$data['user_id'];
        $member = $this->userRepository->find($memberId);
       
        $courseOld = $member->courses->where('id', (int) $data['course_id'])->first();
        $invoices  = $member->getCourseInvoice($oldCourse);

        if($courseOld){
            $monthy = $courseOld->monthly_plan_id;
            $yearly = $courseOld->yearly_plan_id;
            $subscriptions = Subscription::where('user_id',$member->id)->whereIn('plan_id',[$monthy,$yearly])->get();
            foreach($subscriptions as $subscription){
                $subscription->delete();
            }

        }
        if($invoices){
            foreach($invoices as $invoice){
                $invoiceObject = Invoice::find($invoice);
                if($invoiceObject){
                   // $invoiceObject->cancelInvoiceAndCreditNote($invoiceObject,'Course Invoice cancel due to Change Course');
                }
            }
        }
        
        
        $course = Course::find($data['newcourse_id']);
        
        
        if ($request->course_interval === 'yearly'){
            $amount = $course->monthly_enrollment_fee; 
        }else{
            $amount = $course->yearly_enrollment_fee;
        }
        
        $member->courses()->save($course);
        if(!$invoices){
            $invoice = $this->generateInvoice($member, $course, $request, $amount);
        }

        if($member->moodle_user_id>0 && $course->moodle_course_id>0){
            $enrollUser = new \stdClass();
            $enrollUser->userid = $member->moodle_user_id;
            $enrollUser->roleid = 5; // enroll user as a student
            $enrollUser->courseid = $course->moodle_course_id;
            $moodle = New Moodle();
            $moodle->courseEnroll($enrollUser); 
        }  
       
         
        if ($request->course_interval === 'yearly'){
            $plan = Plan::find($course->yearly_plan_id);
            $subscription = $member->newSubscription('course', $plan)->create();
        }else{
            $plan = Plan::find($course->monthly_plan_id);
            $subscription =  $member->newSubscription('course', $plan)->create();
        }
        
        event(new CourseSubscibed($subscription));

        
        $now = Carbon::now();
        $subscription->starts_at = $course->start_date;
        $subscription->ends_at = $course->end_date;
        $subscription->no_of_debit_order = $course->no_of_debit_order;
        $subscription->completed_semester = 0;
        $subscription->course_type = $course->type_of_course;
        $subscription->completed_order = 0;
    

        if ($request->course_interval == 'yearly'){
            $endsat = Carbon::parse( date_format(Carbon::parse($now), 'D'). ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year)->addMonth(1)->addYear(1);
           
        }else{
            $endsat = Carbon::parse( date_format(Carbon::parse($now), 'D'). ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year)->addMonth(1);
        }

        $subscription->ends_at = $endsat;
        $subscription->save();

        
        if ($request->payment_method == 'debit_order'){
            $date = $member->debit->getSubscriptionAndBillableDate();

          

            $member->debit->update(['bill_at_next_available_date' => true]);
            $member->debit->update(['active' => true]);
            $member->debit->update(['billable_date' => $date]);
            $member->debit->next_debit_date = Carbon::now()->addDay(1)->startOfDay();
            $member->debit->save();
        }
        if(!$invoices){
            $invoice->addItems($this->products);
            $invoice->autoUpdateAndSave();
           // $this->addDebitTransaction($invoice);
        }else{
            $invoiceObject = Invoice::find($invoices->invoice_id);
            $subscription->setInvoiceId($invoiceObject);
            $invoiceObject->items()->delete();
            $item = $this->addItems($member, $course, $request, $amount);
        }
       // $invoice->settle();

        $member->courses()->detach($data['course_id']);
        alert()->success('Course Has been changed successfully!', 'Success!');
        return redirect()->route('member.courses', [$memberId]);

    }

    public function cancel_course($member,$course)
    {
        $member = $this->userRepository->find($member);
        
        $courseOld = $member->courses->where('id', (int) $course)->first();
        if($courseOld){
            $monthy = $courseOld->monthly_plan_id;
            $yearly = $courseOld->yearly_plan_id;
            $subscriptions = Subscription::where('user_id',$member->id)->whereIn('plan_id',[$monthy,$yearly])->get();
            foreach($subscriptions as $subscription){
                $subscription->delete();
            }

        }
        $invoices  = $member->getCourseInvoice($course);
        if($invoices){
            foreach($invoices as $invoice){
                $invoiceObject = Invoice::find($invoice);
                if($invoiceObject){
                    // $invoiceObject->cancelInvoiceAndCreditNote($invoiceObject,'Course Invoice cancel due to Cancel Course');
                }
            }
        }
        
        if($courseOld->moodle_course_id > 0) {
            $course1 = new \stdClass();
            $course1->courseid = $courseOld->moodle_course_id;
            $course1->roleid = '5';
            $course1->userid = $member->moodle_user_id;
            $moodle = new Moodle();
            $output = $moodle->courseUnenroll($course1);
        }

        $member->courses()->detach($course);
        alert()->success('Course Has been canceled successfully!', 'Success!');
        return redirect()->route('member.courses', [$member->id]);

    }
    
    public function addDebitTransaction($invoice)
    {
        $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'debit',
            'display_type' => 'Invoice',
            'status' => 'Open',
            'category' => 'course',
            'amount' => $invoice->total,
            'ref' => $invoice->reference,
            'method' => 'Void',
            'description' => 'Invoice '.$invoice->reference,
            'tags' => "Invoice",
            'date' => Carbon::now()->addSeconds(5)
        ]);
    }
    public function generateInvoice($user, $course, $request, $amount)
    {
        $invoice = new InvoiceOrder;
        $invoice->type = 'course';
        $invoice->setUser($user);
        $invoice->save();

        $item = new Item;
        $item->type = 'course';
        $item->name = $course->title;
        $item->description = 'Online Course Access';
        $item->price = $amount;
        $item->discount = '0';
        $item->item_id = $course->id;
        $item->item_type = get_class($course);
        $item->save();

        $this->products[] = $item;

        // Create Course Invoice Entry...
        // DBTable::table('course_invoice')->insert([
        //     'course_id' => $course->id,
        //     'invoice_id' => $invoice->id
        // ]);

        return $invoice;
    }
    public function addItem($user, $course, $request, $amount)
    {
        $item = new Item;
        $item->type = 'course';
        $item->name = $course->title;
        $item->description = 'Online Course Access';
        $item->price = $amount;
        $item->discount = '0';
        $item->item_id = $course->id;
        $item->item_type = get_class($course);
        $item->save();

        $this->products[] = $item;

        // Create Course Invoice Entry...
        // DBTable::table('course_invoice')->insert([
        //     'course_id' => $course->id,
        //     'invoice_id' => $invoice->id
        // ]);

        return $invoice;
    }

    public function assessments($memberId)
    {
        $member = $this->userRepository->find($memberId);
        return view('admin.members.pages.member_assessments', compact('member'));
    }

    public function events($memberId)
    {
        $member = $this->userRepository->find($memberId);
        $InvoiceOrder = InvoiceOrder::whereHas('items', function ($query) {
            $query->where('item_type','=','App\AppEvents\Event');
        })->where('user_id',$memberId)->where('type','event')->where('status','unpaid')->where('paid',0)->get();
        $items = collect();
        $InvoiceOrderPaid = InvoiceOrder::whereHas('items', function ($query) {
            $query->where('item_type','=','App\AppEvents\Event');
        })->with('items')->where('user_id',$memberId)->where('type','event')->where('status','paid')->where('paid',1)->get();
        $InvoicePaid = Invoice::whereHas('items', function ($query) {
            $query->where('item_type','=','App\AppEvents\Event');
        })->with('items')->where('user_id',auth()->user()->id)->where('type','event')->where('status','paid')->where('paid',1)->get();
        $eventInvoicePaid = $InvoicePaid->pluck('items')->collapse()->pluck('item_id')->toArray();
        $eventPaid = $InvoiceOrderPaid->pluck('items')->collapse()->pluck('item_id')->toArray();
        $paidEvents = array_merge($eventInvoicePaid,$eventPaid);

        $currentPlanEvents = [];
        if($member->subscription('cpd')){
        $currentPlan = $member->subscription('cpd')->plan;
        $events = $currentPlan->getPlanEvents();
        $currentPlanEvents = array_merge($events->pluck('id')->toArray(),$paidEvents);
        }
        foreach($InvoiceOrder as $order)
        {
            foreach($order->items as $item)
            {
                if($item->item_type == get_class(new Event()) && !in_array($item->item_id,$currentPlanEvents)){
                    $items->push($item->item_id);
                }
            }
        }
        $myTickets = $member->tickets();
        $eventpending=$items->toArray();
        if(!empty($eventpending)){
            $myTickets = $myTickets->whereNotIn('tickets.event_id',$eventpending);
        }
        $myTickets = $myTickets->get();
        return view('admin.members.pages.events', compact('member','myTickets'));
    }

    public function company($memberId)
    {
        $member = $this->userRepository->find($memberId);
        $cpds = collect();
        $company_staffs = [];
        if($member->company && $member->company->staff) {
            $company_staffs = $member->company->staff->pluck('name', 'id')->toArray();
            $staff_ids = $member->company->staff->pluck('id')->toArray();
            if(isset(request()->employee) && request()->employee!=""){
                $staff_ids = [request()->employee];
            }
            $cpds = Cpd::with('user', 'certificate')->has('certificate')->whereIn('user_id',$staff_ids);
            $cpds = $cpds->orderBy('date','desc')->paginate(10);
            $cpds = $this->assignCategoryToCPD($cpds);
            $cpds->appends(request()->all());
        }
        return view('admin.members.pages.company_page', compact('member', 'cpds'));
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

    public function sms($memberId)
    {
        $member = $this->userRepository->find($memberId);
        return view('admin.members.pages.sms', compact('member'));
    }

    public function custom_topics($memberId)
    {
        $member = $this->userRepository->find($memberId);
        return view('admin.members.pages.panel_plan_topics', compact('member'));
    }

    public function book_event($memberId)
    {
        $member = User::where('id', $memberId)->first();
        $dietaries = DietaryRequirement::all();
        return view('admin.members.pages.register_for_event', compact('member', 'dietaries'));
    }

    public function cpd_hours($memberId)
    {
        $member = User::where('id', $memberId)->first();
        return view('admin.members.pages.cpd_hours', compact('member', 'dietaries'));
    }

    public function remove_cpd_hours(Request $request, $memberId)
    {
        dd($request->all());
        $member = User::where('id', $memberId)->first();
        return view('admin.members.pages.cpd_hours', compact('member', 'dietaries'));
    }

    public function find_pricings($member, $venueId)
    {
        return response()->json(['pricings' => Pricing::where('venue_id', $venueId)->get()]);
    }

    public function venues($member, $eventId)
    {
        return response()->json(['venues' => $venues = Event::where('id', $eventId)->first()->venues]);
    }

    public function dates($member, $venue_id)
    {
        return response()->json(['dates' => Date::where('venue_id', $venue_id)->get()]);
    }

//
//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        $member = $this->userRepository->find($id);
//        return view('admin.members.edit', compact('member'));
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $this->userRepository->find($id);
        $oldEmailAddress = $user['email'];
        $oldIdNumber = $user['id_number'];

        if($oldEmailAddress != $request->input('email')) {
            $emailCheck = User::where('email', $request->input('email'))->first();
            if($emailCheck) {
                alert()->error('New email address is already assigned to an account.', 'Error');
                return redirect()->back();
            }
        }

        if (isset($request['payment_arrangement'])){
            if (auth()->user()->is('super')){
                $user->update(['payment_arrangement' => $request->payment_arrangement]);
            }
        }

        if ($request->debt_arrangement == 1) {
            if (auth()->user()->is('debt-manager')){
                $user->update($request->except(['_token', 'payment_arrangement']));
                $this->EmailAddressChangeJob($request, $oldEmailAddress, $user);
                $this->IdNumberChange($request, $oldIdNumber, $user);
            }else{
                alert()->error('You are not allowed to provide debt', 'unauthorized');
                return redirect()->back();
            }
        }

        if ($user->body && $request->body_id != $user->body_id){
            $user->update(['membership_verified' => false]);
        }

        $user->roles()->sync($request->roles? : []);
        $user->update($request->except(['_token', 'payment_arrangement']));
        $user->profile->fill($request->except(['_token']))->save();

        // if($request->industry && $request->industry == 'Other') {
        //     $user->industry = $request->industry.': '.$request->other_industry;
        // }
        $user->save();

//        if ($request->has(['bank', 'number'])){
//            if ($user->debit == null){
//                $this->debitOrderRepository->createDebitOrder($request, $user);
//            }else{
//                if ($request->bank != $user->debit()->first()->bank || $request->number != $user->debit()->first()->number){
//                    $this->debitOrderRepository->updateDebitOrder($request, $user);
//                }
//            }
//        }

        $user->settings()->merge($request['settings']);
        $this->EmailAddressChangeJob($request, $oldEmailAddress, $user);
        $this->IdNumberChange($request, $oldIdNumber, $user);

        alert()->success('The profile has been updated!', 'Thank you');
        return redirect()->back()->withInput(['tab' => 'panel_edit_account']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function signInAs($memberId)
    {
        Auth::loginUsingId($memberId);
        alert()->success('Logged in as member', 'Success');
        return redirect()->route('dashboard');
    }

    public function resetPassword($memberId)
    {
        $member = User::findOrFail($memberId);
        $tempPassword = $this->generateTemporaryPassword(6);
        $member->password = $tempPassword;
        $member->save();
        session('sweet_alert.html',true);
        alert()->success("The new password is: " .$tempPassword, 'Password Reset')->persistent('Close');
        return redirect()->route('admin.members.show', [$memberId]);
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

    protected function generateTemporaryPassword($length)
    {
        return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode($this->getRandomBytes($length + 1))), 0, $length);
    }

    public function cancelProfile(Request $request, $user)
    {
        $this->validate($request, ['deleted_at_description' => 'required']);
        $user = User::with('invoices')->find($user);

        if ($user->allUnpaidInvoices()->count() || $user->overdueInvoices()->count()){
            alert()->warning('This account has outstanding invoices, Please check','Warning');
            return back();

        }else{
            // Cancel the user CPD Subscription.
            if ($user->subscribed('cpd')){
                $user->subscription('cpd')->cancel(true);
            }

            // Set the deleted_at_description that was given by the user.
            $user->update([
                'deleted_at_description' => $request->deleted_at_description,
                'status' => 'cancelled'
            ]);

            $user->save();
            $user->delete();

            $sendingblueRepository = new SendingblueRepository();
            $input['emailBlacklisted'] = true;
            $input['smsBlacklisted'] = true;
            $sendingblueRepository->updateContact($user->email, $input);

            alert()->success('The following account was cancelled'.'<br/>'.$user->first_name.' '.$user->last_name ,'Success')->persistent('close');
            return redirect()->route('admin.members.index');
        }
    }

    public function toggleForceSuspend($user)
    {
        $user = User::find($user);
        if ($user->force_suspend == false){
            $this->subscriptionRepository->getSubscriptionInvoicesAndCancel($user);
        }
        $this->userRepository->toggleForceSuspension($user);
        alert()->success('This account has been updated', 'Success!');
        return back();
    }

    public function sendSms(Request $request, $id)
    {
        $this->validate($request, ['message' => 'required']);
        $smsValidator = new NumberValidator();

        if ($smsValidator->validate($request['number']) == true){
            $member = User::findorfail($id);
            $message = $this->smsRepository->sendSms($request, $member);

            if (json_decode($message)->status != 'OK'){
                alert()->error('Your message was not sent! <br> '.json_decode($message)->description, 'Please try again')->persistent('Close');
            }else{
                alert()->success('Your message has been successfully sent!', 'Message has been sent')->persistent('Close');
            }
        }else{
            alert()->error('Your message was not sent!', 'Invalid Number')->persistent('Close');
        }
        return back();
    }

    public function additionalInvoiceItems()
    {
        return $items = collect([
            Item::where('name', 'printed notes')->first(),
            Item::where('name', 'Penalty Fee')->first(),
            Item::where('name', 'group booking')->first(),
            Item::where('name', 'Additional charges')->first(),
        ]);
    }
    public function activeUserAndSubscription($user)
    {
        $user = User::find($user);
        $this->userRepository->unsuspend($user);

        $subscription = $user->activeCPDSubscription();
        if(empty($subscription)){
            $InvoiceGenerated = $user->invoices()->where('type','subscription')->where('created_at','>',Carbon::now()->startOfMonth())->first();
            $active_subscription = $user->subscription('cpd');
            $now = Carbon::now();
            
            if($InvoiceGenerated)
            {
                if(!$active_subscription->ends_at->gt($now))
                {
                    $active_subscription->ends_at = Carbon::today();
                }
                $active_subscription->updatePeriod();
                $active_subscription->save();
            }else
            {
                $active_subscription->renew();
            }
        }

        alert()->success('This account has been updated', 'Success!');
        return back();
    }

    /**
     * @param Request $request
     * @param $oldEmailAddress
     * @param $user
     */
    public function addingEmailAddressChangeNote(Request $request, $oldEmailAddress, $user)
    {
        $note = new Note([
            'type' => 'general',
            'description' => "Email address has been changed from " . $oldEmailAddress . ' to ' . $request['email'] . ' as requested by the user.',
            'logged_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        ]);
        $user->notes()->save($note);
    }

    public function addingIDNumberChangeNote(Request $request, $oldIdNumber, $user)
    {
        $note = new Note([
            'type' => 'general',
            'description' => "ID Number has been changed from " . $oldIdNumber . ' to ' . $request['id_number'] . ' as requested by the user.',
            'logged_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        ]);
        $user->notes()->save($note);
    }

    /**
     * @param Request $request
     * @param $oldEmailAddress
     * @param $user
     * @return void
     */
    public function EmailAddressChangeJob(Request $request, $oldEmailAddress, $user)
    {
        if ($request['email'] != $oldEmailAddress) {
            $this->addingEmailAddressChangeNote($request, $oldEmailAddress, $user);
            $job = (new sendEmailAddressChnageToUserJob($user, $oldEmailAddress))->delay(20);
            $this->dispatch($job);
        }
    }

    /**
     * @param Request $request
     * @param $oldIdNumber
     * @param $user
     * @return void
     */
    public function IdNumberChange(Request $request, $oldIdNumber, $user)
    {
        if ($request['id_number'] != $oldIdNumber) {
            $this->addingIDNumberChangeNote($request, $oldIdNumber, $user);
            $job = (new sendIdNumberChangeJobToUser($user, $oldIdNumber))->delay(20);
            $this->dispatch($job);
        }
    }

    public function ptp_invoice_arrangment(Request $request, $invoiceId)
    {
        $invoice = Invoice::find($invoiceId);

        if ($request->date){
            $invoice->update(['ptp_date' => Carbon::parse($request->date)]);
            $invoice->save();

            // Create a new note here..
            $note = Note::create([
                'type' => 'general',
                'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
                'description' => '#PTP I have contacted the client and setup a promise to pay arrangement on '.$request->date. ' for '. $invoice->reference .' whereby the client agreed upon.'
            ]);

            $invoice->user->notes()->save($note);
            $note->invoice()->associate($invoice);

            // Send email to client confirming the arrangment..
            $job = (new NotifyClientAboutPTPArrangement($invoice));
            $this->dispatch($job);

            alert()->success('Your promise to pay has been setup and the client was notfied', 'Success!')->persistent('close');;
            return back();
        }else{
            $invoice->update(['ptp_date' => null]);
            $invoice->save();

            // Create a new note here..
            $note = Note::create([
                'type' => 'general',
                'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
                'description' => '#PTP The promise to pay for '.$invoice->reference.' was successfully removed.'
            ]);
            $invoice->user->notes()->save($note);

            alert()->warning('Your promise to pay has been removed', 'Success!')->persistent('close');
            return back();
        }
    }

    public function otp(Request $request, $memberId)
    {
       $member = User::find($memberId);
       $otp = rand(10000, 99999);
       $app_name = config('app.name');


        $message = "Use the following OTP to confirm your bank account details: {$otp}. By submitting this form you give {$app_name} permission to debit your account on a monthly basis for the amount of R{$member->subscription('cpd')->plan->price}";
        $number = $this->numberValidator->format($member->cell);

        $this->smsRepository->sendSms([
            'message' => $message,
            'number' => $number
        ], $request->user());

        // Send Email.
        $this->dispatch((new SendOTP($request->user(), $otp)));

        OTP::create([
            'otp' => $otp,
            'user_id' => $member->id,
            'number' => $number,
        ]);

        return response()->json(['otp' => $otp]);
    }
    public function generatePracticePlanOrder(Request $request,$id)
    {
        try{ 

            $this->validate($request, [
                'plan_id',
                'payment_method',
            ]);
  
            $data = $request->all();
            
            DB::transaction(function () use($request, $id){

                // Member
                $member = User::find($id);
                
                $plan = Plan::find($request->plan_id);
                
                // Generate order
                $order = $this->generateSubscriptionOrder($member, $plan,$request);

                $description = 'New CPD Subscription started for '.$plan->name.' (' . ucfirst($plan->interval) . 'ly)';
                $note = $this->saveUsernote($type = 'new_subscription', $description);
                $note->save();

                $order->note()->associate($note);
                $order->save();

                $member->notes()->save($note);
            });

            return redirect()->back();           
        }catch(\Exception $e){
            dd($e);            
        }
    }
    
    protected function subscriptionAssign($request, $staffs)
    {
        foreach ($staffs as $staff) {
            $user = User::find($staff);

            if ($user && $user->subscription('cpd')) {
                $agent = User::find($user->subscription('cpd')->agent_id);
            }

            $user->subscriptions->where('name', 'cpd')->each(function ($subscription) {
                $subscription->delete();
            });
            $this->subscribeMember($request, $staff);

            if (@$agent && @$user->fresh()->subscription('cpd')->agent_id == null) {
                $user->fresh()->subscription('cpd')->setAgent($agent);
            }
        }
    }
    public function generateSubscriptionOrder($user, $plan,$request)
    {
        if($request->staff){
            $staff=  $request->staff;
            if ($plan->pricingGroup->count()) {
                foreach ($plan->pricingGroup as $pricing) {
                    if ((int) $pricing->min_user <= $staff && (int) $pricing->max_user >= $staff) {
                        $plan->price = $pricing->price ;
                        $this->pricingGroup = $pricing->id;
                    }
                }
                $max = $plan->pricingGroup->max('max_user');
                if ($staff > $max) {
                    $priceGroup = $plan->pricingGroup->where('max_user', $max)->first();

                    if ($priceGroup) {
                        $plan->price = $priceGroup->price ;
                        $this->pricingGroup = $pricing->id;
                    }
                }
                foreach ($plan->pricingGroup as $pricing) {
                    if ((int) $pricing->min_user <= $staff && (int) $pricing->max_user == 0) {
                        $plan->price = $pricing->price ;
                        $this->pricingGroup = $pricing->id;
                    }
                }   
            }
        }
        $order = new InvoiceOrder();
        $order->type = 'subscription';
        $order->setUser($user);
        $order->save();
        $item = new Item;
        $item->type = 'subscription';
        $item->name = $plan->name;
        $item->description = $plan->description;
        $item->price = $plan->price;
        $item->item_id = $plan->id;
        $item->item_type = get_class($plan);
        $item->quantity = ($request->staff)?$request->staff:1;
        $item->save();
        $order->addItem($item);
        $order->autoUpdateAndSave();
        return $order;
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
    
    /**
     * generate_webinars_order
     *
     * @param  mixed $memberId
     * @return void
     */
    public function generate_webinars_order($memberId)
    {
        $videos = Video::all()->pluck('title','id'); 
        $member = $this->userRepository->find($memberId);
        $dietaries = DietaryRequirement::all();
        return view('admin.members.pages.generate_webinars_order', compact('member', 'videos', 'dietaries'));
    }
            
    /**
     * generateOrder
     *
     * @param  mixed $request
     * @param  mixed $memberId
     * @return void
     */
    public function generateOrder(Request $request, $memberId)
    {
        $member = User::find($memberId);
        $videos = Video::whereIn('id', $request['videos'])->get();

        // Check if already subscribed for a webinar video
        $subscribedWebinars = $member->webinars->pluck('id')->toArray();
        foreach($videos as $video) {
            if (in_array($video->id, $subscribedWebinars)) {
                alert()->error('Member is already registered to attend the webinar of  "' . $video->title . '".', 'Error')->persistent('Close');
                return redirect()->back();
            }
        }

        DB::transaction(function () use($request, $memberId, $videos){
    
            // Member
            $member = User::find($memberId);

            // Generate webinars_on_demand
            $order = $this->generateWebinarOrder($member, $request, $videos);

            $note = new Note([
                'type' => 'webinars_on_demand',
                'description' => "I have registered ".$member->first_name." ".$member->last_name." for Video ".$videos[0]->title,
                'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
            ]);
            $note->save();
            $order->note()->associate($note);

            $order->addItems($this->products);
            $order->autoUpdateAndSave();

            // Save the note to the account!
            $member->notes()->save($note);
            
        });

        alert()->success('Member has been successfully registered for the webinars on demand!', 'Success!');
        return back();
    }        
    /**
     * generateWebinarOrder
     *
     * @parammixed $member
     * @parammixed $request
     * @parammixed $video
     * @return void
     */
    public function generateWebinarOrder($member, $request,$videos){

        // Find order total
        $order_total = 0;
        foreach($videos as $video) {
            $order_total+=$video->getAmountForUser($member);
        }

        $order = new InvoiceOrder;
        $order->type = 'webinar';
        $order->setUser($member);
        $order->total = $order_total;
        $order->save();

        foreach($videos as $video) {

            $item = new Item; 
        $item->type = 'webinar';
        $item->name = $video->title;
        $item->item_id = $video->id;
        $item->price = $video->getAmountForUser($member);
        $item->item_id = $video->id;
        $item->item_type = get_class($video);
            $item->save();
            $this->products[] = $item;

        }

        return $order;
    }

    public function user_activity_log($memberId)
    {
        
        $activity_log = ActivityLog::select('user_id', DB::raw("NULL as subject_id"), DB::raw("NULL as name"), DB::raw("NULL as subject_type"), DB::raw("model"),DB::raw("model_id"),DB::raw("action_by"),DB::raw("action"), DB::raw("data"), DB::raw("request_url"), DB::raw('"activity_log" as type')) 
        ->where('user_id', '=', $memberId);

        $activities =Activity::select('user_id', DB::raw("subject_id"), DB::raw("name"), DB::raw("subject_type"), DB::raw("Null as model"), DB::raw("Null as model_id"),DB::raw("Null as action_by"),DB::raw("Null as action"), DB::raw("Null as data"), DB::raw("Null as request_url"), DB::raw('"activities" as type')) 
        ->where('user_id', '=', $memberId);

        $allData = $activity_log->union($activities)->get();
        
        $member = $this->userRepository->find($memberId);
        $last_login = $member->lastLoginActivity() ? $member->lastLoginActivity()->created_at->format('d-m-y g:i A') : 'N/A';
        return view('admin.members.pages.user_activity_log', compact('member','last_login'));
    }
    public function export_activity_log($memberId)
    {
        $member = $this->userRepository->find($memberId);
        $staffs = $member->company->staff()->with('tickets','tickets.event','tickets.event.assessments','tickets.pricing','tickets.pricing.recordings')->get();
        $staffs[] = $member;

        return Excel::create('Complete Staff Report '.Carbon::now()->timestamp, function ($excel) use ($staffs) {
            
            $years = range(date("Y"), 2021);
            foreach($years as $year){
                $excel->sheet('SUMMARY REPORT - '.$year, function ($sheet) use ($staffs, $year) {
                    $sheet->appendRow([
                        'Name of Employee',
                        'Email Address',
                        'Last date signed into profile',
                        'Total Verifiable CPD hours',
                        'Total Verifiable CPD hours by user',
                        'Total Verifiable CPD hours by system',
                        'Total non-verifiable CPD hours',
                        'Total events registered for',
                        'Total videos watched'
                    ]);

                
                    foreach($staffs as $staff) {
                        $name = $staff->name;
                        $email = $staff->email;
                        
                        $NoWODWatch = ActivityLog::where('user_id',$staff->id)->whereYear('created_at','=',$year)->where('action','watched')->count();
                        $verifiable = Cpd::where('user_id',$staff->id)->whereYear('created_at','=',$year)->sum('hours');
                        $verifiableByUser = Cpd::whereNotNull('attachment')->where('verifiable', '=',1)->where('user_id',$staff->id)->whereYear('created_at','=',$year)->sum('hours');
                        $verifiableBySystem = $verifiable - $verifiableByUser;
                        $totalHours = Cpd::where('user_id',$staff->id)->whereYear('created_at','=',$year)->sum('hours');
                        $Nonverifiable =$totalHours -$verifiable ;
                        $noOfEventRegister = $staff->tickets()->where('invoice_id','>',0)->whereYear('created_at','=',$year)->count();

                        $sheet->appendRow([
                            $name,
                            $email,
                            $staff->lastLoginActivity() ? $staff->lastLoginActivity()->created_at : 'N/A',
                            ($verifiable>0)?$verifiable:0,
                            $verifiableByUser?$verifiableByUser:0,
                            $verifiableBySystem,
                            $Nonverifiable,
                            $noOfEventRegister,
                            $NoWODWatch
                        ]);
                        

                    }
                });
            }

            $excel->sheet('DETAILED PER EMPLOYEE', function ($sheet) use ($staffs) {
                $sheet->appendRow([
                    'Name of Employee',
                    'Date Completed',
                    'Name of event completed',
                    'Name of Video watched',
                    'Assessment completed - CPD hours',
                    '% Achieved on assessment',
                    'No assessment - CPD hours',
                    'Attendance certficate claimed'
                ]);
               

                foreach($staffs as $staff) {
                    if($staff->tickets->count()){
                        $ActivityLogstaff = ActivityLog::where('user_id',$staff->id)->get();
                        foreach($staff->tickets as $ticket)
                        {
                            $eventName = $ticket->event;
                            $eventTitle = "";  

                            $assessment_completed_hours = 0 ;
                            $percentage = 0 ;
                            $claimed_cpd = null;
                            $attempt = null;
                            $date_completed = '';
                            $assessmentsArray = [];
                            if($eventName)
                            {
                                $claimed_cpd = Cpd::select('cpds.*')
                                                    ->join('certificates', 'cpds.id', '=', 'certificates.cpd_id')
                                                    ->where(function($q) {
                                                        $q->where('certificates.view_path', 'certificates.attendance')
                                                            ->orWhere('certificates.view_path', 'certificates.competence');
                                                    })
                                                    ->where('user_id', $staff->id)
                                                    ->where('certificates.source_id', $ticket->id)
                                                    ->get();

                                $assessments = $eventName->assessments;
                                
                                if(count($assessments) > 0)
                                {
                                    foreach($assessments as $assessment)
                                    {
                                        $assessment_cpd = Cpd::select('cpds.*')
                                                        ->join('certificates', 'cpds.id', '=', 'certificates.cpd_id')
                                                        ->where('certificates.view_path', 'certificates.assessment')
                                                        ->where('cpds.user_id', $staff->id)
                                                        ->where('certificates.source_id', $assessment->id)
                                                        ->first();
                                        
                                        $assessmentsArray[$assessment->id]['cpd'] = $assessment_cpd;
                                        $pass =$assessment->passedAttempts($staff)->orderBy('id','desc')->first();
                                        if($pass)
                                        {
                                            $assessmentsArray[$assessment->id]['attempt'] = $pass;
                                            $assessmentsArray[$assessment->id]['percentage'] = $pass->percentage;
                                        }
                                    }
                                }
                            }
                            
                            if(count($claimed_cpd) > 0) {
                                $no_assessment_hours = 0 ;
                                foreach($claimed_cpd as $cpd) {
                                    $no_assessment_hours += $cpd->hours;
                                }
                                $sheet->appendRow([
                                    $staff->name,
                                    date_format($claimed_cpd->first()->created_at, 'Y-m-d'),
                                    $ticket->event ? $ticket->event->name : 'N/A',
                                    'N/A',
                                    0,
                                    0,
                                    $no_assessment_hours,
                                    count($claimed_cpd) > 0 ? 'Yes' : 'No'
                                ]);
                            }

                            if(count($assessmentsArray) > 0) {
                                
                                foreach($assessmentsArray as $assess_cpd) {
                                    if($assess_cpd['cpd']) {
                                        
                                        $sheet->appendRow([
                                            $staff->name,
                                            isset($assess_cpd['attempt']) ? date_format($assess_cpd['attempt']->created_at, 'Y-m-d') : 'N/A',
                                            $ticket->event ? $ticket->event->name : 'N/A',
                                            'N/A',
                                            $assess_cpd['cpd']->hours,
                                            isset($assess_cpd['percentage']) ? $assess_cpd['percentage'] : 0,
                                            0,
                                            count($claimed_cpd) > 0 ? 'Yes' : 'No'
                                        ]);
                                    }   
                                }
                                
                            }
                            if($ticket->pricing && count($ticket->pricing->recordings) > 0) {
                                foreach($ticket->pricing->recordings as $recording){
                
                                    $wods = $ActivityLogstaff->where('model_id',$recording->video_id)->where('action','watched')->sortByDesc('id')->first();
                                    if($wods) {
                                        $accessTime = ($wods)?$wods->created_at:"";
                                        $wodName = ($wods)?Video::where('id',$wods->model_id)->first()->title:"N/A";
                                    
                                        $sheet->appendRow([
                                            $staff->name,
                                            $accessTime ? date_format($accessTime, 'Y-m-d') : 'N/A',
                                            $ticket->event ? $ticket->event->name : 'N/A',
                                            $wodName,
                                            0,
                                            0,
                                            0,
                                            count($claimed_cpd) > 0 ? 'Yes' : 'No'
                                        ]);
                                    }
                                }
                            }
                        }
             
                    }
                }

            });
        })->export('xls');
    }

   

    public function changePaymentMethod(Request $request, $member) {
        $user = User::find($member);

        $user->payment_method = $request->payment_method;
        $user->save();

        alert()->success('Payment Method Has been updated successfully!', 'Success!');
        return back();
    }

    public function find_user(Request $request) {
        // dd($request->q);
        $usersTransformed = [
            'results' => []
        ];

        if($request->q) {
            // $users = User::where('email', 'LIKE', '%'.$request->q.'%')
            //     ->limit(15)
            //     ->get();
            $users = User::where(function ($query) use ($request) {
                        $query->where('email', 'LIKE', '%'.$request->q.'%')
                            ->orWhere('first_name', 'LIKE', '%'.$request->q.'%')
                            ->orWhere('last_name', 'LIKE', '%'.$request->q.'%');
                    })
                    ->where('is_merged',false)
                    ->limit(15)
                    ->get();

            foreach($users as $user) {
                $arrUser = [];
                $arrUser['id'] = $user->id;
                $arrUser['text'] = $user->first_name.' '.$user->last_name.' <'.$user->email.'>';
    
                $usersTransformed['results'][] = $arrUser;
            }
        }

        return response()->json($usersTransformed);
    }

    public function mergeProfile(Request $request, $current_user) {
		try{
        $merge_user_id = $request->merge_user;
        $merge_user = User::with('cpds', 'cpds.certificate')->where('id', $merge_user_id)->first();
        $current_user = User::with('cpds', 'cpds.certificate')->where('id', $current_user)->first();

        if(!$merge_user) {
            alert()->error('No profile found for selected user.');
            return redirect()->back();
        }

        if($merge_user->id == $current_user->id) {
            alert()->error('Please enter the email of the profile you want to merge.');
            return redirect()->back();
        }

        if( !$current_user->id_number || $current_user->id_number == '' ) {
            if($merge_user->id_number && $merge_user->id_number != '') {
                $current_user->update(['id_number' => $merge_user->id_number]);
            }
        }

        if( !$current_user->cell || $current_user->cell == '' ) {
            if($merge_user->cell && $merge_user->cell != '') {
                $current_user->update(['cell' => $merge_user->cell]);
            }
        }

        MergedProfiles::create([
            'user_id' => $current_user->id,
            'merged_user_id' => $merge_user->id,
            'mergable_id' => $merge_user->id,
            'mergable_type' => get_class($merge_user)
        ]);

        // Merge tickets
        foreach($merge_user->tickets as $ticket) {
            $ticket->update(['user_id' => $current_user->id]);
            MergedProfiles::create([
                'user_id' => $current_user->id,
                'merged_user_id' => $merge_user->id,
                'mergable_id' => $ticket->id,
                'mergable_type' => get_class($ticket)
            ]);
        }

        // Merge WODs
        $current_webinars = $current_user->webinars->pluck('id')->toArray();
        $merge_webinars = $merge_user->webinars->pluck('id')->toArray();
        $unassigned_webinars = array_diff($merge_webinars, $current_webinars);
        $current_user->webinars()->attach($unassigned_webinars);
        foreach($merge_user->webinars as $webinar) {
            MergedProfiles::create([
                'user_id' => $current_user->id,
                'merged_user_id' => $merge_user->id,
                'mergable_id' => $webinar->id,
                'mergable_type' => get_class($webinar)
            ]);
            $merge_user->webinars()->detach($webinar->id);
        }


        // Invoices
        foreach($merge_user->invoices as $invoice) {
            $invoice->update(['user_id' => $current_user->id]);
            MergedProfiles::create([
                'user_id' => $current_user->id,
                'merged_user_id' => $merge_user->id,
                'mergable_id' => $invoice->id,
                'mergable_type' => get_class($invoice)
            ]);
        }

        // Invoices orders
        foreach($merge_user->invoiceOrders as $invoiceOrder) {
            $invoiceOrder->update(['user_id' => $current_user->id]);
            MergedProfiles::create([
                'user_id' => $current_user->id,
                'merged_user_id' => $merge_user->id,
                'mergable_id' => $invoiceOrder->id,
                'mergable_type' => get_class($invoiceOrder)
            ]);
        }

        // Notes
        foreach($merge_user->notes as $note) {
            $note->update(['user_id' => $current_user->id]);
            MergedProfiles::create([
                'user_id' => $current_user->id,
                'merged_user_id' => $merge_user->id,
                'mergable_id' => $note->id,
                'mergable_type' => get_class($note)
            ]);
        }

        // Course subscriptions
        $course_subscriptions = $merge_user->subscriptions()
            ->where('name', 'course')
            ->get();
        foreach($course_subscriptions as $subscription) {
            $subscription->update(['user_id' => $current_user->id]);
            MergedProfiles::create([
                'user_id' => $current_user->id,
                'merged_user_id' => $merge_user->id,
                'mergable_id' => $subscription->id,
                'mergable_type' => get_class($subscription)
            ]);
        }

        // Courses
        $current_courses = $current_user->courses->pluck('id')->toArray();
        foreach($merge_user->courses as $course) {
            if(!in_array($course->id, $current_courses)) {
                $current_user->courses()->attach($course->id);
            }
            MergedProfiles::create([
                'user_id' => $current_user->id,
                'merged_user_id' => $merge_user->id,
                'mergable_id' => $course->id,
                'mergable_type' => get_class($course)
            ]);
        }

        // Subscription
        $merge_subscription = $merge_user->subscription('cpd');
        $current_subscription = $current_user->subscription('cpd');
        $merge = false;
        if($merge_subscription) {
            if($merge_subscription->active() && !$merge_subscription->suspended() && !$merge_subscription->canceled()) {
                if($current_subscription) {
                    if($current_subscription->active() && !$current_subscription->suspended() && !$current_subscription->canceled()) {
                        if($current_subscription->plan->price != 0 && $merge_subscription->plan->price != 0) {
                            if($current_subscription->ends_at < $merge_subscription->ends_at) {
                                $merge = true;
                            }
                        }
                        else if($merge_subscription->plan->price != 0){
                            $merge = true;
                        }
                    }
                    else {
                        $merge = true;            
                    }
                }
                else {
                    $merge = true;
                }

            }
        }

        if($merge) {
            // $merge_subscription->update(['user_id' => $current_user->id]);
            $period = new Period($merge_subscription->plan->interval, $merge_subscription->plan->interval_count, $merge_subscription->starts_at);
            $merge_subscription->user_id = $current_user->id;
            $merge_subscription->ends_at = $period->getEndDate();
            $merge_subscription->save();

            if($current_subscription) {
                $current_subscription->delete();
            }

            MergedProfiles::create([
                'user_id' => $current_user->id,
                'merged_user_id' => $merge_user->id,
                'mergable_id' => $merge_subscription->id,
                'mergable_type' => get_class($merge_subscription)
            ]);

        }

        foreach($merge_user->latestActivities() as $acivity) {
            $data = ['user_id' => $current_user->id];
            if($acivity->action == 'logged in') {
                $data['model_id'] = $current_user->id;
            }
            $acivity->update($data);
            MergedProfiles::create([
                'user_id' => $current_user->id,
                'merged_user_id' => $merge_user->id,
                'mergable_id' => $acivity->id,
                'mergable_type' => get_class($acivity)
            ]);
        }

        if(count($merge_user->cpds) > 0) {
            foreach($merge_user->cpds as $cpd) {
                $merge_cpd = true;
                if($cpd->certificate) {
                    foreach($current_user->cpds as $current_cpd) {
                        if($current_cpd->certificate) {
                            if($current_cpd->certificate->view_path == $cpd->certificate->view_path
                                && $current_cpd->certificate->source_id == $cpd->certificate->source_id
                            ) {
                                $merge_cpd = false;
                                break;
                            }
                        }
                    }
                }
                
                if($merge_cpd) {
                    $cpd->update(['user_id' => $current_user->id]);
                    MergedProfiles::create([
                        'user_id' => $current_user->id,
                        'merged_user_id' => $merge_user->id,
                        'mergable_id' => $cpd->id,
                        'mergable_type' => get_class($cpd)
                    ]);
                }
            }
        }
        
		$merge_user->update(['is_merged'=>true]);
        $merge_user->delete();

        alert()->success('Profiles merged successfully' ,'Success');
        return redirect()->back();
		}catch(\Exception $e)
		{
			return ($e);
		}
    }

    public function member_details($id) {
        $user = User::find($id);
        return response()->json($user);
    }

    public function renew_company_subscription($memberId)
    {
		try{
        $plan = Plan::where('is_practice', 1)->get();
        
        $plan->each(function ($plans) {
            $new = $plans->features->reject(function ($feature) {
                if ($feature->selectable == false) {
                    return $feature;
                }
            });
            $plans->pricingGroup = $plans->pricingGroup;
            if($plans->pricingGroup->count() && $plans->is_practice){
                //$plans->price=$plans->pricingGroup->min('price');
            }
            unset($plans->features);
            $plans->features = $new;
        });
        $member = $this->userRepository->find($memberId);
        $dietaries = DietaryRequirement::all();
        $last_staff_count = $member->isPracticePlan();
        // dd($last_staff_count);
        return view('admin.members.pages.renew_company_subscription', compact('member', 'plan', 'dietaries', 'last_staff_count'));
		}catch(\Exception $e){
			return $e->getMessage();
		}
    }

    public function renewCompanySubscription(Request $request, $id)
    {
        try{ 

            $this->validate($request, [
                'plan_id',
                'staff',
            ]);
  
            // DB::transaction(function () use($request, $id){

                // Member
                $member = User::find($id);
                
                $plan = Plan::find($request->plan_id);
                
                $last_staff_count = $member->isPracticePlan();
                
                $staff_ids = [];
                $staffs = $member->company->staff;
                
                if($request->staff < count($staffs)) {
                    
                    $staff_ids = $request->selected_staffs;
                    $plan = Plan::where('price', '0')->where('interval', 'year')->get()->first();
                    
                    foreach($staffs as $staff) {
                        $user = $staff;
                        if(!(in_array($staff->id, $staff_ids))) {
                            if($user->subscription('cpd')->plan_id != $plan->id) {
                                $this->changePlan($user, $plan);
                            }
                        } elseif(in_array($staff->id, $staff_ids)) {
                            if($user->subscription('cpd')->plan_id != $member->subscription('cpd')->plan_id) {
                                $this->changePlan($user, $member->fresh()->subscription('cpd')->plan);
                            }
                        }
                    }
                    
                }
				

                if($request->staff >= count($staffs)) {
                    foreach($staffs as $staff) {
                        $user = $staff;
                        if($user->subscription('cpd')->plan_id != $member->subscription('cpd')->plan_id) {
                            $this->changePlan($user, $member->fresh()->subscription('cpd')->plan);
                        }
                    }
                }

                $member->additional_users = (int)$request->staff - (int)$last_staff_count;
                $member->save();
                
                $member->fresh()->subscription('cpd')->renew();

            // });
            alert()->success('Subscription renew successfully!', 'Success!');
            return redirect()->back();           
        }catch(\Exception $e){
            return $e->getMessage();            
        }
    }

    public function changePlan($user, $plan) {
        if ($user->subscription('cpd')->plan->is_custom) {
            $user->fresh()->custom_features->delete();
        }

        $user->fresh()->subscriptions->where('name', 'cpd')->each(function ($subscription) {
            $subscription->delete();
        });
        $user->fresh()->newSubscription('cpd', $plan)->create();
    }

    public function updateSubscriptionPeriod(Request $request, $id) {
        $member = User::find($id);
        $subscription = $member->subscription('cpd');

        if($subscription) {

            if($member->hasCompany()
                && $member->hasCompany()->company->admin()
            ) {
                $company_subscription = $member->hasCompany()->company->admin()->subscription('cpd');
                $subscription->starts_at = $company_subscription->starts_at;
                $subscription->ends_at = $company_subscription->ends_at;
            } else {
                $subscription = $subscription->updatePeriod();
            }

            $subscription->save();
        }

        alert()->success('Staff updated successfully!', 'Success!');
        return redirect()->back();
    }

    public function allocateSubscription(Request $request, $id) {
        $user = User::find($id);
        $plan = Plan::find($request->plan_id);
        if (! $user->subscribed('cpd')){

            /* Safety Check */
            $user->subscriptions->where('name','cpd')->each(function ($subscription){
                $subscription->delete();
            });
            
            $subscription = $user->fresh()->newSubscription('cpd', $plan)->create();
            if($user->hasCompany()
                && $user->hasCompany()->company->admin()
            ) {
                $company_subscription = $user->hasCompany()->company->admin()->subscription('cpd');
                $subscription->starts_at = $company_subscription->starts_at;
                $subscription->ends_at = $company_subscription->ends_at;
                
                $subscription->save();
            }
            // dd($user->subscribed('cpd'));
            $description = 'New CPD Subscription started for '.$plan->name.' (' . ucfirst($plan->interval) . 'ly)';
            $note = $this->saveUsernote($type = 'new_subscription', $description);
            $user->notes()->save($note);

            $viewFile = 'emails.upgrades.free_member_cpd';
            $whereTo = env('APP_TO_EMAIL');
            $subject = 'new subscription upgrade processed';
            $from = config('app.email');

           // $this->dispatch(new sendUpgradeNotification($viewFile, $user->fresh(), $note, $from, $whereTo, $subject, '', $plan));

            // Set the agent to the new subscription
            if ($user->fresh()->subscription('cpd')->agent_id == null){
                $user->fresh()->subscription('cpd')->setAgent(auth()->user());
            }

            
        }else{
            // Set the old plan.
            $old = Plan::find($user->subscription('cpd')->plan->id);

            if ($user->subscription('cpd')->plan->is_custom){
                $user->fresh()->custom_features->delete();
            }

            $user->fresh()->subscriptions->where('name','cpd')->each(function ($subscription){
                $subscription->delete();
            });

            // Change the plans for the user.
            $subscription = $user->fresh()->newSubscription('cpd', $plan)->create();
            if($user->hasCompany()
                && $user->hasCompany()->company->admin()
            ) {
                $company_subscription = $user->hasCompany()->company->admin()->subscription('cpd');
                $subscription->starts_at = $company_subscription->starts_at;
                $subscription->ends_at = $company_subscription->ends_at;
                $subscription->canceled_at = NULL;
                $subscription->save();
            }


            $new = $plan;

            // Save Note for upgrading subscription
            $description = 'CPD subscription upgraded from ' . $old->name . ' (' . ucfirst($old->interval) . 'ly) to ' . $new->name . ' (' . ucfirst($new->interval) . 'ly)';
            $note = $this->saveUserNote($type = 'subscription_upgrade', $description);
        
            if ($user->fresh()->subscription('cpd')->agent_id == null){
                $user->fresh()->subscription('cpd')->setAgent(auth()->user());
            }

            $viewFile = 'emails.upgrades.notify_staff';
            $oldPlan = $old;
            $newPlan = $user->fresh()->subscription('cpd')->plan;
            $whereTo = env('APP_TO_EMAIL');
            $subject = 'new subscription upgrade processed';
            $from = config('app.email');
            //$this->dispatch(new sendUpgradeNotification($viewFile, $user->fresh(), $note, $from, $whereTo, $subject, $oldPlan, $newPlan));
        }

        alert()->success('Subscription allocated successfully!', 'Success!');
        return redirect()->back();
    }

    public function removeSubscriptionAndMoveToFreePlan(Request $request, $id) {
        $user = User::find($id);

        if ($user->subscription('cpd')->plan->is_custom){
            if($user->fresh()->custom_features)
                $user->fresh()->custom_features->delete();
        }

        $user->fresh()->subscriptions->where('name','cpd')->each(function ($subscription) {
            $subscription->delete();
        });

        $plan = Plan::where('price', '0')->where('interval', 'year')->get()->first();
        $user->fresh()->newSubscription('cpd', $plan)->create();

        alert()->success('Subscription removed and moved to free plan successfully!', 'Success!');
        return redirect()->back();
    }

    protected function create_moodle_user($memberId)
    {
        // Create Account
        $user = User::find($memberId);
        $paidInvoices = $user->invoices->where('status', 'paid');
        
        if(count($paidInvoices) > 0) {
            $moodleUser = new \stdClass();
            $moodleUser->username = strtolower($user->email);
            $moodleUser->password = $user->password;
            $moodleUser->firstname = $user->first_name;
            $moodleUser->lastname = $user->last_name;
            $moodleUser->email = strtolower($user->email);
            // $moodleUser->city = 'apo';
            $moodleUser->country = 'SA';
            $moodleUser->auth = 'manual';

            $moodle = New Moodle();
            $userMoodle =  $moodle->register($moodleUser);
            // dd($userMoodle);

            $moodle_id = 0;
            if(isset($userMoodle[0]))
            {
                $moodle_id = $userMoodle[0]['id'];
            }
            
            $user->moodle_user_id = $moodle_id;
            $user->save();

            if($moodle_id == 0) {
                alert()->error('There is some problem, Please try again!', 'Error');
            } else {
                alert()->success('Your User has been added with Moodle!', 'Success');
            }
        } else {
            alert()->error('Sorry, User has not made any payment!', 'Error');
        } 

        return back();
    }
}
