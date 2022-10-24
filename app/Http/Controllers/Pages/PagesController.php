<?php

namespace App\Http\Controllers\Pages;

use App\Profession\Profession;
use App\Store\Listing;
use App\Subscriptions\Models\Plan;
use App\Users\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Blog\Post;
use App\FaqQuestion;
use App\Video;
use App\AppEvents\Event;
use App\ActList;
use App\SupportTicket;
use App\Donation;
use Illuminate\Support\Facades\Mail;
use App\Repositories\ThreeDsRepository\ThreeDsRepository;

use App\SponsorList;
use App\Repositories\Donation\DonationRepository;
use App\Peach;
use App\Card;

class PagesController extends Controller
{
    protected $threeDsRepository;
    protected $donationRepository;
    private $peach;
    public function __construct(ThreeDsRepository $threeDsRepository, DonationRepository $donationRepository, Peach $peach)
    {
        $this->threeDsRepository = $threeDsRepository;
        $this->donationRepository = $donationRepository;
        $this->peach = $peach;
    }

    public function home(Request $request)
    { 
        if (strtolower($request->method()) == 'post') {

            $search = collect();
            $title = $request['search'];
            $validator = \Validator::make($request->all(), ['search' => 'required']);
            if ($validator->fails()){
                alert()->error('The search field is required when searching..', 'Error');
                return redirect()->route('home');
            }

            /*
            * Posts
            */
            $articles = Post::whereRaw(1);
            $articles = $articles->search($request['search'], null, true)
                        ->orderByRaw('relevance desc,id desc');
            $articlesCount = $articles->count();
            $articles = $articles->limit(5)->get();
            
            foreach($articles as $article) {
                $article->search_type = 'articles';
                $article->search_date = $article->created_at->format('Y-m-d');
                $search->push($article);
            }

            /*
            * FAQ
            */
            $faqs = FaqQuestion::whereRaw(1);
            $faqs = $faqs->search($request['search'], null, true)->orderByRaw('relevance desc,id desc')
                    ->with('tags')->whereHas('tags', function($query){
                        $query->where('type', 'technical');
                    });
                    $faqsCount = $faqs->count();
                    $faqs = $faqs->limit(5)->get();

            foreach($faqs as $faq) {
                $faq->search_type = 'faqs';
                $faq->search_date = $faq->created_at->format('Y-m-d');
                $search->push($faq);
            }

            /*
            * Webinars on Demand
            */
            $webinars = Video::whereRaw(1);
            $webinars = $webinars->search($request['search'], null, true)->where('tag', 'studio')
                        ->orderByRaw('relevance desc,id desc');
                        $webinarsCount = $webinars->count();
                        $webinars = $webinars->limit(5)->get();
            foreach($webinars as $webinar) {
                $webinar->search_type = 'webinars';
                $webinar->search_date = $webinar->created_at->format('Y-m-d');
                $search->push($webinar);
            }

            /*
            * Events
            */
            $events = Event::whereRaw(1);
            $events = $events->search($request['search'], null, true)->has('pricings')
                    ->orderByRaw('relevance DESC,events.start_date desc');
                    $eventsCount = $events->count();
                    $events = $events->limit(5)->get();
            foreach($events as $event) {
                $event->search_type = 'events';
                $event->search_date = $event->start_date->format('Y-m-d');
                $search->push($event);
            }

            /*
            * Acts
            */
            $acts = ActList::whereRaw(1);
            $acts = $acts->search($request['search'], null, true)
                    ->orderByRaw('relevance DESC,id desc');
                    $actsCount = $acts->count();
                    $acts = $acts->limit(5)->get();
            foreach($acts as $act) {
                $act->search_type = 'acts';
                $act->search_date = $act->created_at->format('Y-m-d');
                $search->push($act);
            }
            
            /*
            * Threads
            */
            $tickets = SupportTicket::whereRaw(1);
            $tickets = $tickets->search($request['search'], null, true)->orderByRaw('relevance desc,id desc')->has('thread')->get()->filter(function ($ticket){
                if ($ticket->thread->open_to_public == true){
                    return $ticket;
                }
            });
            $ticketsCount = $tickets->count();
            $tickets = $tickets->slice(0, 5);
            
            foreach($tickets as $ticket) {
                $ticket->search_type = 'tickets';
                $ticket->search_date = $ticket->created_at->format('Y-m-d');
                $search->push($ticket);
            }


            // Sort
            $allRecords = collect();
            // $search = $search->sortByDesc('relevance');
            $search = $search->sortByDesc('created_at');
                    foreach($search as $item) {
                        $allRecords->push($item);
                        // $allRecords = $allRecords->slice(0, 5);
                    }
    }

        $users = User::where('deleted_at', null)->count();
        $listings = Listing::with(['products'])->orderBy('title')->get();
        $professions = Profession::where('is_active', true)->get();
        
        $premium_sponsers = SponsorList::where('is_active', true)->where('is_premium_partner',1)->get();
        $normal_sponsers = SponsorList::where('is_active', true)->where('is_premium_partner',0)->get();
        return view('pages.home', compact('title','articles','articlesCount', 'faqs','faqsCount', 'events','eventsCount', 'webinars','webinarsCount', 'tickets','ticketsCount', 'acts', 'actsCount','allRecords','listings', 'users', 'professions', 'premium_sponsers', 'normal_sponsers'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function wod()
    {
        return view('pages.wod');
    }

    public function donate()
    {
        if (request()->has('threeDs') && auth()->user()) {
            $this->threeDsRepository->handleThreeDs(request());
        }
        
        if(request()->has('threeDs') && request()->has('donation')) {
            $donate = Donation::find(request()->input('donation'));
            if($donate) {

                $payment = $this->threeDsRepository->handleThreeDsPayment(request());
                if($payment->successful()) {
                    $transaction_id = $payment->id;
                    $donate->status = '1';
                    $donate->transaction_id = $transaction_id;
                    $donate->save();

                    $this->donationRepository->notifyUser($donate);
                    $this->donationRepository->notifyStaff($donate);

                    alert()->success('Thank you for Funding a learner.', 'Success');
                    return redirect()->route('donate');

                }
                else {
                    $donate->failure_reason = $payment->result['description'];
                    $donate->save();
                    
                    $this->donationRepository->notifyStaff($donate);
                }
            }

            alert()->error('Something went wrong.', 'Error');
            return redirect()->route('donate');
        }
        
        
        return view('pages.donate');
    }
    public function donateSave(Request $request)
    {
        $payment_status = false;
        $transaction_id = false;
        $payment_error = 'Error in payment.';
        $donate = null;
        
        $user = auth()->user();
        $charge = $request->amount;
        
        if($request->paymentOption == 'eft' && $request->paymentStatus == 'success') {
            $payment_status = true;
            $transaction_id = $request->transactionId;
        }
        else if($request->paymentOption == 'cc') {

            if($user) {
                $card = Card::find($request->card);
                if($card && $card->user_id == $user->id) {

                    $payment = $this->peach->charge(
                        $card->token,
                        $charge,
                        "Donation - " . time(),
                        "Donation - " . time()
                    );
                    if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {
                        $payment_status = true;
                        $transaction_id = $payment['id'];
                    }
                    else {
                        $payment_error = $payment['result']['description'];
                    }
                }
            }
            else {

                // Create donation entry
                $data = $request->only('first_name', 'last_name', 'email', 'company_name', 'cell', 'paymentOption', 'address', 'amount');
                $data['status'] = '0';
                $donate = Donation::create($data);

                $newcard = $request->newcard;
                if($newcard && is_array($newcard)) {

                    $payment = $this->peach->autorizeandFetch(
                        $newcard['number'],
                        $newcard['holder'],
                        $newcard['exp_month'],
                        $newcard['exp_year'],
                        $newcard['cvv'],
                        time() . '- Authorization',
                        '/donate?threeDs=yes&donation='.$donate->id,
                        $charge
                    );
                    
                    if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {
                        $payment_status = true;
                        $transaction_id = $payment['id'];
                    }
                    else {

                        if(isset($payment['redirect']['url'])) {

                            return response()->json([
                                'status' => false,
                                'message' => 'ThreeDs',
                                'data' => $payment,
                                'donation' => $donate->id
                            ], 200);                
                        }
                        else {
                            $payment_error = $payment['result']['description'];
                        }

                    }

                }

            }

        }

        if(!$payment_status) {

            if($donate) {
                $donate->failure_reason = $payment_error;
            }

            return response()->json([
                'status' => false,
                'message' => $payment_error
            ], 200);
        }

        try{
            if(!$donate)  {
                $data = $request->all();
                $donate = Donation::create($request->only('first_name', 'last_name', 'email', 'company_name', 'cell', 'paymentOption', 'address', 'amount'));
            }

            if($transaction_id) {
                $donate->transaction_id = $transaction_id;
            }

            $donate->status = '1';
            $donate->save();

            $this->donationRepository->notifyUser($donate);
            $this->donationRepository->notifyStaff($donate);

            return response()->json(['status' => true, 'message' => 'Thank you for Funding a learner.'], 200);
        }catch(\Exception $e){
            \Log::info('Exception when donating from donor page.');
            \Log::info($e);
            return response()->json(['status' => false, 'message' => 'Something went wrong'], 404);
        }
    }
    
    public function bf(Request $request)
    {
        if(request()->has('threeDs'))
            $this->threeDsRepository->handleThreeDs(request());

        $plans = Plan::where('inactive', '==', false)
            ->where('interval', 'year')
            ->where('price', '!=', 0)
            ->where('enable_bf', true)
            ->with('professions')
            ->get();

        $plans->each(function ($plan){
            $new = $plan->features->reject(function ($feature){
                if ($feature->selectable == false){
                    return $feature;
                }
            });

            unset($plan->features);
            $plan->features = $new;
        });

        $selectedPlan = null;
        if($request->has('subscription')) {
            $selectedPlan = Plan::find($request->subscription);
        }

        return view('subscriptions.2017.index', compact('plans', 'selectedPlan'));
    }

    public function draftWorx()
    {
        return view('black_friday.index');
    }
}
