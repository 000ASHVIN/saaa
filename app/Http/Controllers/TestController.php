<?php

namespace App\Http\Controllers;

use App\AppEvents\EventRepository;
use App\AppEvents\Ticket;
use App\Billing\Invoice;
use App\Billing\InvoiceRepository;
use App\Billing\Transaction;
use App\Blog\Category;
use App\Body;
use App\DebitOrder;
use App\Events\Event;
use App\Freshdesk;
use App\InvoiceOrder;
use App\Jobs\FileForRob;
use App\Note;
use App\PiUser;
use App\PreCPD;
use App\Profession\Profession;
use App\Repositories\DebitOrder\DebitOrderRepository;
use App\Repositories\InvoiceOrder\InvoiceOrderRepository;
use App\Repositories\StoreRepository\ListingRepository\ListingRepository;
use App\Store\Cart;
use App\Store\Listing;
use App\Store\Product;
use App\Store\ProductListing;
use App\Subscriptions\Models\Feature;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;
use App\Users\Cpd;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class TestController extends Controller
{
    /**
     * @var EventRepository
     */
    private $repository;
    /**
     * @var InvoiceRepository
     */
    private $invoiceRepository;
    /**
     * @var DebitOrderRepository
     */
    private $debitOrderRepository;
    /**
     * @var ListingRepository
     */
    private $listingRepository;
    /**
     * @var InvoiceOrderRepository
     */
    private $invoiceOrderRepository;

    /**
     * TestController constructor.
     * @param EventRepository $repository
     * @param InvoiceRepository $invoiceRepository
     * @param DebitOrderRepository $debitOrderRepository
     */
    public function __construct(ListingRepository $listingRepository, InvoiceOrderRepository $invoiceOrderRepository, EventRepository $repository, InvoiceRepository $invoiceRepository, DebitOrderRepository $debitOrderRepository)
    {
        $this->repository = $repository;
        $this->invoiceRepository = $invoiceRepository;
        $this->debitOrderRepository = $debitOrderRepository;
        $this->listingRepository = $listingRepository;
        $this->invoiceOrderRepository = $invoiceOrderRepository;
    }

    public function test()
    {
        $from = Carbon::parse();
        $to = Carbon::parse();

        $events = \App\AppEvents\Event::where('start_date' ,'>=', '2018-04-01')->where('end_date', '<=', '2019-03-31')->get();
        $ids = [];


        foreach ($events as $event){
            $ids[] = $event->id;
        }

        $this->dispatch(new FileForRob($ids));
        $tickets = Ticket::whereIn('event_id', $ids)->get();

        Excel::create('report', function($excel) use($tickets) {
            $excel->sheet('sheet', function($sheet) use ($tickets){
                $sheet->appendRow([
                    'NAMES OF THE LEARNER',
                    'SURNAME OF THE LEARNER',
                    'ID NUMBER',
                    'ID NUMBER TYPE',
                    'TOPIC / DESCRIPTION OF TRAINING',
                    'START DATE OF TRAINING',
                    'END DATE OF TRAINING',
                    'NQF LEVEL',
                    'NAME OF THE EMPLOYER',
                    'EMPLOYER SDL NUMBER',
                    'EMPLOYER CONTACT DETAILS',
                    'NAME OF THE TRAINING PROVIDER / PROFESSIONAL BODY',
                    'TRAINING PROVIDER OR PROFESSIONAL BODY ACCREDITATION / REGISTRATION NUMBER',
                    'TRAINING PROVIDER / PROFESSIONAL BODY CONTACT DETAILS',
                    'IS TRAINING PROVIDER PRIVATE /PUBLIC ',
                    'LEARNER PROVINCE',
                    'LEARNER LOCAL/DISTRICT MUNICIPALITY ',
                    'SPECIFY LEARNER RESIDENTIAL  AREA',
                    'IS THE LEARNER RESIDENTIAL AREA URBAN / RURAL',
                    'TRAINING COST',
                    'RACE',
                    'GENDER',
                    'AGE',
                    'DISABILITY',
                    'NON-RSA CITIZEN',
                ]);

                foreach ($tickets as $ticket){
                    $sheet->appendRow([
                        $ticket->user->first_name,
                        $ticket->user->last_name,
                        $ticket->user->id_number,
                        '-',
                        $ticket->event->name,
                        $ticket->event->start_date,
                        $ticket->event->start_date,
                        '-',
                        $ticket->user->profile->company,
                        '-',
                        '-',
                        '-',
                        '-',
                        '-',
                        '-',
                        (count($ticket->user->addresses) ? $ticket->user->addresses->first()->province : "N/A"),
                        '-',
                        (count($ticket->user->addresses) ? $ticket->user->addresses->first()->city : "N/A"),
                        '-',
                        ($ticket->event->type == 'webinar' ? "399" : "650"),
                        '-',
                        ($ticket->user->profile->gender ? : "-"),
                        '-',
                        '-',
                        '-',
                    ]);
                }
            });
        })->export('xls');

//        $data = [
//            'incoming' => true,
//            'private' => false,
//            'body' => 'This is me replying to the ticket as the customer...',
//            'user_id' => 29005064456,
//        ];
//        $tickets = Freshdesk::create_ticket_reply(1363, $data);
//
//        return json_decode($tickets->getBody()->getContents(), true);
//        die();


//      $user = User::find(2);
//     dd(date_format($user->subscription('cpd')->created_at, 'd F Y'));

        die('Not Found!');
        //        $tickets = Ticket::where('event_id', '170')->get()->filter(function ($ticket){
//            if ($ticket->invoice && $ticket->invoice->paid){
//                return $ticket;
//            }
//        });
//
//        foreach ($tickets as $ticket) {
//            if (! $ticket->user->orders->contains('product_id', '36')){
//                $user = $ticket->user;
//
//                \DB::transaction(function () use($user){
//
//                    Cart::clear();
//                    $listing = Listing::find(36);
//
//                    $productListing = ProductListing::findOrFail($listing->productListings->first()->id);
//                    $productListing->addToCart();
//
//                    Cart::getAllCartProductListings()->sortBy('discountPercentage');
//                    Cart::getTotalPrice();
//                    Cart::getTotalDiscount();
//                    Cart::getTotalDiscountedPrice();
//
//                    $InvoiceOrder = InvoiceOrder::createFromCart($user);
//                    Cart::assignAllProductListingsToUser($user, $InvoiceOrder);
//
//                    $order = InvoiceOrder::find($InvoiceOrder->id);
//                    $order->payments()->create([
//                        'amount' => $order->balance,
//                        'description' => 'Discount #'.$order->reference,
//                        'date_of_payment' => Carbon::now()->addMinutes(2),
//                        'method' => '',
//                        'tags' => 'discount',
//                    ]);
//
//                    $order->update([
//                        'balance' => $order->total - $order->payments->sum('amount'),
//                        'discount' => $order->payments->where('tags', 'discount')->sum('amount')
//                    ]);
//
//                    if ($order->balance <= 0){
//                        $invoice = $order->convert();
//
//                        $order->paid = true;
//                        $order->status = 'paid';
//                        $order->date_converted = Carbon::now();
//                        $order->updateOrderBalance();
//                        $order->releasePendingOrders();
//                        $order->assignCpdStoreItem();
//                        $order->save();
//
//                        foreach ($order->payments->where('tags', 'payment') as $payment){
//                            $this->allocatePayment($invoice, $payment['amount'], $request['description'], Carbon::parse($request['date_of_payment']), $request['method']);
//                            continue;
//                        }
//
//                        $invoice->fresh()->settle();
//                        $invoice->fresh()->autoUpdateAndSave();
//                        $invoice->update([ 'balance' => $invoice->balance ]);
//                    }
//                    Cart::clear();
//                });
//            }
//        }
//        Cart::clear();
//        dd('done');
//        $defaults = Config::get('app.DefaultBuildYourOwn');
//        $customs = collect();
//
//        foreach ($defaults as $slug){
//            $customs->push(Feature::where('slug', $slug)->first());
//        }
//
//        foreach ($customs as $custom) {
//            dd($custom);
//        }

//        $subscriptions = Subscription::where('plan_id', 2)->active()->get();
//        dd($subscriptions);
//        $body = Body::where('id', 2)->first();
//        $profession = Profession::first();

        //        $tickets = Ticket::has('invoice_order')->where('invoice_id', '')->get();
//        foreach ($tickets as $ticket){
//            if ($ticket->invoice_order){
//                if ($ticket->invoice_order->invoice){
//                    $this->info('Fixing Ticket '.$ticket->id);
//                    $ticket->update(['invoice_id' => $ticket->invoice_order->invoice->id]);
//                }
//            }
//        }
//        $professions = Profession::all();
//        $professions->filter(function ($profession){
//            $profession->plans->filter(function ($plan){
//               if ($plan->interval != 'month'){
//                   return $plan;
//               }
//            });
//        });

        $plans = Plan::where('inactive', '==', false)
            ->where('interval', 'year')
            ->where('price', '!=', 0)
            ->get();

        return view('subscriptions.2017.index', compact('plans'));
//        $debit_order = DebitOrder::where('user_id', 2)->first();
//        $note = new Note([
//            'type' => 'general',
//            'description' => 'Unable to debit account due to payment method not set to Debit Order',
//            'logged_by' => 'system',
//        ]);
//        $debit_order->user->notes()->save($note);
//
//        dd($debit_order->user->notes);
//
////        dd($user->cards->count() == 0);
////        $this->debitOrderRepository->createDebitOrder($request, $user);
//
//        $days29 = collect();
//        $days30to59 = collect();
//        $days60to89 = collect();
//        $days90to119 = collect();
//        $days120 = collect();
//
//        User::all()->each(function($user) use($days29, $days30to59, $days60to89, $days90to119, $days120){
//                foreach ($user->overdueInvoices() as $invoice){
//                    if (Carbon::now()->endOfDay()->diffInDays($invoice->created_at) <= 29){
//                        $days29->push($invoice);
//                    }elseif (Carbon::now()->endOfDay()->diffInDays($invoice->created_at) >= 29 && Carbon::now()->endOfDay()->diffInDays($invoice->created_at) <= 59){
//                        $days30to59->push($invoice);
//                    }elseif (Carbon::now()->endOfDay()->diffInDays($invoice->created_at) >= 59 && Carbon::now()->endOfDay()->diffInDays($invoice->created_at) <= 89){
//                        $days60to89->push($invoice);
//                    }elseif (Carbon::now()->endOfDay()->diffInDays($invoice->created_at) >= 89 && Carbon::now()->endOfDay()->diffInDays($invoice->created_at) <= 119){
//                        $days90to119->push($invoice);
//                    }else{
//                        $days120->push($invoice);
//                    }
//                }
//            });
//
//        $all = collect([
//            '0 - 29 Days' => $days29,
//            '30 - 59 Days' => $days30to59,
//            '60 - 89 Days' => $days60to89,
//            '60 - 89 Days' => $days60to89,
//            '90 - 119 Days' => $days90to119,
//            '120 + Days' => $days120,
//        ]);
//
//        Excel::create('Aging Report', function($excel) use($all) {
//
//            $excel->sheet('Summary', function($sheet) use($all) {
//                $sheet->appendRow([
//                    'Age',
//                    'Total Invoices',
//                    'Store Invoices',
//                    'Event Invoices',
//                    'Subscription Invoices',
//                    'Total Balance',
//                ]);
//
//                foreach ($all as $key => $value){
//                    $sheet->appendRow([
//                        $key,
//                        count($value),
//                        number_format($value->where('type', 'store')->sum('balance'), 2, ".", ""),
//                        number_format($value->where('type', 'event')->sum('balance'), 2, ".", ""),
//                        number_format($value->where('type', 'subscription')->sum('balance'), 2, ".", ""),
//                        number_format($value->sum('balance'), 2, ".", "")
//                    ]);
//                }
//            });
//
//            foreach ($all as $key => $value){
//                $excel->sheet($key, function($sheet) use($key, $value) {
//                    $sheet->appendRow([
//                        'ID',
//                        'Invoice Date',
//                        'Reference',
//                        'Name',
//                        'Email',
//                        'Discounts',
//                        'Payments',
//                        'Total',
//                        'Balance',
//                    ]);
//
//                    foreach ($value as $invoice){
//                        $sheet->appendRow([
//                            $invoice->id,
//                            $invoice->created_at,
//                            '#'.$invoice->reference,
//                            $invoice->user->first_name .' '.$invoice->user->last_name,
//                            $invoice->user->email,
//                            $invoice->transactions->where('tags', 'Discount')->sum('amount'),
//                            $invoice->transactions->where('tags', 'Payment')->sum('amount'),
//                            $invoice->total,
//                            number_format($invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('balance'), 2, ".", ""),
//                        ]);
//                    }
//                });
//            };
//        })->export('xls');

//        echo "29 Days = ".count($days29).' Total R'.number_format($days29->sum('balance'), 2)."</br>".
//            "30 - 59 Days = ".count($days30to59).' Total R'.number_format($days30to59->sum('balance'), 2)."</br>".
//            "60 - 89 Days = ".count($days60to89).' Total R'.number_format($days60to89->sum('balance'), 2)."</br>".
//            "90 - 119 Days = ".count($days90to119).' Total R'.number_format($days90to119->sum('balance'), 2)."</br>".
//            "120 days Plus = ".count($days120).' Total R'.number_format($days120->sum('balance'), 2)."</br>";

//        echo "29 Days = ".count($days29).' Total R'.number_format($days29->sum('balance'), 2)."</br>".
//        "Store R".number_format($days29->where('type', 'store')->sum('balance'), 2)."</br>".
//        "Events R".number_format($days29->where('type', 'event')->sum('balance'), 2)."</br>".
//        "CPD R".number_format($days29->where('type', 'subscription')->sum('balance'), 2);
////        echo "29 Days = ".count($days29).' Total R'.number_format($days29->sum('balance'), 2)."</br>".
////            "Events = ".count($days90to119).' Total R'.number_format($days90to119->sum('balance'), 2)."</br>".
////            "Subscription = ".count($days120).' Total R'.number_format($days120->sum('balance'), 2)."</br>";
//        die();
//
//        $from = Carbon::parse('2018-03-10 07:00:28')->startOfDay();

        /*
             * 0 - 29 days
             * 30 - 59 days
             * 60 - 89 days
             * 90 - 119 days
             * 120 days +
//         */
//        $arears = Carbon::now()->diffInDays($from);
//
//        dd($arears);
//
//
//        $data = Transaction::whereBetween('date', [$from,$to])->orderBy('date')->has('user')->get();
//        dd($data->first());
////
//        $subscribers = Subscription::with('user', 'plan')
//            ->active()
//            ->get();
//
//        $newUsers = collect();
//
//        $users = $subscribers->filter(function ($subscriber) use($newUsers){
//            if ($subscriber->user->addresses){
//                if ($subscriber->user->addresses->contains('province', 'GP')){
//                    $newUsers->push($subscriber->user);
//                };
//            }
//        });
//
//
//        Excel::create('Gauteng CPD Subscribers', function($excel) use($newUsers) {
//            $excel->sheet('sheet1', function($sheet) use($newUsers) {
//                $sheet->appendRow(array(
//                    'Name',
//                    'Last Name',
//                    'Email',
//                    'Cell',
//                    'Package'
//                ));
//
//                foreach ($newUsers as $user) {
//                    $sheet->appendRow(array(
//                        $user->first_name,
//                        $user->last_name,
//                        $user->email,
//                        $user->cell,
//                        $user->subscription('cpd')->plan->name,
//                    ));
//                }
//            });
//        })->export('xls');
    }

    public function get_cpd_new()
    {
        return view('saiba.index');
    }

    public function cpd_new(Request $request)
    {
        return redirect('/auth/register');
//        $this->validate($request, [
//            'first_name' => 'required',
//            'last_name' => 'required',
//            'job_title' => 'required',
//            'company' => 'required',
//            'email' => 'required|email|unique:pre_cpds',
//            'id_number' => 'required|numeric|min:12|unique:pre_cpds',
//            'plan' => 'required',
//            'terms' => 'required',
//            'proffesional_body' => 'required'
//        ]);
//
//        if ($request->has('events')){
//            if (count($request->events) === 8){
//                PreCPD::create($request->except('_token', 'terms'));
//            }else
//                return back()->withInput($request->all())->withErrors(['This package requires that you select 8 seminar topics from the dropdown below in order to proceed.']);
//        }else{
//            PreCPD::create($request->except('_token', 'terms'));
//        }
//
//        $data  = $request->all();
//        if ($request->pi_cover === '1'){
//            return redirect()->route('insurance.index');
//        }else{
//            return view('pages.about.cpd.thank_you', compact('data'));
//        }
    }

    public function cancel_cpd()
    {
        return view('subscription.cancel');
    }

    public function post_cancel_cpd(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'id_number' => 'required',
            'cell' => 'required'
        ]);

        $user = $request->all();
        Mail::send('emails.cancel_subscription', ['user' => $user], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.email'), 'cancellation')->subject('Subscription Cancellation');
        });

        alert()->success('Thank you for the cancellation we will process your request and provide you with feedback as soon as possible', 'success');
        return redirect()->route('home');
    }

    public function pi_insurance()
    {
        return view('pages.about.cpd.pi_insurance');
    }

    public function confirm_email_subscription($email)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('cpd_new');
        }
        return view('pages.about.cpd.confirm', compact('user'));
    }

    public function save_confirm_email_subscription(Request $request)
    {
        $renewals = collect();
        $ToRenew = \DB::table('renewals')->get();

        foreach ($ToRenew as $renewal) {
            $renewals->push($renewal);
        }

        if ($renewals->contains('email', $request->email)) {
            alert()->success('You have already renewed your subscription.', 'Thank You');
            return redirect()->route('dashboard');
        }

        $this->validate($request, ['terms' => 'required']);
        \DB::table('renewals')->insert($request->except('_token', 'terms'));

        if (!$request->has('pi_cover')) {
            alert()->success('Thank you for renewing your subscription', 'success');
            return redirect()->route('home');
        };
        return redirect()->route('insurance.index');
    }

    /**
     * CPD Registrationa and renwal report.
     */
    // For Pre-registrations.
    public function pre_registration()
    {
        return view('admin.cpd_report.pre_registration');
    }

    // For Renewals
    public function cpd_renewals()
    {
        return view('admin.cpd_report.cpd_renewals');
    }

    public function testingapiview()
    {

        return view('testmeapi');
    }

    public function pi_users()
    {
        $users = PiUser::with('piAssessment', 'piAddresses')->get();
        Excel::create('users', function ($excel) use ($users) {
            $excel->setTitle('PI Users');
            $excel->sheet('Users', function ($sheet) use ($users) {
                $sheet->prependRow([
                    'First Name',
                    'Last Name',
                    'Cell',
                    'Registered',
                    'Professional Body',
                    'Email',
                    'Declined',
                    'Declined Reason',
                    'Legal Entity',
                    'Negligence',
                    'Practice Abroad',
                    'Work Abroad',
                    'Do you adhere to a Code of Conduct that is equal or similar to the IFAC Code of Conduct?',
                    'Are your CPD hours up to date as required by your professional body?',
                    'Do you use engagement letters for all clients?',
                    'Do you have access to the latest technical knowledge or library?',
                    'Do you have the required infrastructure and resources to perform professional work for clients?',
                    'Do you or your firm perform reviews of all work performed by your professional support staff?',
                    'Do you apply relevant auditing and assurance standards when issuing reports on financial statements for clients?',
                    'Do you use the latest technology and software to manage your practice and perform professional work?',
                ]);

                foreach ($users as $user) {
                    $SystemUser = User::where('email', $user->email)->get()->first();
                    $sheet->appendRow([
                        $user->first_name,
                        $user->last_name,
                        ($SystemUser) ? $SystemUser->cell : 'None',
                        $user->registered,
                        $user->body,
                        $user->email,
                        $user->declined,
                        $user->declined_reason,
                        $user->legal_entity,
                        $user->negligence,
                        $user->practice_abroad,
                        $user->work_abroad,
                        ($user->piAssessment['conduct'] ? 'Yes' : 'No'),
                        ($user->piAssessment['cpd'] ? 'Yes' : 'No'),
                        ($user->piAssessment['engagement'] ? 'Yes' : 'No'),
                        ($user->piAssessment['technical'] ? 'Yes' : 'No'),
                        ($user->piAssessment['resources'] ? 'Yes' : 'No'),
                        ($user->piAssessment['reviews'] ? 'Yes' : 'No'),
                        ($user->piAssessment['standards'] ? 'Yes' : 'No'),
                        ($user->piAssessment['technology'] ? 'Yes' : 'No'),
                    ]);
                }
            });
        })->export('xls');
    }

    public function allwebsitesubcribernow()
    {
        $subscription = Subscription::where('plan_id', '!=', 6)->with('user', 'plan')->get()->unique('user')->toArray();
        $subscription = array_map(function ($subscription) {
            return array_dot($subscription);
        }, $subscription);

        Excel::create('Filename', function ($excel) use ($subscription) {
            $excel->sheet('Sheetname', function ($sheet) use ($subscription) {
                $sheet->fromArray($subscription);
            });
        })->export('xls');
    }

    public function fixInvoicesNow()
    {
        $invoices = Invoice::with('user')->where('created_at', '2016-10-01 08:00:00')->where('cancelled', '1')->get();
        Excel::create('Filename', function ($excel) use ($invoices) {
            $excel->sheet('Sheetname', function ($sheet) use ($invoices) {
                foreach ($invoices as $invoice) {
                    if ($invoice->user) {
                        $sheet->appendRow([
                            'Email', $invoice->user->email
                        ]);
                    }
                }
            });
        })->export('xls');

//        $invoices = Invoice::where('created_at', '2016-10-01 08:00:00')
//                            ->where('paid', 0)
//                            ->where('total', '>', 3500)
//                            ->get();
//
//        foreach ($invoices as $invoice) {
//
//            if($invoice->cancelled)
//                continue;
//
//            // Create Credit note
//            $invoice->transactions()->create([
//                'user_id' => $invoice->user->id,
//                'invoice_id' => $invoice->id,
//                'type' => 'credit',
//                'display_type' => 'Credit Note',
//                'status' => 'Closed',
//                'category' => $invoice->type,
//                'amount' => $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount'),
//                'ref' => $invoice->reference,
//                'method' => 'Void',
//                'description' => "Invoice #{$invoice->reference} cancellation",
//                'tags' => "Cancellation",
//                'date' => Carbon::now()
//            ]);
//
//            // Set Cancelled on Invoice
//            $invoice->cancelled = 1;
//            $invoice->status = 'cancelled';
//            $invoice->save();
//
//            var_dump($invoice->total . ' was cancelled <br />');
//        }
    }

    public function getinvoices()
    {
        $invoices = Invoice::whereDate('created_at', '=', Carbon::parse('28 November 2016'))
                            ->where('type', 'store')
                            ->get();

        Excel::create('28 November 2016', function ($excel) use ($invoices) {
            $excel->sheet('sheet1', function ($sheet) use ($invoices) {
                $sheet->appendRow([
                    'Invoice Date',
                    'Invoice Reference',
                    'Invoice Sub Total',
                    'Invoice Total',
                    'Invoice Discount',
                    'Invoice Total minus Discount',
                    'Invoice Total Due'
                ]);

                foreach ($invoices as $invoice) {
                    if ($invoice->user) {
                        $sheet->appendRow([
                            $invoice->created_at->toFormattedDateString(),
                            '#' . $invoice->reference,
                            round($invoice->sub_total, 2),
                            round($invoice->total, 2),
                            round($invoice->invoice_discount, 2),
                            round($invoice->total, 2) - round($invoice->invoice_discount, 2),
                            round($invoice->total_due, 2)
                        ]);
                    }
                }
            });
        })->export('xls');
    }

    public function getExport()
    {
        $subscriptions = \App\Subscriptions\Models\Subscription::renewable()->get();
        dd($subscriptions);
//        $users = User::whereBetween('created_at', [Carbon::parse('30 June 2017'), Carbon::parse('31 August 2017')])->get();

//        $users = User::where('status', 'suspended')->get();

        /*
         * Suspended Users.
         */
//
//        $cpdUsers = $users->reject(function ($user){
//           if (! $user->subscribed('cpd')){
//               return $user;
//           }
//        });
//
//        $data = $cpdUsers->filter(function ($user){
//            $invoices = $user->invoices->where('type', 'subscription')->where('status', 'unpaid');
//            if ($invoices->count() >= 1){
//                return $user;
//            }
//        });

//
//        Excel::create('Suspended Users', function($excel) use($users) {
//        $excel->sheet('sheet', function($sheet) use ($users){
//            $sheet->appendRow([
//                'User ID',
//                'Join Date',
//                'Email',
//                'Payment Method',
//                'Invoices',
//                'ID Number',
//                'Cell',
//                'Subscription'
//            ]);
//
//            foreach ($users as $user){
//                if($user->subscribed('cpd')){
//                    if (count($user->invoices->where('status', 'unpaid'))  > 1){
//                        $sheet->appendRow([
//                            $user->id,
//                            date_format($user->created_at, 'd F Y'),
//                            $user->email,
//                            $user->payment_method,
//                            $user->invoices->where('status', 'unpaid')->count(),
//                            $user->id_number,
//                            $user->cell,
//                            $user->subscription('cpd')->plan->name.' '.$user->subscription('cpd')->plan->interval.'ly'
//                        ]);
//                    }
//
//                }else{
//                    if (count($user->invoices->where('status', 'unpaid')) > 1) {
//                        $sheet->appendRow([
//                            $user->id,
//                            date_format($user->created_at, 'd F Y'),
//                            $user->email,
//                            $user->payment_method,
//                            $user->invoices->where('status', 'unpaid')->count(),
//                            $user->id_number,
//                            $user->cell,
//                            "No Plan"
//                        ]);
//                    }
//                }
//            }
//        });
//    })->export('xls');

//
//        // Invoices extract for Stiaan from date to date
//        $invoices = \App\Billing\Invoice::with('user')->whereBetween('created_at', [Carbon::parse('1 January 2017'), Carbon::parse('31 July 2017')])->get();
//        Excel::create('Invoices from 1 January 2017 - 31 July 2017', function($excel) use($invoices) {
//            $excel->sheet('sheet', function($sheet) use ($invoices){
//                $sheet->appendRow([
//                    'Name',
//                    'Email',
//                    'Cell',
//                    'Date',
//                    'reference',
//                    'total',
//                    'type',
//                    'Status',
//                ]);
//
//                foreach ($invoices as $invoice){
//                    $sheet->appendRow([
//                        $invoice->user->first_name. ' '.$invoice->user->last_name,
//                        strtolower($invoice->user->email),
//                        $invoice->user->cell,
//                        date_format($invoice->created_at, 'd F Y'),
//                        $invoice->reference,
//                        $invoice->total,
//                        $invoice->type,
//                        ($invoice->paid ? "Yes": "No"),
//                    ]);
//                }
//            });
//        })->export('xls');
    }

    public function register()
    {
        $user = User::find(2630);
        dd($user->subscription('cpd')->active());
    }

    public function testme()
    {
        $SAITUsers = [
            'smithfrik@yahoo.com',
            'linda@countabean.co.za',
            'yvette@yvdm.co.za',
            'john.dladla3@gmail.com',
            'ms@melodiecorp.co.za',
            'brents@mweb.co.za',
            'dma@mweb.co.za',
            'elanzagrobbelaar@yahoo.com',
            'bonita@woodacc.co.za',
            'magdatheron@polka.co.za',
            'marianne@gvsbrokers.co.za',
            'phia@trusteeze.co.za',
            'claassens.deb@gmail.com',
            'anmarie@bundance.co.za',
            'alp@netactive.co.za',
            'frank@frankr.co.za',
            'info@ismco.co.za',
            'magda@olwen.co.za',
            'nigelinem@gmail.com',
            'lynstorm09@gmail.com',
            'florencemathe@yahoo.com',
            'annarie.muller@gmail.com',
            'xshabalala@bwlog.com',
            'info@gardenroutebusinesshub.co.za',
            'bca@iafrica.com',
            'Tshepangradebe@yahoo.com',
            'henniek@henniekrugeracc.co.za',
            'hannes@clearcutaccounting.co.za',
            'willymbayi@gmail.com',
            'mike@shamubookkeeping.co.za',
            'macheke.jennifer@gmail.com',
            'Hettieh@triplelineacc.co.za',
            'dr@drothman.co.za',
            'menelisi@dumayoaccounting.co.za',
            'raylene.elephant@live.co.za',
            'millicentm@agridelights.co.za',
            'nyirongo2007@gmail.com',
            'charmaine@insentive.co.za',
            'diebubbs@gmail.com',
            'mjmcgonigle@yahoo.com',
            'christian@cherbst.co.za',
            'aaco@wirelessza.co.za',
            'phenias@smmaccounting.co.za',
            'helene@joyfin.co.za',
            'ntsimeconsulting@mweb.co.za',
            'eddie@kpf.co.za',
            'divan@jcdbusiness.co.za',
            'roelf.brink@lad-fc.co.za',
            'janet@cpma.cc',
            'lourette@lcmpty.com',
            'lcmpty@mweb.co.za',
            'LATIB@TELKOMSA.NET',
            'sebastian@rex-fin.co.za',
            'reception@metricgroup.net',
            'jakedubuisson@gmail.com',
            'Carladt@me.com',
            'davidcandicey@gmail.com',
            'mnetshandama@sars.gov.za',
            'sanmari@accounted.co.za',
            'michelle@atomic-ac.co.za',
            'maya@accounthing.co.za',
            'robyn@swiftskillsacademy.co.za',
            'derrick@cybersmart.co.za',
            'dane@atmarket.co.za',
            'christine@dekeur.co.za',
            'marlene@dekeur.co.za',
            'indileni7@gmail.com',
            'hashilhira@gmail.com',
            'janet.malcolm3@gmail.com',
            'yolandi@smbaccountants.co.za',
            'mariska@veritasacc.com',
            'cbaservices4u@gmail.com',
            'naren@narrak.co.za',
            'shah@snkfinan.co.za',
            'kenny.mhangu@cebolenkosi.co.za',
            'willem@burrek.co.za',
            'phillip@shifren.co.za',
            'admin@financial-mill.co.za',
            'jistrydom71@gmail.com',
            'info@123accounting.co.za',
            'ggabby@mweb.co.za',
            'cathycoetsee@gmail.com',
            'thirusha@controlpro.works',
            'sunika.melck@gmail.com',
            'slester@intelliworx.co.za',
            'delia@dfaccounting.co.za',
            'semakula@accamail.com',
            'vasapolli@telkomsa.net',
            'ikahengm@lantic.net',
            'sjchiromo@vodamail.co.za',
            'mike@mlfs.co.za',
            'info@Lpac.co.za',
            'yoerlemans@gmail.com',
            'ett7@mweb.co.za',
            'lopestax@gmail.com',
            'celesteh@uisol.co.za',
            'noxolo@chemin.co.za',
            'rowan.marais@crowehorwath.co.za',
            'mornes@morneschutteaccountingandtaxservices.co.za',
            'accounts@ftsa.co.za',
            'gskuhn@tiscali.co.za',
            'karen@hage.co.za',
            'triplets@live.co.za',
            'jhfinance@telkomsa.net',
            'tanya@eclipseaccounting.co.za',
            'michelle@finitesolutions.co.za',
            'reinet@vsca.co.za',
            'mimi@mimigreyling.co.za',
            'elizha@rennol.co.za',
            'angelique@fussfree.co.za',
            'chris@airventcape.co.za',
            'esther.acctax@gmail.com',
            'lorraine@sharpbooks.co.za',
            'ayesha@almaccounting.co.za',
            'anrich@finleys.co.za',
            'luthfiya@accsolutionz.co.za',
            'mcfah@vodamail.co.za',
            'marelize@taxcat.co.za',
            'richard@nautilusct.co.za',
            'michellewesvaal@telkomsa.net',
            'cvandenheever@dttrust.co.za',
            'rlabuschagne@lantic.net',
            'chris@bredekamp.co.za',
            'bherman298@gmail.com',
            'bertinaf@telkomsa.net',
            'philip@krinkin.co.za',
            'dja@dja.co.za',
            'davebades@gmail.com',
            'krugeben@gmail.com',
            'shilak2@gmail.com',
            'farmidah@lantic.net',
            'athol@gtpe.co.za',
            'accounts@findata.co.za',
            'elseadri@yahoo.com',
            'i.dwyer@habibgroup.co.za',
            'yvettekok@vodamail.co.za',
            'info@patscc.co.za',
            'ncumisaservices@gmail.com',
            'ferdi@fhrek.co.za',
            'travis@imbokotho.com',
            'schen@daberistic.com',
            'info@dryk.co.za',
            'jibstax@gmail.com',
            'accounting2009@gmail.com',
            'theac@mweb.co.za',
            'colettedp@outlook.com',
            'charl@cmv.co.za',
            'engela@eafs.co.za',
            'debbie@debbiemta.com',
            'lynne.jones@geobrugg.com',
            'janine@msdbn.co.za',
            'h.var.coe@hotmail.com',
            'banda@mweb.co.za',
            'rajesh@blue-platinum.co.za',
            'andre@vismagie.co.za',
            'riaandt@cmv.co.za',
            'accountantjhb1@ipacc.co.za',
            'renate@ipacc.co.za',
            'teresa@worldwidetax.co.uk',
            'advancedfinsolutions@gmail.com',
            'riana@debeerfarms.co.za',
            'hloksaphl@gmail.com',
            'lee@accbus.co.za',
            'nitaleroux@telkomsa.net',
            'ledbury@mweb.co.za',
            'Gareth@GLAccTax.co.za',
            'info@tax-practice.com',
            'nicky@wilcos.co.za',
            'debi.malan@afbgroup.co.za',
            'ernest@bachus.co.za',
            'david@djmacc.co.za',
            'bbahlmann@lantic.net',
            'Minisha.kassen@firstrand.co.za',
            'yollast@gmail.com',
            'dkwfinance@mwebbiz.co.za',
            'nicolette@toaccounting.co.za',
            'terry.sallie@gmail.com',
            'andrea@dk-group.co.za',
            'umeshsaiba@gmail.com',
            'annalize@cranecert.co.za',
            'kobus.froneman@gmail.com',
            'belinda@ban.co.za',
            'theresa@greylingassociates.co.za',
            'tracyleemanthe@gmail.com',
            'kobus@mulleraccountants.co.za',
            'graham@mulleraccountants.co.za',
            'estelle@profacc.co.za',
            'bradleylanning@me.com',
            'marianne@perfect-solutions.co.za',
            'cobus.duplessis@enterprises.up.ac.za',
            'jp@smarttax.co.za',
            'lerato@hotslots.biz',
            'contessavantonder@gmail.com',
            'jhkounzie@gmail.com',
            'corne@simpsonaccounting.co.za',
            'annelienf@mweb.co.za',
            'yvonne@moolmangroup.co.za',
            'pjbobbert@telkomsa.net',
            'jpspieters@mweb.co.za',
            'taxpert@lantic.net',
            'zinash@intekom.co.za',
            'yolande@iconnection.co.za',
            'nicole@ardley.co.za',
            'cprout@dbit.co.za',
            'PRITHIE@EFFICACY.CO.ZA',
            'vivian@anzacrs.co.za',
            'renier@taxfinbiz.co.za',
            'vdheevernelia@gmail.com',
            'zelda.visagie@me.com',
            'janmalan@mweb.co.za',
            'helena@hsrek.co.za',
            'simon@iridium.co.za',
            'wouter@rousant.co.za',
            'peter@iridium.co.za',
            'markvh@nettrand.co.za',
            'anneries.as@gmail.com',
            'hilton@iafrica.com',
            'nvaizie@gmail.com',
            'lukas@accume.co.za',
            'jaq.terblanche@gmail.com',
            'glu@connix.co.za',
            'financialmanager@nhcltd.com',
            'kharwaze@telkomsa.net',
            'deon@iqaccounting.co.za',
            'isobel@xsinet.co.za',
            'ivorj@iafrica.com',
            'visserandrea@yahoo.com',
            'angela@fowler.co.za',
            'sarakie@d5.co.za',
            'accho@iafrica.com',
            'benferreira7@gmail.com',
            'heidivan@telkomsa.net',
            'lzlreitz@gmail.com',
            'npelser@mpact.co.za',
            'adminjhb1@ipacc.co.za',
            'mavoureenw@iafrica.com',
            'constance@rdtoyota.co.za',
            'jolandie@noble-acc.co.za',
            'chinique@noble-acc.co.za',
            'elpoolman1@gmail.com',
            'alta.strumpher@gmail.com',
            'pieter.conradie@spar.co.za',
            'johann@wealthgroup.co.za',
            'rui@rcservices.co.za',
            'adriana@rcservices.co.za',
            'morne@rcservices.co.za',
            'morne@ruireservices.co.za',
            'deon@honeybeeapp.com',
            'sheree@pmafrica.co.za',
            'colette@msp.property',
            'Joon.Chong@webberwentzel.com',
            'alisonf@redfordcapital.co.za',
            'elrika@eyecare.co.za',
            'lisafranklinca@gmail.com',
            'louis@prinsen.biz',
            'jaco@prinsen.biz',
            'anton@meerkotter.co.za',
            'thomasm@wetback.co.za',
            'melissaraemurray@gmail.com'
        ];

        $event = \App\AppEvents\Event::with('tickets', 'tickets.pricing')->where('slug', 'compliance-and-legislation-update-december-2017')->first();
        $Tftickets = collect();
        $TfnoTicket = collect();

        foreach ($SAITUsers as $email) {
            $ticket = $event->tickets->where('email', $email)->first();
            if ($ticket) {
                $Tftickets->push($ticket);
            } else {
                $TfnoTicket->push($email);
                continue;
            }
        }

        Excel::create('SAIT Members who attended ' . $event->name, function ($excel) use ($Tftickets, $TfnoTicket) {
            $excel->sheet('Attended', function ($sheet) use ($Tftickets) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Price',
                    'Discount',
                ]);

                foreach ($Tftickets as $ticekt) {
                    $sheet->appendRow([
                        $ticekt->first_name,
                        $ticekt->last_name,
                        $ticekt->email,
                        $ticekt->pricing->price,
                        ($ticekt->invoice) ? $ticekt->invoice->transactions->where('tags', 'Discount')->sum('amount') : '0',
                    ]);
                }
            });

            $excel->sheet('Not Attended', function ($sheet) use ($TfnoTicket) {
                foreach ($TfnoTicket as $ticekt) {
                    $sheet->appendRow([
                        $ticekt
                    ]);
                }
            });
        })->export('xls');

