<?php

namespace App\Repositories\DebitOrder;

use App\DebitOrder;
use App\InvalidDebitOrderDetail;
use App\Note;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Created by PhpStorm.
 * User: Tiaan Theunissen
 * Date: 1/9/2017
 * Time: 8:56 PM
 */
class DebitOrderRepository
{
    private $debitOrder;

    public function __construct(DebitOrder $debitOrder)
    {
        $this->debitOrder = $debitOrder;
    }

    public function retreiveAllDebitOrders()
    {
        $data = $this->debitOrder->orderBy('has_been_contacted')->with('user')->get();
        return $debit_orders = $data->filter(function ($debit_order) {
            if ($debit_order->user && $debit_order->user->payment_method == 'debit_order' && $debit_order->user->subscribed('cpd') && $debit_order->user->subscription('cpd')->plan->interval != 'year') {
                return $debit_order;
            }
        });
    }

    public function retreiveDebitOrders()
    {
        return $this->debitOrder->with('user', 'user.subscriptions', 'user.invoices')->get();
    }

    public function findDebitOrder($id)
    {
        return $this->debitOrder->find($id);
    }

    public function createDebitOrder(Request $request, $user)
    {
        DB::transaction(function () use ($user, $request) {
            $user->debit()->save(new DebitOrder([
                'bank' => $request->bank,
                'number' => $request->number,
                'type' => $request->type,
                'branch_name' => $request->branch_name,
                'branch_code' => $request->branch_code,
                'billable_date' => $request->billable_date,
                'id_number' => $request->id_number,
                'registration_number' => ($request->registration_number ? $request->registration_number : ($user->debit ? $user->debit->registration_number : '')),
                'account_holder' => $request->account_holder,
                'type_of_account' => $request->type_of_account,
                'otp' => $request->otp,
                'peach' => true,
            ]));

            $this->checkSubscriptionAndInvoiceStatus($user);
        });

        $this->sendNewCreatedDebitOrderDetailsEmail($user);
    }

    public function updateDebitOrder(Request $request, $user)
    {
        if ($user->debit->active) {
            $user->debit()->update([
                'bank' => $request->bank,
                'number' => $request->number,
                'type' => $request->type,
                'branch_name' => $request->branch_name,
                'branch_code' => $request->branch_code,
                'billable_date' => $request->billable_date,
                'id_number' => $request->id_number,
                'registration_number' => ($request->registration_number ? $request->registration_number : $user->debit->registration_number),
                'account_holder' => $request->account_holder,
                'type_of_account' => $request->type_of_account,
                'otp' => $request->otp,
            ]);
        } else {
            $user->debit()->update([
                'bank' => $request->bank,
                'number' => $request->number,
                'type' => $request->type,
                'branch_name' => $request->branch_name,
                'branch_code' => $request->branch_code,
                'billable_date' => $request->billable_date,
                'id_number' => $request->id_number,
                'registration_number' => ($request->registration_number ? $request->registration_number : $user->debit->registration_number),
                'account_holder' => $request->account_holder,
                'type_of_account' => $request->type_of_account,
                'otp' => $request->otp,
                'active' => true,
                'peach' => true,
                'bill_at_next_available_date' => true,
                'next_debit_date' => Carbon::now()->addDays(1)->startOfDay()
            ]);

            $debit = InvalidDebitOrderDetail::where('user_id', $user->id)->get();
            foreach ($debit as $invalid) {
                $invalid->delete();
            }
        }

        $this->sendUpdatedBillingInformationEmail($user);
    }

