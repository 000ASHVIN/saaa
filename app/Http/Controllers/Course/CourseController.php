<?php

namespace App\Http\Controllers\Course;

use App\Billing\Invoice;
use App\Billing\Item;
use App\Card;
use App\Models\Course;
use App\Note;
use App\Peach;
use App\Repositories\DatatableRepository\DatatableRepository;
use App\Subscriptions\Models\Plan;
use App\Video;
use Carbon\Carbon;
use DB as DBTable;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\CalendarLinks\Link;
use App\AppEvents\PromoCode;
use App\Models\CouponDiscount;
use App\Events\CourseSubscibed;
use Illuminate\Support\Facades\Cache;
use Sendinblue\Mailin;
use App\CourseProcess;
use Response;
use Session;
use Illuminate\Support\Facades\Mail;
use App\Donation;
use App\CourseAdresses;
use App\Repositories\InvoiceOrder\InvoiceOrderRepository;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\ContactsApi;
use GuzzleHttp\Client;
use Exception;
use App\Repositories\Sendinblue\SendingblueRepository;
use App\Subscriptions\Models\Subscription;
use App\Moodle;

class CourseController extends Controller
{
    private $peach;
    private $invoiceOrderRepository;
    private $sendingblueRepository;
    public function __construct(Peach $peach,InvoiceOrderRepository $invoiceOrderRepository, SendingblueRepository $sendingblueRepository)
    {
        $this->peach = $peach;
        $this->invoiceOrderRepository = $invoiceOrderRepository;
        $this->sendingblueRepository = $sendingblueRepository;
        $this->middleware('auth', ['except' => ['index', 'show', 'search', 'course_process', 'talk_to_human', 'course_process', 'download_brochure', 'fund_learner']]);
    }

    public function index()
    {
        if(env('APP_THEME') == 'taxfaculty') {

            $qualifications = Course::where('display_in_front','1')
            // ->where('is_publish','1')
            ->where('course_type', 'qualifications')
            ->paginate(50);

            $professional = Course::where('display_in_front','1')
                // ->where('is_publish','1')
                ->where('course_type', 'professional')
                ->paginate(50);

            $short_courses = Course::where('display_in_front','1')
                // ->where('is_publish','1')
                ->where('course_type', 'short')
                ->paginate(50);

            return view('courses.index', compact('qualifications', 'professional', 'short_courses'));

        }
        else {
            $courses = Course::where('display_in_front','1')->paginate(50);
            return view('courses.index', compact('courses'));
        }
    }

    public function show($courseref)
    {
        PromoCode::clear();
        $course = Course::where('display_in_front','1');
        if(is_numeric($courseref)){
			$course = $course->where('reference', $courseref);
		}else{
			$course = $course->where('slug', $courseref);;
        }
        
        $course = $course->first();
        if(!$course){
            alert()->error('Invalid Course.', 'error')->persistent('Close');
            return redirect()->route('courses.index');
        }
        $link = Link::create(str_replace('\'/', '', preg_replace('/[^A-Za-z0-9\-]/', '', $course->title)), $course->start_date, $course->end_date)
            ->address(config('app.name'))
            ->description(htmlentities($course->short_description));

        return view('courses.show', compact('course', 'link'));
    }


    public function enroll($courseref)
    {
        if(request()->has('threeDs'))
            $this->handleThreeDs(request());

       $course = Course::where('display_in_front','1');
        if(is_numeric($courseref)){
			$course = $course->where('reference', $courseref);
		}else{
			$course = $course->where('slug', $courseref);;
        }
        
        $course = $course->first();
        if(!$course){
            alert()->error('Invalid Course.', 'error')->persistent('Close');
            return redirect()->route('courses.index');
        }
        if (auth()->user()->courses->contains($course->id)){
            alert()->warning('Unable to complete the checkout because you already have this course.', 'Warning')->persistent('Close');
            return redirect()->route('courses.index');
        }

        return view('courses.enroll', compact('course'));
    }