//        // Invoices Test
//        $from = Carbon::parse('1 march 2017')->startOfDay();
//        $to = Carbon::parse('20 march 2018')->endOfDay();
//
//        $invoices = Invoice::whereBetween('created_at', [$from,$to])->get();
//        foreach ($invoices as $invoice){
//            $tempTransactions = collect($invoice->transactions)->filter(function ($transaction) use($from, $to){
//                if ($transaction->date >= $from && $transaction->date <= $to){
//                    return $transaction;
//                }
//            });
//            unset($invoice->transactions);
//            $invoice->transactions = $tempTransactions;
//        }
//
//        $invoices = $invoices->groupBy(function ($invoice){
//            return date_format(Carbon::parse($invoice->created_at), 'F - Y');
//        });
//
//        Excel::create('Invoices between '.date_format($from, 'd F Y').' - '.date_format($to, 'd F Y'), function($excel) use($invoices) {
//            foreach ($invoices as $key => $value){
//                $excel->sheet($key, function($sheet) use($value){
//                    $sheet->appendRow([
//                        'First Name',
//                        'Last Name',
//                        'Email',
//                        'Subscription',
//                        'Reference',
//                        'Invoice Created At',
//                        'Invoice Balance',
//                        'Invoice Type'
//                    ]);
//
//                    foreach ($value as $invoice) {
//                        $sheet->appendRow([
//                            $invoice->user->first_name,
//                            $invoice->user->last_name,
//                            $invoice->user->email,
//                            ($invoice->user->subscribed('cpd')? $invoice->user->subscription('cpd')->plan->name : "None"),
//                            $invoice->reference,
//                            date_format($invoice->created_at, 'd F Y'),
//                            $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount'),
//                            $invoice->type,
//                        ]);
//                    }
//                });
//            }
//        })->export('xls');

