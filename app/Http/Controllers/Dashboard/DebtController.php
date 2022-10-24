<?php

namespace App\Http\Controllers\Dashboard;

use App\CustomDebitOrders;
use App\CustomEftPayments;
use App\Repositories\DebitOrder\DebitOrderRepository;
use App\Users\User;
use Carbon\Carbon;
use Crypt;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DebtController extends Controller
{
    private $debitOrderRepository;
    public function __construct(DebitOrderRepository $debitOrderRepository)
    {
        $this->debitOrderRepository = $debitOrderRepository;
    }

    public function store($secret)
    {
        $encrypted = $secret;
        $decrypted = Crypt::decrypt($secret);
        $user = User::where('email', $decrypted)->first();

        if (! $user){
            alert()->warning('Your account was not found, Please contact support', 'Warning')->persistent('close');
            return redirect()->route('home');
        }else{
            return redirect()->route('dashboard.security_question', compact('encrypted'));
        }
    }

    public function security_question(Request $request, $secret)
    {
        $decrypted = Crypt::decrypt($secret);
        $user = User::where('email', $decrypted)->first();
        return view('dashboard.security.index', compact('user', 'secret'));
    }

    public function post_security_question(Request $request)
    {
        $secret = Crypt::decrypt($request->secret);
        if ($secret == $request->email){
            $user = User::where('email', $request->email)->first();
            Auth::login($user);

            if ($request->payment_option == 'debit'){
                return view('dashboard.payment_options.debit_order', compact('user'));
            }else{
                return view('dashboard.payment_options.full_payment', compact('user'));
            }
        }else{
            alert()->warning('Your email address appears to be incorrect, Please try again or alternatively contact support', 'Account not found')->persistent('close');
            return back();
        }
    }

    public function store_debit_order(Request $request, $user)
    {
        $this->validate($request, [
            'id_number' => 'required|numeric',
            'cell' => 'required|numeric|min:9',
            'bank' => 'required',
            'branch_name' => 'required',
            'type' => 'required',
            'account_number' => 'required|numeric',
            'branch_code' => 'required',
            'billable_date' => 'required|numeric',
            'payment_option' => 'required',
            'amount' => 'required|numeric'
        ]);

        $user = User::find($user);
        $user->update($request->only(['id_number', 'cell']));

        if ($user->custom_debit()->count() <= 0){
            $custom_debit = new CustomDebitOrders([
                'id_number' => $user->id_number,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'mobile' => $user->cell,
                'reference' => $user->invoices->where('type','subscription')->first()->reference,
                'bank' => $request->bank,
                'number' => $request->account_number,
                'type' => $request->type,
                'branch_name' => $request->branch_name,
                'branch_code' => $request->branch_code,
                'billable_date' => $request->billable_date,
                'start_date' => Carbon::now()->addMonths(1)->startOfMonth()->startOfDay(),
                'final_date' => Carbon::now()->addMonths(9)->endOfMonth()->endOfDay(),
                'amount' => $request->amount
            ]);
            $user->custom_debit()->save($custom_debit);
            alert()->success('Your debit order process has been completed.', 'Success');
        }else{
            alert()->warning('you have already completed the debit order process', 'Warning');
        }
        return redirect()->route('dashboard');
    }

    public function eft_payment($user)
    {
        $user = User::find($user);
        return view('dashboard.eft.index', compact('user'));
    }

    public function store_eft_payment(Request $request, $user)
    {
        $this->validate($request, [
            'id_number' => 'required',
            'date' => 'required'
        ]);

        $user = User::find($user);
        if ($user->custom_eft()->count() <= 0){
            $eft = new CustomEftPayments([
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'id_number' => $request->id_number,
                'cell' => $user->cell,
                'email' => $user->email,
                'date' => Carbon::parse($request->date),
            ]);
            $user->custom_eft()->save($eft);
            alert()->success('Thank you completing the EFT process.', 'Success');
        }else{
            alert()->warning('You have already completed the eft process', 'Warning');
        }
        return redirect()->route('dashboard');

    }

}
