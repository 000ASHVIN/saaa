<?php

namespace App;

use App\Users\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DebitOrder
 * @package App
 */
class DebitOrder extends Model
{
	/**
	 * Which fields may be mass assigned
	 * @var array
     */
	protected $guarded = [];

	/**
	 * @var array
     */
	protected $dates = ['last_debit', 'next_debit_date'];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function toCustomArray() {
        return [
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'branch_code' => (int) $this->branch_code,
            'account_number' => (int) $this->number,
            'amount' => (int) $this->getSubscriptionPrice(),
            'reference' => "DEBIT{$this->id}IN{$this->getSubscriptionInvoiceId()}"
        ];
    }
    public function toCustomArrayCourse($course) {
        return [
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'branch_code' => (int) $this->branch_code,
            'account_number' => (int) $this->number,
            'amount' => (float) $course->plan->price,
            'reference' => "DEBIT{$this->id}IN{$course->fresh()->invoice_id}-DEBITCOURSE{$course->id}"
        ];
    }
    public function getInvoiceandCourse($debit)
    {
        try{
            $refrence = explode("-",$debit);
            $debit_id = substr($debit, 5);
            
            $invoice_id= 0;
            $Inv = explode("IN",$debit_id);
           
            if(count($Inv)==2){
                $debit_id = $Inv[0];
                $invoice_id = $Inv[1];
            }
            if(count($refrence) == 2){
                $Invc = explode("IN",$refrence[0]);
                $debit_id = (int) substr($refrence[0], 5);
                if(count($Invc)==2){
                    $debit_id = (int) substr($Invc[0], 5);
                    $invoice_id = $Invc[1];
                }
            }
            $invoice = [];
            $invoice['invoice_id'] = (int) $invoice_id;
            $invoice['debit_id'] = (int) $debit_id;
            return $invoice;
        }catch(\Exception $e){
            $invoice = [];
            return $invoice;
        }
    }

    public function getSubscriptionPrice()
    {
        $price = 0;
        $subscription = $this->user->subscription('cpd');
        if($this->user->isPracticePlan()){
            $activeStaff = $this->user->isPracticePlan();
            $subscription->plan->getPlanPrice($activeStaff) ;
            $pricingGroup = @$subscription->getChildSubscriptions[0];
            $price=($subscription->plan->price)*$activeStaff;
            if($pricingGroup && $pricingGroup->pricings){
                // $price=($pricingGroup->pricings->price)*$activeStaff;
                $request = request();
                $request->merge(['staff'=>$activeStaff]);
            }
            
        }else{
            $price = $this->user->subscription('cpd')->plan->price;
        } 
        return $price;       
    }
    public function setLastDebitAttribute($date)
    {
        return $this->attributes['last_debit'] = Carbon::parse($date);
    }

    public function getSubscriptionAndBillableDate()
    {
        if ($this['billable_date'] == 1){
            $date = 2;
        }elseif ($this['billable_date'] >= 29){
            $date = 2;
        }else{
            $date = $this['billable_date'];
        }
        return $date;
    }

    public function getSubscriptionInvoiceId()
    {
        if ($this->user->subscription('cpd')){
            return $this->user->subscription('cpd')->invoice_id;
        }else{
            return $this->user->invoices->where('type', 'subscription')->last()->id;
        }
    }
}