//        // Get a list of subscribers for specifc plans and check if they have paid for this month.
//        $subscribers = \App\Subscriptions\Models\Subscription::with('plan', 'user', 'user.invoices')->whereIn('plan_id', [
//            '22',
//            '21',
//            '20',
//            '19',
//            '18',
//            '12',
//            '11',
//            '10',
//            '9',
//            '8',
//            '7',
//            '6',
//            '3',
//            '2',
//            '1',
//        ])->active()->get();
//
//        $invoices = collect();
//        foreach ($subscribers as $package){
//            $package->user->invoices->filter(function ($invoice) use($invoices){
//                if ($invoice->created_at >= Carbon::parse('01 January 2018') && $invoice->created_at <= Carbon::parse('31 January 2018')){
//                    $invoices->push($invoice);
//                }
//            });
//        }

        // Invoices from date to date and has not been paid by X date.
//        $invoices = Invoice::
//        where('status', '!=', 'credit noted')
//        ->where('status', '!=', 'cancelled')
//        ->where('sub_total', '>', '0')
//        ->where('total','>', '0')
//        ->whereBetween('created_at', [Carbon::parse('1 January 2018'), Carbon::parse('31 January 2018')])->get();

//        $end_date = Carbon::parse('31 January 2018')->endOfDay();
//        $paid = collect();
//        $unpaid = collect();
//        $paidIn2018 = collect();
//        $discountedInvoices = collect();
//
//        $invoices->each(function ($invoice) use($end_date, $paid, $unpaid, $paidIn2018, $discountedInvoices){
//            if (is_null($invoice->date_settled) && $invoice->status == 'paid'){
//                $discountedInvoices->push($invoice);
//
//            }elseif (is_null($invoice->date_settled)){
//                    $unpaid->push($invoice);
//            }
//            else{
//                if($invoice->date_settled <= $end_date){
//                    $paid->push($invoice);
//
//                }else{
//                    $unpaid->push($invoice);
//                    if (! is_null($invoice->date_settled)){
//                        $paidIn2018->push($invoice);
//                    }
//                }
//            }
//        });

