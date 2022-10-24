<?php

namespace App\Http\Controllers;

use App\Card;
use App\Http\Requests\CompleteCheckoutRequest;
use App\InvoiceOrder;
use App\Jobs\SendEventTicketInvoiceJob;
use App\Jobs\SendEventTicketOrderJob;
use App\Note;
use App\Peach;
use App\Repositories\InvoiceOrder\InvoiceOrderRepository;
use App\Users\User;
use Carbon\Carbon;
use App\Store\Cart;
use App\Http\Requests;
use App\Users\Address;
use App\Billing\Invoice;
use App\Mailers\UserMailer;
use Illuminate\Http\Request;
use App\Store\ShippingMethod;
use App\Http\Controllers\Controller;
use App\Billing\CreditCardBillingRepository;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Repositories\SendEmailRepository\SendEmailRepository;
use App\Repositories\WalletRepository\WalletRepository;
use Illuminate\Support\Facades\Session;
use App\Video;

class StoreCheckoutController extends Controller
{
    private $creditCardBillingRepository, $peach, $sendInvoiceRepository, $walletRepository, $sendEmailRepository;
    /**
     * @var InvoiceOrderRepository
     */
    private $invoiceOrderRepository;

    public function __construct(
        CreditCardBillingRepository $creditCardBillingRepository,
        Peach $peach,
        SendInvoiceRepository $sendInvoiceRepository,
        WalletRepository $walletRepository,
        SendEmailRepository $sendEmailRepository,
        InvoiceOrderRepository $invoiceOrderRepository
        )
    {
        $this->creditCardBillingRepository = $creditCardBillingRepository;
        $this->peach = $peach;
        $this->sendInvoiceRepository = $sendInvoiceRepository;
        $this->walletRepository = $walletRepository;
        $this->sendEmailRepository = $sendEmailRepository;
        $this->invoiceOrderRepository = $invoiceOrderRepository;
    }
    
    public function getCheckout(Request $request)
    {
        if(request()->has('threeDs'))
            $this->handleThreeDs(request());

        if (auth()->user()->status != 'active'){
            $this->checkSuspendedStatus(auth()->user());
            return back();
        }

        $cartItems = Cart::getAllCartProductListings();
        if (!$cartItems || count($cartItems) < 1) {
            alert()->error('You need to add something to your cart first.');
            return redirect()->route('store.index');
        }

        $cartHasPhysicalProduct = Cart::hasPhysicalProduct();
        $cartTotalQuantity = Cart::getTotalQuantity();
        $cartTotalDiscountedPrice = Cart::getTotalDiscountedPrice();
        $addresses = auth()->user()->addresses;
        $shippingMethods = ShippingMethod::all();
        return view('store.checkout', compact('cartItems', 'cartHasPhysicalProduct', 'cartTotalQuantity', 'cartTotalDiscountedPrice', 'addresses', 'shippingMethods'));
    }


    public function postCheckout(CompleteCheckoutRequest $request, UserMailer $mailer)
    {
        $user = auth()->user();
        $donations = $request->donations?$request->donations:null;
        try{
            if (Cart::hasPhysicalProduct()) {
                $shippingMethod = ShippingMethod::findOrFail($request->shippingMethodId);
                $deliveryAddress = Address::findOrFail($request->deliveryAddressId);
                $InvoiceOrder = InvoiceOrder::createFromCart($user, $shippingMethod, $donations);
                Cart::assignAllProductListingsToUser($user, $InvoiceOrder, $deliveryAddress, $shippingMethod);
                $this->saveSalesNote($InvoiceOrder);
            } else {
                $InvoiceOrder = InvoiceOrder::createFromCart($user, null, $donations);
                Cart::assignAllProductListingsToUser($user, $InvoiceOrder);
                $this->saveSalesNote($InvoiceOrder);
            }

            if ($request->paymentOption == 'po'){
                $this->saveSalesNote($InvoiceOrder);
                $this->dispatch((new SendEventTicketOrderJob($InvoiceOrder->fresh())));
                $this->assignWebinarOnDemand();
                Cart::clear();
            }
            
            if ($request->paymentOption == 'eft') {
                $this->saveSalesNote($InvoiceOrder);
                $invoice = $this->invoiceOrderRepository->processCharge($InvoiceOrder->fresh(), 'instant_eft', 'Instant EFT Payment');
                $this->dispatch((new SendEventTicketOrderJob($InvoiceOrder->fresh())));
                $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
                $this->assignWebinarOnDemand();
                Cart::clear();
                $invoice = $invoice->fresh();
                $items = $invoice->items;
                return response()->json(['message' => 'success', 'invoice' => $invoice], 200);
            }

            if ($request->paymentOption == 'cc') {
                $card = Card::find($request->card);
                $payment = $this->peach->charge(
                    $card->token,
                    $InvoiceOrder->total - $InvoiceOrder->discount,
                    'Order #' . $InvoiceOrder->reference,
                    $InvoiceOrder->reference
                );

                if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {
                    $invoice = $this->invoiceOrderRepository->processCharge($InvoiceOrder->fresh(), 'cc', 'Credit Card Payment');
                    $this->dispatch((new SendEventTicketOrderJob($InvoiceOrder->fresh())));
                    $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
                    $this->assignWebinarOnDemand();
                    Cart::clear();
                    $invoice = $invoice->fresh();
                    $items = $invoice->items;
                    return response()->json(['message' => 'success', 'invoice' => $invoice], 200);
                } else {
                    return response()->json([
                        'errors' => [
                            'card' => $payment['result']['description']
                        ]
                    ], 422);
                }
            }

            if ($request->paymentOption == 'wallet') {
                $this->saveSalesNote($InvoiceOrder);
                $this->walletRepository->payInvoice(auth()->user()->id, $InvoiceOrder->fresh()->id);
                $this->invoiceOrderRepository->sendInvoiceOrder(auth()->user(), $InvoiceOrder->fresh());
                $this->assignWebinarOnDemand();
                Cart::clear();
                return response()->json(['message' => 'success'], 200);
            }

            if ($request->paymentOption == null){
                $this->saveSalesNote($InvoiceOrder);
                $invoice = $this->invoiceOrderRepository->processCharge($InvoiceOrder->fresh(), 'Applied', 'Discount');
                $this->dispatch((new SendEventTicketOrderJob($InvoiceOrder->fresh())));
                $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
                $this->assignWebinarOnDemand();
            }

            $this->saveSalesNote($InvoiceOrder);

            Cart::clear();
            $this->sendEmailRepository->sendEmail($user, 'new_order', 'Online Store Purchase', 'store');
            if ($InvoiceOrder->fresh()->paid == true){
                $InvoiceOrder->releasePendingOrders();
                $InvoiceOrder->assignCpdStoreItem();
            }
        }catch (\Exception $exception){
            return $exception;
        }
        return "Done";
    }

