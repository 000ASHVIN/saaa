<?php

namespace App\Http\Controllers\Admin;

use App\Billing\Invoice;
use App\Note;
use App\Repositories\Subscription\SubscriptionRepository;
use App\Users\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;

class CancelMemberSubscriptionController extends Controller
{
    private $userRepository, $subscriptionRepository;
    public function __construct(UserRepository $userRepository, SubscriptionRepository $subscriptionRepository)
    {
        $this->userRepository = $userRepository;
        $this->subscriptionRepository = $subscriptionRepository;
    }

    public function destroy(Request $request, $user)
    {
        $validator = Validator::make($request->all(), ['description' => 'required']);

        if ($validator->fails()){
            alert()->warning('You need to specify a reason for cancellation', 'Warning');
        }else{
            $user = $this->userRepository->find($user);
            $this->subscriptionRepository->cancelSubscriptionWithInvoices($request, $request->description, $user);
            alert()->success('Subscription has been cancelled', 'Success!');
        }

        return back();
    }
}
