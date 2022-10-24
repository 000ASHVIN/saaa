<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UpdateUserPaymentMethodController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $previous = $user->payment_method;
        $user->update($request->only('payment_method'));

        if ($request->payment_method) {
            Mail::send('emails.billing.change', ['user' => $user, 'previous' => $previous], function ($m) use ($user) {
                $m->from(config('app.email'), config('app.name'));
                $m->to(config('app.email'), 'Admin')->subject('Updated Payment Method');
            });
        }
    }
}