    protected function handleThreeDs($request)
    {
        $payment = $this->peach->fetchPayment($request->id);

        if(! Card::where('token', $payment->registrationId)->exists() && $payment->successful()) {
            $card = new Card([
                'token' => $payment->registrationId,
                'brand' => $payment->paymentBrand,
                'number' => $payment->card['bin'] . '******' . $payment->card['last4Digits'],
                'exp_month' => $payment->card['expiryMonth'],
                'exp_year' => $payment->card['expiryYear']
            ]);

            auth()->user()->cards()->save($card);

            if(count(auth()->user()->cards) == 1) {
                auth()->user()->update([
                    'primary_card' => $card->id
                ]);
            }

            alert()->success('Credit card added successfully.', 'Success');
        } else {
            alert()->error('Credit card already added or invalid.', 'Could not save credit card');
        }
    }
    public function validateField(Request $request)
    {
        $this->validate($request, [
            'id_number' => 'required',
            'street_name' => 'required',
            // 'building' => 'required',
            'suburb' => 'required',
            'city' => 'required',
            'province' => 'required',
            'country' => 'required',
            'postal_code' => 'required'
        ], [
            'id_number.required' => 'The ID Number field is required.',
            'street_name.required' => 'The Street number and street name field is required.',
            'building.required' => 'The Complex/Building field is required.',
            'city.required' => 'The City/Town field is required.'
        ]);
    }