//        dd($unpaid->count());

//        Excel::create('Paid after 31 December Invoices for 1 January 2017 - 30 October 2017', function($excel) use($discountedInvoices) {
//        $excel->sheet('sheet', function($sheet) use($discountedInvoices){
//            $sheet->appendRow([
//                    'First Name',
//                    'Last Name',
//                    'Email',
//                    'Cell',
//                    'Subscription',
//                    'Reference',
//                    'Balance',
//                    'Status',
//                ]);
//
//            foreach ($discountedInvoices as $invoice) {
//                $sheet->appendRow([
//                    $invoice->user->first_name,
//                    $invoice->user->last_name,
//                    $invoice->user->email,
//                    $invoice->user->cell,
//                    ($invoice->user->subscription('cpd')? $invoice->user->subscription('cpd')->plan->name : "None"),
//                    $invoice->reference,
//                    $invoice->balance,
//                    $invoice->status
//                ]);
//            }
//        });
//    })->export('xls');

//        $data = \App\Subscriptions\Models\Subscription::with('plan', 'user')->get();
//        $subscriptions = $data->filter(function ($subscription){
//            if ($subscription->active() && $subscription->plan->interval == 'year'){
//                if ($subscription->created_at >= '2016-11-01' && $subscription->created_at <= '2016-12-31'){
//                    return $subscription;
//                };
//            }
//        });
//
//        Excel::create('Outstanding invoices for Cyber Monday', function($excel) use($subscriptions) {
//            $excel->sheet('sheet', function($sheet) use ($subscriptions){
//                $sheet->appendRow([
//                    'Name',
//                    'Last Name',
//                    'Email',
//                    'Cell',
//                    'Plan',
//                    'Interval',
//                    'Created_at',
//                    'Starts AT',
//                    'Ends AT',
//                ]);
//                foreach ($subscriptions as $subscription){
//                    $sheet->appendRow([
//                        $subscription->user->first_name,
//                        $subscription->user->last_name,
//                        $subscription->user->email,
//                        $subscription->user->cell,
//                        $subscription->plan->name,
//                        $subscription->plan->interval,
//                        $subscription->created_at,
//                        $subscription->starts_at,
//                        $subscription->ends_at,
//                    ]);
//                }
//            });
//        })->export('xls');

        ////         Grab all the once off subscribers and the value of them, invoiced and paid with unpaid
        $data = \App\Subscriptions\Models\Subscription::all();
        $subscriptions = $data->reject(function ($subscription) {
            if ($subscription->canceled_at != '') {
                return $subscription;
            }
        });
        ////