    protected function assignWebinarOnDemand(){

        $cartItem = Cart::getAllStorageItems();
        $user = auth()->user();
        foreach($cartItem as $cart){
            if($cart->model == 'App\Video')
            {
                $video = Video::find($cart->id);
        
                // Allocate webinars to user
                $allVideos = collect();
                if($video->type=='series') {

                    // User's owned webinars
                    $owned_webinars = [];
                    $owned_webinars = $user->webinars->pluck('id')->toArray();

                    foreach($video->webinars as $value) {
                        if(!in_array($value->id,$owned_webinars)) {
                            $allVideos->push($value);
                        }
                    }
    
                    $webinars = $allVideos->pluck('id')->toArray();
                    $webinars[] = $video->id;
                    $user->webinars()->attach($webinars);
                }
                else {
                    $allVideos->push($video);
                    $user->webinars()->save($video);
                }
    
                // Assign cpd hours and certificate for all videos
                // foreach($allVideos as $v) {
                //     $cpd = $this->assignCPDHours($user, $v->hours, 'Webinars On Demand - '.$v->title, null, $v->category, false);
                //     $this->assignCertificate($cpd, $v);
                // }
            }
        }
    }
    public function assignCPDHours($user, $hours, $source, $attachment, $category, $verifiable)
    {
        $cpd = $user->cpds()->create([
            'date' => Carbon::now(),
            'hours' => $hours,
            'source' => $source,
            'attachment' => $attachment,
            'category' => $category,
            'verifiable' => $verifiable,
        ]);
        return $cpd;
    }
    public function assignCertificate($cpd, $video)
    {
        $video = Video::where('title', 'LIKE', '%'.substr($cpd->source, 28).'%')->get()->first();
        if ($video){
            $cpd->certificate()->create([
                'source_model' => Video::class,
                'source_id' => $video->id,
                'source_with' => [],
                'view_path' => 'certificates.wob'
            ]); 
        }
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

    public function allocatePayment($invoice, $amount, $description, $method)
    {
        // Create Transaction
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
            'date' => Carbon::now()
        ]);
    }

    public function checkSuspendedStatus($user)
    {
        alert()->info(
            'Please check your account outstanding balance before trying to checkout your products. <hr> 
            <a href="/dashboard/statement" style="font-weight: bold; color: red;">View Account Statement</a>
            ', 'Account ' . $user->status
        )->persistent('close');
    }

    public function sendInvoiceViaEmail($user, $invoice)
    {
        $this->sendInvoiceRepository->sendInvoice($user, $invoice->fresh());
    }

    public function saveSalesNote($InvoiceOrder)
    {
        if(key_exists('orig_user', Session::all())){
            $agent = User::find(Session::get('orig_user'));

            $note = new Note([
                'type' => 'store_items',
                'description' => "Store Item purchased for ".$InvoiceOrder->user->first_name." ".$InvoiceOrder->user->last_name,
                'logged_by' => $agent->first_name .' '.$agent->last_name,
            ]);

            $note->save();
            $InvoiceOrder->update(['note_id' => $note->id]);
            $InvoiceOrder->user->notes()->save($note);
        }
    }
}
