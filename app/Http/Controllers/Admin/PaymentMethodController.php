<?php

namespace App\Http\Controllers\Admin;

use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PaymentMethodController extends Controller
{

    public function index()
    {
        $eft = $this->eft();
        $debit_orders = $this->debit_orders();
        $credit_cards = $this->credit_cards();

        $users = User::with('subscriptions')->get();
        $subscriptions = $users->reject(function ($user){
            if (! $user->subscribed('cpd')){
                return $user;
            };
        })->where('payment_method', null);

        $plans = Plan::where('inactive', false)->get();
        return view('admin.stats.payment_methods', compact('subscriptions','debit_orders', 'credit_cards', 'eft', 'plans'));
    }

    public function show($payment)
    {
        $users = User::with('subscriptions')->where('payment_method', $payment)->get();
        $users = $users->reject(function ($user){
           if (! $user->subscribed('cpd')){
               return $user;
           };
        });

        if (count($users) == 0){
            alert()->error('No active CPD subscriptions found on debit order', 'Error')->persistent('close');
            return redirect()->back();
        }

        return view('admin.stats.show_payment_subscribers', compact('users'));
    }

    public function export($payment)
    {
        $subscriptions = Subscription::with('user')->active()->where('payment_method', $payment)->get();

        $subscriptions = $subscriptions->each(function ($subscription){
        array_dot($subscription->toArray());
           array_except($subscription,
               [
                   'created_at',
                   'updated_at',
                   'canceled_at',
                   'sms_confirmed',
                   'email_confirmed',
                   'voice_recording',
                   'trial_ends_at'
               ]);

           return $subscription;
        });

        return Excel::create($subscriptions->first()->payment_mthod.' '.'subscriptions', function($excel) use($subscriptions) {
            $excel->sheet('Exports', function($sheet) use($subscriptions) {
                foreach ($subscriptions as $subscription){
                    $subscription = $subscription->toArray();
                    array_forget($subscription, ['user.id', 'user.deleted_at', 'user.created_at', 'user.updated_at', 'user.avatar', 'user.password_is_temporary', 'user.temp_password', 'user.deleted_at_description', 'user.cpd_with_sait']);
                    $array = array_except(array_dot($subscription), ['user_id', 'plan_id', 'starts_at', 'ends_at', 'suspended']);
                    $sheet->appendRow(array_except(array_dot($array), ['user_id', 'plan_id', 'starts_at', 'ends_at']));
                }
            });

        })->export('xls');
    }

    public function debit_orders()
    {
        $debits = User::with('subscriptions')->where('payment_method', 'debit_order')->get();
        return $debits;
    }

    public function credit_cards()
    {
        $credit_cards = User::with('subscriptions')->where('payment_method', 'credit_card')->get();
        return $credit_cards;
    }

    public function eft()
    {
        $eft = User::with('subscriptions')->where('payment_method', 'eft')->get();
        return $eft;
    }

    public function exportPlan(Request $request)
    {
        $plan = Plan::find($request->plan);
        $subscriptions = Subscription::with('user')->active()->where('plan_id', $plan->id)->where('payment_method', null)->get();

        return Excel::create($subscriptions->first()->plan->name.' '.'subscriptions', function($excel) use($subscriptions) {
            $excel->sheet('Exports', function($sheet) use($subscriptions) {
                $sheet->appendRow([
                    'Full Name',
                    'Email',
                    'Cell',
                    'Subscription',
                    'Payment Method'
                ]);

                $subscriptions->each(function ($subscription) use($sheet){
                    $sheet->appendRow([
                        $subscription->user->first_name.' '.$subscription->user->last_name,
                        $subscription->user->email,
                        $subscription->user->cell,
                        $subscription->plan->name,
                        ($subscription->payment_method ? : "None")
                    ]);

                });
            });

        })->export('xls');

    }
}