//        $invoiced = collect();
//        $unpaid = collect();
//        $paid = collect();
//        $cancelled = collect();
//
//        foreach ($subscriptions as $subscription){
//            $invoices = $subscription->user->invoices->where('type', 'subscription');
//            foreach ($invoices as $invoice){
//                if ($invoice->total >= '2950.00' && $invoice->created_at >= Carbon::now()->startOfYear()->startOfDay()){
//                    $invoiced->push($invoice);
//                    if ($invoice->status == 'paid'){
//                        $paid->push($invoice);
//                    }elseif($invoice->status == 'unpaid' && $invoice->cancelled == false){
//                        $unpaid->push($invoice);
//                    }else{
//                        $cancelled->push($invoice);
//                    }
//                }
//            }
//        }
//
        Excel::create('All Subscribers', function ($excel) use ($subscriptions) {
            $excel->sheet('sheet', function ($sheet) use ($subscriptions) {
                $sheet->appendRow([
                    'created_at',
                    'Interval',
                    'Name',
                    'Last Name',
                    'Email',
                    'Cell',
                    'Subscription',
                    'ends at'
                ]);
                foreach ($subscriptions as $subscription) {
                    $sheet->appendRow([
                        $subscription->user->created_at,
                        $subscription->plan->interval,
                        $subscription->user->first_name,
                        $subscription->user->last_name,
                        $subscription->user->email,
                        $subscription->user->cell,
                        ($subscription->user->subscription('cpd') ? $subscription->user->subscription('cpd')->plan->name : 'None'),
                        ($subscription->user->subscription('cpd') ? $subscription->user->subscription('cpd')->ends_at : 'None'),
                    ]);
                }
            });
        })->export('xls');