    public function post_checkout(Request $request)
    {
        $this->validate($request, [
            'id_number' => 'required',
            'street_name' => 'required',
            // 'building' => 'required',
            'suburb' => 'required',
            'city' => 'required',
            'province' => 'required',
            'country' => 'required',
            'postal_code' => 'required'
        ], [
            'id_number.required' => 'The ID Number field is required.',
            'street_name.required' => 'The Street number and street name field is required.',
            'building.required' => 'The Complex/Building field is required.',
            'city.required' => 'The City/Town field is required.'
        ]);

        $course = Course::find($request['course']);
        $card = Card::find($request['card']);

        $discount  = 0;
        if ($request->enrollmentOption === 'yearly'){
            $amount = $course->yearly_enrollment_fee;
           $discount = $course->annual_discount;
           if($course->type_of_course == 'semester'){
            if($request->course_type == 'partially')
                {
                    $amount = $course->semester_price;
                }elseif($request->course_type == 'full'){
                    $amount = ($course->semester_price)*($course->no_of_semesters);
                }
            }
        
        }else{
            $amount = $course->monthly_enrollment_fee;
        }

        // Enroll the user for this course
        $user = auth()->user();
        $user->courses()->save($course);

        $invoice = $this->generateInvoice(auth()->user(), $course, $request, $amount,$discount);

        $invoice->addItems($this->products);
        $invoice->autoUpdateAndSave();

        $this->addDebitTransaction($invoice);
        $invoice->autoUpdateDiscount();
        if($request->paymentOption == 'eft') {
            $invoice->settle();
            $this->allocatePayment($invoice, $invoice->total - ($invoice->transactions->where('type', 'credit')->sum('amount') + $invoice->discount), "Instant EFT Payment", 'instant_eft');
            $CouponDiscount = CouponDiscount::create(['course_id'=>$course->id,'user_id'=>auth()->user()->id,'promo_code_id'=>PromoCode::GetPromoCode()]);
            // Start Subscription...
            $this->startSubscription($request, $course);
            return response()->json(['message' => 'success','invoice'=>$invoice], 200);
        }

        if($request->paymentOption == 'cc') {
            $payment = $this->peach->charge(
                $card->token,
                $invoice->total - ($invoice->transactions->where('type', 'credit')->sum('amount')  + $invoice->discount),
                '#' . $invoice->reference,
                $invoice->reference
            );

            if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {
                $invoice->settle();
                $this->allocatePayment($invoice, $invoice->total - ($invoice->transactions->where('type', 'credit')->sum('amount') + $invoice->discount), "Credit Card Payment", 'cc');
                $CouponDiscount = CouponDiscount::create(['course_id'=>$course->id,'user_id'=>auth()->user()->id,'promo_code_id'=>PromoCode::GetPromoCode()]);
                // Start Subscription...
                $subscription=$this->startSubscription($request, $course);
                $subscription->setInvoiceId($invoice);
                return response()->json(['message' => 'success','invoice'=>$invoice], 200);

            } else {
                return response()->json([
                    'errors' => $payment['result']['description']
                ], 422);
            }
            return response()->json(['errors' => $course], 422);
        }
    }
    public function showpopup()
    {
        $expiresAt = Carbon::now()->addMinutes(5);
        Cache::put('debit-course-update-' . auth()->user()->id, true, $expiresAt);
    }
    public function startSubscription($request, $course)
    {
        $user = auth()->user();
        $userPaidInvoices = $user->invoices->where('status', 'paid');

        if((int)$course->moodle_course_id < 1) {
            $course = $this->create_moodle_course($course);
        }
        if(count($userPaidInvoices) > 0 && (int)$user->moodle_user_id < 1) {
            $user = $this->create_moodle_user($user);
        }

        if($user->moodle_user_id > 0 && $course->moodle_course_id > 0){
            $enrollUser = new \stdClass();
            $enrollUser->userid = $user->moodle_user_id;
            $enrollUser->roleid = 5; // enroll user as a student
            $enrollUser->courseid = $course->moodle_course_id;
            $moodle = New Moodle();
            $moodle->courseEnroll($enrollUser);  
        }  
        if ($request->enrollmentOption === 'yearly'){
            $plan = Plan::find($course->yearly_plan_id);
            $subscription = $user->newSubscription('course', $plan)->create();
        }else{
            $plan = Plan::find($course->monthly_plan_id);
            $subscription = $user->newSubscription('course', $plan)->create();
        }
        $full_payment = 0 ;
        if($request->course_type == 'full'){
            $full_payment = 1 ;
        }

        $lastSubscrption = Subscription::whereIn('plan_id',[$course->yearly_plan_id,$course->monthly_plan_id])->where('name','course')->whereNotNull('student_number')->orderBy('id','desc')->first();
        $studentNumber = "";
        if($lastSubscrption){
            $arraydata = explode('_',$lastSubscrption->student_number);
            $numberOfCourseStudent = (int)$arraydata[sizeof($arraydata) - 1] + 1 ;
            $studentNumber = $course->id.'_'.date('y').'_'.date('m').'_';
            foreach(explode(' ',preg_replace('/[^a-zA-Z0-9_ -]/s','',$course->title)) as $title){
                if($title != '' && ctype_alpha($title)){
                    $studentNumber .= strtoupper($title[0]);
                }
            }
            $studentNumber .= '_'.$numberOfCourseStudent;
        }else{
            $studentNumber = $course->id.'_'.date('y').'_'.date('m').'_';
            foreach(explode(' ',preg_replace('/[^a-zA-Z0-9_ -]/s','',$course->title)) as $title){
                if($title != '' && ctype_alpha($title)){
                    $studentNumber .= strtoupper($title[0]);
                }
            }
            $studentNumber .= '_1';
            $course->update(['student_number' => $studentNumber]);
        }

        event(new CourseSubscibed($subscription));
        $subscription->student_number = $studentNumber;
        $subscription->starts_at = $course->start_date;
        $subscription->ends_at = $course->end_date;
        $subscription->no_of_debit_order = $course->discounted_debit_order;
        $subscription->completed_semester = 0;
        $subscription->course_type = $course->type_of_course;
        $subscription->full_payment = $full_payment;
        $subscription->completed_order = 0;
        $subscription->save();
        $array =  ['110','115'];
        if ($request->enrollmentOption == 'monthly' && $course->type_of_course == 'semester' && $course->order_price>0){
            if(!in_array($course->id,$array)){
            $orders =  $this->invoiceOrderRepository->generateCourseOrder($user,$course,$request);
            $description = 'New Course order is generated '.$course->title.' (' . ucfirst($request->enrollmentOption) . ')';
            $note = $this->saveUsernote($type = 'course_subscription', $description);
            $user->notes()->save($note);
            }
        }

        $course_addresses = CourseAdresses::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'subscription_id' => $subscription->id,
            'id_number' => $request['id_number'],
            'street_name' => $request['street_name'],
            'building' => $request['building'] ?? '',
            'suburb' => $request['suburb'],
            'city' => $request['city'],
            'province' => $request['province'],
            'country' => $request['country'],
            'postal_code' => $request['postal_code'],
        ]);

        $description = 'New Course Subscription started for '.$plan->name.' (' . ucfirst($plan->interval) . 'ly)';
        $note = $this->saveUsernote($type = 'new_subscription', $description);
        $user->notes()->save($note);
        PromoCode::clear();
        event(new CourseSubscibed($subscription));
        if ($request->enrollmentOption != 'yearly'){
            if(!in_array($course->id,$array)){
            $this->showpopup();
            }
        }
        return $subscription;
    }

    public function create_moodle_course($course) {
        $course1 = new \stdClass();
        $theme = env('APP_THEME') == 'saaa' ? 'saaa' : 'ttf';
        $course1->fullname = strval($course->title);
        $course1->shortname =  strval($theme."-".$course->slug);
        $course1->startdate = strtotime($course->start_date);
        $course1->enddate = strtotime($course->end_date);
        $course1->summary = strval($course->description);
        $course1->categoryid = '3'; 
        $moodle = New Moodle();
        $output = $moodle->courseCreate($course1);
        $moodle_id = 0;

        if(isset($output[0]))
        {
            $moodle_id = intval($output[0]['id']);
        }

        $course->moodle_course_id = $moodle_id;
        $course->save();

        return $course;
    }

    public function create_moodle_user($user)
    {
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

        return $user;
    }

    public function generateInvoice($user, $course, $request, $amount,$discount)
    {
        $invoice = new Invoice;
        $invoice->type = 'course';
        $invoice->discount = $discount;
        $invoice->setUser($user);
        if($course->exclude_vat == 1){
            $invoice->vat_rate = 0;
        }

        /*
        * Add donations if exists
        */
        $donations = $request->donations;
        if($donations) {
            $invoice->donation = $donations;
        }

        $invoice->save();

        $item = new Item;
        $item->type = 'course';
        $item->name = $course->title;
        $item->description = 'Online Course Access';
        $item->price = $amount;
        $item->discount = $discount;
        $item->item_id = $course->id;
        $item->course_type = $request->enrollmentOption;
        $item->item_type = get_class($course);
        $item->save();

        $this->products[] = $item;

        // Create Course Invoice Entry...
        DBTable::table('course_invoice')->insert([
            'course_id' => $course->id,
            'invoice_id' => $invoice->id
        ]);

        return $invoice;
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

    public function allocatePayment($invoice, $amount, $description, $method)
    {
        $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'credit',
            'display_type' => 'Payment',
            'status' => 'Closed',
            'category' => $invoice->type,
            'amount' => $amount,
            'ref' => $invoice->reference,
            'method' => $method,
            'description' => $description,
            'tags' => "Payment",
            'date' => Carbon::now()->addSeconds(30)
        ]);
    }

    public function saveUserNote($type, $description)
    {
        $note = new Note([
            'type' => $type,
            'description' => $description,
            'logged_by' => auth()->user()->first_name." ".auth()->user()->last_name,
        ]);

        return $note;
    }

    public function search_get(Request $request)
    {
        return redirect()->route('courses.index');
    }

    public function search(Request $request)
    {

        $courses = Course::search($request['title'], null, true)->where('display_in_front','1')->get();

        if (count($courses)){
            alert()->success("We found ".count($courses)." matching your search criteria", 'Success');
            return view('courses.search_results', compact('courses'));
        }else{
            alert()->error('We did not find any courses matching this search criteria', 'No Courses Found');
            return back();
        }
    }

    public function course_process(Request $request)
    {
        $validator = Validator::make($request->all(),
         [
             'name' => 'required',
             'mobile'=>'required|digits_between:7,15',
             'email' => 'required|email|max:255',
             'g-recaptcha-response'=>'required|recaptcha'
             ]);

        if ($validator->fails()) {
            Session::put('is_error', 'course_brochure_popup'.$request->course_id);
            return redirect()->back()
                ->withErrors($validator);
        }
        // Split name
        $arr = explode(" ", trim($request->name));
        $first_name = $arr[0];
        unset($arr[0]);
        $last_name = implode(' ', $arr);

        $user = auth()->user();

        $course_process = CourseProcess::createOrUpdate([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $request->email,
            'mobile' => $request->full_number,
            'user_id' => $user?$user->id:0,
            'course_id' => $request->course_id,
            'type' => 'download_brochure'
        ]);
        $course_process->mobile = $request->full_number;
        $course_process->user_id = $user?$user->id:0;
        $course_process->save();
        $course = Course::find($request->course_id);
        if($course) {

            // Add activity for the lead
            $course_process->addActivity('download_brochure', [
                'course_name' => $course->title,
                'course_id' => $course->id
            ]);

            // Send to sendinblue list
            $list_id = $this->getDownloadBrochureList($course);
            if($list_id) {

                $data = [
                    'email' => $request->email,
                    'attributes' => [
                        'FIRSTNAME' => $first_name,
                        'SMS' => $request->full_number
                    ],
                    'listIds' => [
                        (int)$list_id
                    ]
                ];
                
                if($last_name) {
                    $key = env('APP_THEME') == 'taxfaculty'?'LASTNAME':'LAST_NAME';
                    $data['attributes'][$key] = $last_name;
                }
                
                $this->sendingblueRepository->createUpdateContact($data);
               
            }
            Session::put('course', $course->id );
            
        }
        return back();
        
    }

    public function download_brochure($course_id) {
        $course = Course::find($course_id);
        // Download brochure
        if($course->brochure) {
            $file= public_path().'/storage/'.$course->brochure;
            $headers = [];
            return Response::download($file, $course->title.'.pdf', $headers);
        }
    }

    public function talk_to_human(Request $request) {


        $validator = Validator::make($request->all(),
         [
             'name' => 'required',
             'mobile'=>'required|digits_between:7,15',
             'email' => 'required|email|max:255',
             'g-recaptcha-response'=>'required|recaptcha'
             ]);

        if ($validator->fails()) {
            Session::put('is_error', 'talk_to_human_popup');
            return redirect()->back()
                ->withErrors($validator);
        }
        $arr = explode(" ", trim($request->name));
        $first_name = $arr[0];
        unset($arr[0]);
        $last_name = implode(' ', $arr);

        $user = auth()->user();

        // Get or create new lead
        $course_process = CourseProcess::createOrUpdate([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $request->email,
            'mobile' => $request->full_number,
            'user_id' => $user?$user->id:0,
            'course_id' => $request->course_id,
            'type'=>'talk_to_human'
        ]);
        
        // Add activity for the lead
        $course = null;
        if($request->course_id) {
            $course = Course::find($request->course_id);
        }

        // Add activity for the lead
        $courseData = [];
        if($course) {
            $courseData = [
                'course_name' => $course->title,
                'course_id' => $course->id
            ];
        }
        $course_process->addActivity('talk_to_human', $courseData);

        $list_id = env('SENDINBLUE_TALK_TO_HUMAN_LIST_ID', null);
        if($list_id) {

            // Split name
            $arr = explode(" ", trim($request->name));
            $first_name = $arr[0];
            unset($arr[0]);
            $last_name = implode(' ', $arr);

            $data = [
                'email' => $request->email,
                'attributes' => [
                    'FIRSTNAME' => $first_name,
                    'SMS' => $request->full_number
                ],
                'listIds' => [
                    (int)$list_id
                ]
            ];

            if($last_name) {
                $key = env('APP_THEME') == 'taxfaculty'?'LASTNAME':'LAST_NAME';
                $data['attributes'][$key] = $last_name;
            }

            $this->sendingblueRepository->createUpdateContact($data);
        }
        alert()->success('Thank you! Your information submitted successfully.', 'Success');
        return back();

    }

    public function getDownloadBrochureList($course) {

        $list_id = null;
        if($course->sendinblue_list) {
            $list_id = $course->sendinblue_list;
        }
        else {
            
            $folder_id = env('SENDINBLUE_DOWNLOAD_BROCHURE_FOLDER');
            if($folder_id) {

                $data = [
                    'name' => $course->title.' - Download Brochure',
                    'folderId' => (int)$folder_id
                ];
                
                $list = $this->sendingblueRepository->createList($data);

                if(isset($list['id'])) {

                    $course->sendinblue_list = $list['id'];
                    $course->save();

                    $list_id = $list['id'];

                }
                else {
                    Mail::send([], ['course' => $course], function ($m) use ($course) {
                        $m->from(env('APP_EMAIL'), config('app.name'));
                        $m->to(env('APP_TO_EMAIL'), 'System Administrator')->subject('Issue: Unable to create new list in sendinblue');
                        $m->setBody('The system was unable to create a new list in sendinblue for following course: '.$course->title);
                    });
                }

            }
        }
        return $list_id;

    }

    public function call()

    {
        $mailin = new Mailin('https://api.sendinblue.com/v2.0', env('SENDINBLUE_KEY'));
        return $mailin;
    }

    public function fund_learner() {
        return view('pages.courses.fund_a_learner');
    }
}
