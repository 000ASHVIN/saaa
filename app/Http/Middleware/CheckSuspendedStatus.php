<?php

namespace App\Http\Middleware;
use App\Events\TriggerSuspensionNotice;
use App\Users\UserRepository;
use Closure;
use Carbon\Carbon;

class CheckSuspendedStatus
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * CheckSuspendedStatus constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle($request, Closure $next)
    {
         $user = auth()->user();
         $overdue_invoice = $user->overdueInvoices()->where('type', 'subscription')->last();

         /* Check if the user has an outstanding invoice */
         if ($overdue_invoice) {
             /* If the user has a debt arragement Ignore the below. */
             if ($user->debt_arrangement != true) {

                 /* If user has been force suspended, Suspend his account immediate without proceeding with code below */
                 if ($user->force_suspend == false) {

                     if ($overdue_invoice) {
                         if ($user->subscribed('cpd') && $user->payment_method == 'eft') {
                             /* If the user has an Overdue Invoice (30 days from created at "still unpaid"), Let's suspend his account. */
                             if (Carbon::parse($overdue_invoice->created_at)->addDays(15) <= Carbon::now() &&
                                 $overdue_invoice->status === 'unpaid' || $overdue_invoice->status === 'partial'
                             ) {
                                 /* User has overdue Invoices, Let's suspend his account. */
                                 $this->userRepository->suspend($user);
                                 return view('dashboard.suspended', compact('user'));

                             } elseif ($user->status == 'suspended') {
                                 /* User does not have overdue Invoices, Let's un-suspend his account. */
                                 $this->userRepository->unsuspend($user);
                                 return $next($request);
                             }
                         } else {
                             if ($overdue_invoice->created_at->addDays(30) <= Carbon::now() &&
                                 $overdue_invoice->status === 'unpaid' || $overdue_invoice->status === 'partial'
                             ) {
                                 /* User has overdue Invoices, Let's suspend his account. */
                                 $this->userRepository->suspend($user);
                                 return view('dashboard.suspended', compact('user'));

                             } elseif ($user->status == 'suspended') {
                                 /* User does not have overdue Invoices, Let's un-suspend his account. */
                                 $this->userRepository->unsuspend($user);
                                 return $next($request);
                             }
                         }
                     } else {
                         $this->userRepository->unsuspend($user);
                         return $next($request);

                     }
                 } else {
                     $this->userRepository->suspend($user);
                     return view('dashboard.suspended', compact('user'));
                 }
             } else {
                 return $next($request);
             }
         }else{
             if ($user->status == 'suspended'){
                 $this->userRepository->unsuspend($user);
             }
             return $next($request);
         }
    }
}