//    $data = User::has('subscriptions')->get();
//    $users = $data->filter(function ($user){
//        if ($user->subscription('cpd')->active() == true && $user->payment_method == 'debit_order'){
//            return $user;
//        }
//    });
//
//        $invoiced = collect();
//        $unpaid = collect();
//        $paid = collect();
//        $cancelled = collect();

//        foreach ($users as $user){
//            $invoices = $user->invoices->where('type', 'subscription');
//            foreach ($invoices as $invoice){
//                if ($invoice->total < '2950.00' && $invoice->created_at >= Carbon::now()->startOfYear()->startOfDay()){
//                    $invoiced->push($invoice);
//                    if ($invoice->status == 'paid'){
//                        $paid->push($invoice);
//                    }elseif($invoice->status == 'unpaid' && $invoice->cancelled == false){
//                        $unpaid->push($invoice);
//                    }else{
//                        $cancelled->push($invoice);
//                    }
//                }
//            }
//        }
//
//        Excel::create('Total UNPAID for Debit Orders', function($excel) use($unpaid) {
//            $excel->sheet('sheet', function($sheet) use ($unpaid){
//                $sheet->appendRow([
//                    'Invoice Ref',
//                    'Name',
//                    'Email',
//                    'Cell',
//                    'Subscription',
//                    'Amount',
//                    'Balance'
//                ]);
//                foreach ($unpaid as $invoice){
//                    $sheet->appendRow([
//                        $invoice->reference,
//                        $invoice->user->first_name.' '.$invoice->user->last_name,
//                        $invoice->user->email,
//                        $invoice->user->cell,
//                        ($invoice->user->subscription('cpd') ? $invoice->user->subscription('cpd')->plan->name : "None"),
//                        $invoice->transactions->where('type', 'debit')->sum('amount'),
//                        $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount')
//                    ]);
//                }
//            });
//        })->export('xls');

