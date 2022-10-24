<?php

namespace App\Http\Controllers\Admin;

use App\Users\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mail;

class CryptEmailAddressController extends Controller
{

    public function index()
    {
        return view('admin.cryp.index');
    }

    public function store(Request $request)
    {
        $users = collect();
        $user = User::where('email', $request->email)->first();

        if ($user){
            $balance = $user->transactions()->where('type', 'debit')
            ->sum('amount') - $user->transactions()
            ->where('type', 'credit')
            ->sum('amount');

            if($balance > 0) {
                $users->push([
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'balance' => $balance
                ]);
            }
        }

            foreach ($users as $user){
                Mail::send('emails.accounts.debtemail', ['user' => $user ], function ($m) use ($user) {
                    $m->from(config('app.email'), config('app.name'));
                    $m->to('tiaant@saiba.org.za', $user['first_name'])->subject('Your assistance please');
                });
            }

        alert()->success('Keys has been generated and sent!', 'Success!');
        return redirect()->back();
    }
}