    public function sendUpdatedBillingInformationEmail($user)
    {
        $user = $user->fresh();
        Mail::send('emails.billing.update', ['user' => $user], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.email'), 'Admin')->subject('Updated debit order details');
        });
    }

    public function sendNewCreatedDebitOrderDetailsEmail($user)
    {
        $user = $user->fresh();
        Mail::send('emails.billing.new', ['user' => $user], function ($m) use ($user) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.email'), 'Admin')->subject('New debit order details');
        });
    }

    public function removeDebitOrder($id)
    {
        $do = $this->findDebitOrder($id);
        $do->delete();

        $note = new Note([
            'type' => 'general',
            'logged_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'description' => 'D/O details has been removed from the system'
        ]);

        $do->user->notes()->save($note);
    }

    public function searchDebitOrders($data)
    {
        return $this->debitOrder->where(function ($query) use ($data) {
            if ($data['number']) {
                $query->where('number', 'like', '%' . $data['number'] . '%');
            }
            if ($data['status']) {
                $query->where('active', $data['status']);
            }
            if ($data['debit_date']) {
                $query->where('billable_date', $data['debit_date']);
            }
            if ($data['branch']) {
                $query->where('branch_code', $data['branch']);
            }
            if ($data['provider']) {
                if ($data['provider'] == 'peach') {
                    $query->where('peach', true);
                } else {
                    $query->where('peach', false);
                }
            }
            if ($data['account_holder']) {
                $query->where('account_holder', 'LIKE', '%' . $data['account_holder'] . '%');
            }
        })->with(['user', 'user.subscriptions'])->paginate(10);
    }

    public function export_searchDebitOrders($data)
    {
        return $this->debitOrder->where(function ($query) use ($data) {
            if ($data->number) {
                $query->where('number', 'like', '%' . $data->number . '%');
            }
            if ($data->status) {
                $query->where('active', $data->status);
            }
            if ($data->debit_date) {
                $query->where('billable_date', $data->debit_date);
            }
            if ($data->branch) {
                $query->where('branch_code', $data->branch);
            }
            if ($data->provider) {
                if ($data->provider == 'peach') {
                    $query->where('peach', true);
                } else {
                    $query->where('peach', false);
                }
            }
            if ($data->account_holder) {
                $query->where('account_holder', 'LIKE', '%' . $data->account_holder . '%');
            }
        })->with(['user'])->get();
    }

    public function checkSubscriptionAndInvoiceStatus($user)
    {
        if ($user->subscribed('cpd') && $user->subscription('cpd')->plan->interval == 'month' && $user->subscription('cpd')->plan->price != '0' && $user->subscription('cpd')->active()) {
            if ($user->wasInvoiceForSubscriptionThisMonth()) {
                // Get the latest subscription invoice..
                $invoice = $user->wasInvoiceForSubscriptionThisMonth();

                $user->fresh()->debit->update(['active' => true]);
                $user->subscription('cpd')->setInvoiceId($invoice);

                // Invoice is unpaid..
                if ($invoice->paid == false) {
                    // If the debit date is in the past, we need to debit him ASAP.
                    if ($user->fresh()->debit->billable_date <= Carbon::now()->day) {
                        $user->fresh()->debit->update(['bill_at_next_available_date' => true]);
                        $user->fresh()->debit->next_debit_date = Carbon::now()->addDays(1);
                        $user->fresh()->debit->save();
                    } else {
                        $user->fresh()->debit->update(['bill_at_next_available_date' => false]);
                        $user->fresh()->debit->save();
                    }
                } else {
                    // The invoice has been paid.
                    $date = $user->fresh()->debit->getSubscriptionAndBillableDate();
                    $now = Carbon::now();

                    $user->subscription('cpd')->starts_at = $user->subscription('cpd')->starts_at;
                    $endsat = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year)->addMonth(1);

                    $user->subscription('cpd')->ends_at = $endsat;
                    $user->subscription('cpd')->save();

                    $user->fresh()->debit->update(['active' => true]);
                    $user->fresh()->debit->update(['billable_date' => $date]);

                    $user->fresh()->debit->update(['bill_at_next_available_date' => false]);
                    $user->fresh()->debit->next_debit_date = $endsat;
                    $user->fresh()->debit->save();
                }
            } else {
                $date = $user->fresh()->debit->getSubscriptionAndBillableDate();

                $now = Carbon::now();
                $user->subscription('cpd')->starts_at = $user->subscription('cpd')->starts_at;

                $endsat = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year);
                $user->subscription('cpd')->ends_at = $endsat;
                $user->subscription('cpd')->save();

                $user->fresh()->debit->update(['active' => true]);
                $user->fresh()->debit->update(['billable_date' => $date]);
                $user->fresh()->debit->save();

                if ($user->fresh()->debit->billable_date <= Carbon::now()->day) {
                    $user->fresh()->debit->update(['bill_at_next_available_date' => true]);
                }

                $user->fresh()->debit->next_debit_date = Carbon::now()->addDay(1)->startOfDay();
                $user->fresh()->debit->save();
            }
        };
    }
}