//        Grab all the monthly EFT subscribers and the value of them, invoiced and paid with unpaid
//        $data = \App\Subscriptions\Models\Subscription::wherein('plan_id', ['1', '2', '3', '9', '12', '18', '19', '20', '21'])->get();
//
//        $subscriptions = $data->filter(function ($subscription){
//            if ($subscription->canceled_at == ''){
//                if ($subscription->user->payment_method == 'eft'){
//                    return $subscription;
//                };
//            }
//        });
//
//        $invoiced = collect();
//        $unpaid = collect();
//        $paid = collect();
//        $cancelled = collect();
//
//        foreach ($subscriptions as $subscription){
//            $invoices = $subscription->user->invoices->where('type', 'subscription');
//            foreach ($invoices as $invoice){
//                if ($invoice->total < '2950.00' && $invoice->created_at >= Carbon::now()->startOfYear()->startOfDay()){
//                    $invoiced->push($invoice);
//                    if ($invoice->status == 'paid'){
//                        $paid->push($invoice);
//                    }elseif($invoice->status == 'unpaid' && $invoice->cancelled == false){
//                        $unpaid->push($invoice);
//                    }else{
//                        $cancelled->push($invoice);
//                    }
//                }
//            }
//        }
//
//        Excel::create('Total UNPAIDS for Monthly EFT', function($excel) use($unpaid) {
//            $excel->sheet('sheet', function($sheet) use ($unpaid){
//                $sheet->appendRow([
//                    'Invoice Ref',
//                    'Name',
//                    'Email',
//                    'Cell',
//                    'Subscription',
//                    'Amount',
//                    'Balance'
//                ]);
//                foreach ($unpaid as $invoice){
//                    $sheet->appendRow([
//                        $invoice->reference,
//                        $invoice->user->first_name.' '.$invoice->user->last_name,
//                        $invoice->user->email,
//                        $invoice->user->cell,
//                        ($invoice->user->subscription('cpd') ? $invoice->user->subscription('cpd')->plan->name : "None"),
//                        $invoice->transactions->where('type', 'debit')->sum('amount'),
//                        $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount')
//                    ]);
//                }
//            });
//        })->export('xls');
    }

    public function allocatePayment($invoice, $amount, $description, $method)
    {
        $invoice->transactions()->create([
            'user_id' => auth()->user()->id,
            'invoice_id' => $invoice->id,
            'type' => 'credit',
            'display_type' => 'Payment',
            'status' => 'Closed',
            'category' => $invoice->type,
            'amount' => $amount,
            'ref' => $invoice->reference,
            'method' => $method,
            'description' => $description,
            'tags' => 'Payment',
            'date' => Carbon::now()
        ]);
    }
}
