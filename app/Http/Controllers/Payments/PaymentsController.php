<?php

namespace App\Http\Controllers\Payments;

use Carbon\Carbon;
use App\Http\Requests;
use App\Subscriptions\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\ThreeDSecureCompleted;
use App\Services\Billing\Mygate\Mygate;

class PaymentsController extends Controller
{
	protected $mygate;

	public function __construct(Mygate $mygate)
	{
		$this->mygate = $mygate;
	}

    public function afterCheckThreeDs(Request $request)
    {
        $data = $request->all();

        $PaRes = $data['PaRes'];
        $MD = $data['MD'];
        $time = Carbon::now();

        event(new ThreeDSecureCompleted($MD, $PaRes, $time));

        return view('payments.three_ds_complete');
    }

    public function checkThreeDs(Request $request)
    {
    	$cc = $request->ccNo;
    	$expYear = $request->expYear;
    	$expMonth = $request->expMonth;
        $amount = $request->amount;
        $orderId = $request->orderId;
        $reference = $request->reference; 

    	return $this->mygate->checkThreeDs(
    		$cc,
    		$expYear,
    		$expMonth, 
    		$amount,
    		$orderId,
    		$reference
		);
    }
}
