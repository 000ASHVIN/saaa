<?php

namespace App\Listeners;

use App\DebitOrder;
use App\Jobs\NotifyClientOfInvalidDebitOrderDetails;
use App\Jobs\SendRenewableInvoice;
use App\Jobs\SendRenewableNotificationToUserWithCreditCard;
use App\Note;
use App\Peach;
use App\Billing\InvoiceRepository;
use App\Events\SubscriptionRenewed;
use App\Repositories\CreditMemo\CreditMemoRepository;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Repositories\WalletRepository\WalletRepository;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use WebDriver\Exception;
use App\Events\CourseSubscibed;

class CourseSubscribedListeners
{
    use DispatchesJobs;
 
    /**
     * Handle the event.
     *
     * @param  CourseSubscibed $event
     * @return \Exception|Exception
     */
    public function handle(CourseSubscibed $event)
    {
        
        try{
            Mail::send('emails.course_subscription', ['subscription' => $event->subscription], function ($m) use ($event) {
                    $m->from(env('APP_EMAIL'), config('app.name'));
                    $m->to(env('COURSE_EMAIL'), 'System Administrator')->subject('New Course Enrollment');
            });
            return true;
        }catch(\Exception $e){
            return true;
        }
    }

  
}
