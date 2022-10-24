<?php

Route::get('testme', function (){


    // Invoices extract for Stiaan from date to date

//    $invoices = \App\Billing\Invoice::with('user')->whereBetween('created_at', [Carbon::parse('22 March 2017'), Carbon::parse('21 April 2017')])->get();
//    $transactions = \App\Billing\Transaction::with('user', 'invoice')->whereBetween('date', [\Carbon\Carbon::parse('1 March 2017'), \Carbon\Carbon::parse('30 April 2017')])
//        ->where('display_type', 'Credit Note')->get();
//
//    Excel::create('Credit Notes from 1 March 2017 - 30 April 2017', function($excel) use($transactions) {
//        $excel->sheet('sheet', function($sheet) use ($transactions){
//            $sheet->appendRow([
//                'Name',
//                'Email',
//                'Cell',
//                'Date',
//                'reference',
//                'total',
//                'category',
//                'Type',
//            ]);
//
//            foreach ($transactions as $transaction){
//                $sheet->appendRow([
//                    $transaction->user->first_name. ' '.$transaction->user->last_name,
//                    strtolower($transaction->user->email),
//                    $transaction->user->cell,
//                    date_format($transaction->created_at, 'd F Y'),
//                    $transaction->ref,
//                    $transaction->amount,
//                    $transaction->category,
//                    $transaction->display_type,
//                ]);
//            }
//        });
//    })->export('xls');

// Invoices extract for Stiaan from date to date
//
//    $invoices = \App\Billing\Invoice::with('user')->whereBetween('created_at', [Carbon::parse('22 March 2017'), Carbon::parse('21 April 2017')])->get();
////     $transactions = Transaction::with('user', 'invoice')->whereBetween('date', [Carbon::parse('22 March 2017'), Carbon::parse('21 April 2017')])
////         ->where('display_type', 'Credit Note')->get();
//
//    Excel::create('Invoices from 22 March 2017 - 21 April 2017', function($excel) use($invoices) {
//        $excel->sheet('sheet', function($sheet) use ($invoices){
//            $sheet->appendRow([
//                'Name',
//                'Email',
//                'Cell',
//                'Date',
//                'reference',
//                'total',
//                'type',
//                'Status',
//            ]);
//
//            foreach ($invoices as $invoice){
//                $sheet->appendRow([
//                    $invoice->user->first_name. ' '.$invoice->user->last_name,
//                    strtolower($invoice->user->email),
//                    $invoice->user->cell,
//                    date_format($invoice->created_at, 'd F Y'),
//                    $invoice->reference,
//                    $invoice->total,
//                    $invoice->type,
//                    ($invoice->paid ? "Yes": "No"),
//                ]);
//            }
//        });
//    })->export('xls');

//    $no_dates = collect();
//    $tickets = \App\AppEvents\Ticket::with('dates')->get();
//    foreach ($tickets as $ticket){
//        if (count($ticket->dates) == null){
//            $no_dates->push($ticket);
//        }
//    }
//
//    dd(count($no_dates));

    // Get the pay as you go events stats.
//    $events = \App\AppEvents\Event::with('tickets', 'tickets.user', 'tickets.invoice', 'tickets.invoice.transactions')
//        ->where('start_date' ,'>=', Carbon::parse('1 January 2017'))
//        ->where('end_date' ,'<=', Carbon::parse('30 June 2017'))->get();
//
//    $filtered = $events->reject(function ($event){
//       if ($event->is_redirect){
//           return $event;
//       }
//    });
//
//    $debit = collect();
//    $credited = collect();
//
//    foreach ($filtered as $event){
//        foreach ($event->tickets as $ticket){
//            if ($ticket->user){
//                if(! $ticket->user->subscribed('cpd') || count($ticket->user->subscriptions) == null){
//                    if ($ticket->invoice){
//                        foreach ($ticket->invoice->transactions->where('type', 'debit')->where('display_type', 'Invoice') as $transactiion){
//                            $debit->push($transactiion);
//                        }
//                        foreach ($ticket->invoice->transactions->where('type', 'credit')->where('display_type', 'Credit Note') as $transactiion){
//                            $credited->push($transactiion);
//                        }
//                    }
//                }
//            }
//        }
//    }
//
//    dd($credited->first());

//$invoices = \App\Billing\Invoice::with('transactions', 'creditmemos')->get();
//
//$newMemos = collect();
//$invoices->each(function ($invoice) use($newMemos){
//   $invoice->transactions->where('display_type', 'Credit Note')->each(function ($transaction) use($newMemos, $invoice){
//       if ($transaction->amount != 0){
//           if (count($invoice->creditmemos) == 0){
//               $newMemos->push($transaction);
//           }
//       }
//   });
//});
//
//// Creduted invoices with no credit memo
//dd($newMemos->first());

//$invoices = \App\Billing\Invoice::with('transactions', 'user')
//->whereBetween('created_at', [Carbon::parse('1 January 2017'), Carbon::parse('30 April 2017')])
//->where('type', 'subscription')
//->where('status', 'unpaid')
//->get();
//
//$data = $invoices->reject(function ($invoice){
//    $debits = $invoice->transactions->where('type', 'debit')->sum('amount');
//    $credits = $invoice->transactions->where('type', 'credit')->sum('amount');
//
//    if($debits - $credits < 0){
//        return $invoice;
//    }
//});

//    Excel::create('Unpaid subscription invoices', function($excel) use($data) {
//        $excel->sheet('sheet', function($sheet) use ($data){
//            $sheet->appendRow([
//                'Name',
//                'Email',
//                'Cell',
//                'Date',
//                'reference',
//                'total',
//                'type',
//            ]);
//
//            foreach ($data as $item){
//                $sheet->appendRow([
//                    $item->user->first_name. ' '.$item->user->last_name,
//                    strtolower($item->user->email),
//                    $item->user->cell,
//                    date_format($item->created_at, 'd F Y'),
//                    $item->reference,
//                    $item->transactions->where('type', 'debit')->sum('amount') - $item->transactions->where('type', 'credit')->sum('amount'),
//                    $item->type,
//                ]);
//            }
//        });
//    })->export('xls');



//$tickets = collect();
//$events = \App\AppEvents\Event::with('tickets', 'tickets.invoice', 'tickets.invoice.transactions')
//        ->where('end_date', '>', Carbon::parse('1 April 2017'))
//        ->where('is_redirect', false)
//        ->get();
//
//foreach ($events as $event){
//    foreach ($event->tickets as $ticket){
//        if($ticket->invoice){
//            foreach ($ticket->invoice->transactions as $transaction){
//                if ($transaction->display_type == 'Credit Note' &&
//                    $transaction->created_at->gt(Carbon::parse('1 March 2017')) &&
//                    $transaction->created_at->lt(Carbon::parse('31 March 2017'))){
//                    $tickets->push($transaction);
//                }
//            }
//        }
//    }
//}
//
//dd(number_format($tickets->sum('amount'), 2));


//
//$data = collect();
//$invoices = \App\Billing\Invoice::with('transactions')
//    ->whereBetween('created_at', [Carbon::parse('1 March 2017 '), Carbon::parse('31 March 2017')])
//    ->latest()
//    ->get();
//
//foreach ($invoices as $invoice){
//    foreach($invoice->transactions()->whereBetween('created_at', [Carbon::parse('1 March 2017 '), Carbon::parse('31 March 2017')])
//        ->where('display_type', 'Payment')
//        ->get() as $transaction){
//        $data->push($transaction);
//    }
//}
//
//dd(number_format($data->sum('amount'), 2));

//$data = collect();
//$transactions = Transaction::with('invoice')->whereBetween('created_at', [Carbon::parse('1 March 2017 '), Carbon::parse('31 March 2017')])
//    ->where('category', 'subscription')
//    ->where('display_type', 'Credit Note')
//    ->get();
//
//$transactions->each(function ($transaction){
//   if ($transaction->invoice->created_at->lt(Carbon::parse('1 March 2017')))
//});


//    $amount = collect();
//    $plans = Plan::with('subscriptions')->get();
//
//    $plans->each(function ($plan) use($amount){
//        $count = $plan->subscriptions()->where('starts_at', 'LIKE', '2017-03-25%')->count();
//        $data = [
//            'interval' => $plan->interval,
//            'plan_name' => $plan->name,
//            'total_value' => number_format($count * $plan->price, 2)
//        ];
//
//        $amount->push($data);
//    });
//
//    dd($amount);
});

//Route::get('testme', function (){
//   $user = User::where('email', 'debbie@morethanaccounting.co.za')->first();
//    Mail::send('emails.accounts.debtemail', ['user' => $user ], function ($m) use ($user) {
//        $m->from('accounts@accountingacademy.co.za', 'SA Accounting Academy');
//        $m->to('tiaant@saiba.org.za', $user['first_name'])->subject('Your assistance please');
//    });
//    return redirect()->route('home');


//Route::get(/**
// * @param $email
// */
//    'testme/{email}', function ($email){
//
//    $encrypted = Crypt::encrypt($email);


//    $tickets = \App\AppEvents\Ticket::with('dates', 'invoice')->get();
//
//    $no_dates = collect();
//    $tickets->each(function ($ticket) use($no_dates) {
//        if (count($ticket->dates) == 0)
//            $no_dates->push($ticket);
//    });
//
//    Excel::create('No Dates', function($excel) use($no_dates) {
//        $excel->sheet('sheet', function($sheet) use ($no_dates){
//            $sheet->appendRow([
//                'user_id',
//                'Date ID',
//                'Invoice',
//                'User',
//                'Email Address',
//                'Cell',
//                'ID Number'
//            ]);
//
//            foreach ($no_dates as $no_date){
//                $sheet->appendRow([
//                    $no_date->user_id,
//                    $no_date->id,
//                    '#'.$no_date->invoice->reference,
//                    $no_date->user->first_name.' '.$no_date->user->last_name,
//                    $no_date->user->email,
//                    $no_date->user->cell,
//                    $no_date->user->id_number,
//                ]);
//            }
//        });
//    })->export('xls');
//
//    foreach ($no_dates as $no_date){
//        echo "$no_date->user_id" .'</br>';
//    }

//// Store Orders
//    $orders = \App\Store\Order::with('user', 'product')->get();
//
//    $filtered = $orders->reject(function ($order){
//        if (! $order->user){
//            return $order;
//        };
//    });
//
//    Excel::create('Store Orders', function($excel) use($filtered) {
//        $excel->sheet('sheet', function($sheet) use ($filtered){
//            $sheet->appendRow([
//                'Product',
//                'Title',
//                'user_id',
//                'User',
//                'Email Address',
//                'Cell',
//                'ID Number'
//            ]);
//
//            foreach ($filtered as $filter){
//                $sheet->appendRow([
//                    $filter->product->topic,
//                    $filter->product->title,
//                    $filter->user_id,
//                    $filter->user->first_name.' '.$filter->user->last_name,
//                    $filter->user->email,
//                    $filter->user->cell,
//                    $filter->user->id_number,
//                ]);
//            }
//        });
//    })->export('xls');

//// Event Tickets all Export
//$tickets = \App\AppEvents\Ticket::with('user', 'event')->get();
//$filtered = $tickets->reject(function ($ticket){
//    if (! $ticket->user){
//        return $ticket;
//    };
//});
//
//    Excel::create('Event Tickets', function($excel) use($filtered) {
//        $excel->sheet('sheet', function($sheet) use ($filtered){
//            $sheet->appendRow([
//                'Type',
//                'Description',
//                'user_id',
//                'Event',
//                'User',
//                'Email Address',
//                'Cell',
//                'ID Number'
//            ]);
//
//            foreach ($filtered as $filter){
//                $sheet->appendRow([
//                    $filter->name,
//                    $filter->description,
//                    $filter->user_id,
//                    $filter->event->name,
//                    $filter->user->first_name.' '.$filter->user->last_name,
//                    $filter->user->email,
//                    $filter->user->cell,
//                    $filter->user->id_number,
//                ]);
//            }
//        });
//    })->export('xls');

/*
 * All invoices that has been cancelled
 * problem 1 we have invices where cancelled = true but status is not cancelled.
 */
//$invoices = \App\Billing\Invoice::with('transactions', 'user')->where('status', 'cancelled')->get();
//$new = $invoices->reject(function ($invoice) {
//    if ($invoice->transactions->contains('type', 'credit')){
//        return $invoice;
//    };
//});
//
//    Excel::create('Transactions', function($excel) use($invoices) {
//        $excel->sheet('sheet', function($sheet) use ($invoices){
//            foreach ($invoices as $invoice) {
//
//                $sheet->appendRow([
//                    'User',
//                    'user_id '.$invoice->user->id,
//                    $invoice->user->first_name.' '.$invoice->user->last_name,
//                    $invoice->user->id_number,
//                    $invoice->user->email,
//                    $invoice->user->cell,
//                    $invoice->user->status,
//                ]);
//
//                $sheet->appendRow([
//                    'Invoice',
//                    '#'.$invoice->reference,
//                    $invoice->type,
//                    $invoice->status,
//                    $invoice->discount,
//                    $invoice->sub_total,
//                    $invoice->transactions->where('type', 'credit')->sum('amount') - $invoice->transactions->where('type', 'debit')->sum('amount'),
//                    $invoice->type,
//                ]);
//
//                foreach ($invoice->transactions as $transaction){
//                    $sheet->appendRow([
//                        'Transaction',
//                        '#'.$transaction->ref,
//                        $transaction->type,
//                        $transaction->tags,
//                        $transaction->status,
//                        $transaction->amount,
//                        '-',
//                        $transaction->description,
//                    ]);
//                }
//
//                $sheet->appendRow([
//                   '-',
//                   '-',
//                   '-',
//                   '-',
//                   '-',
//                   '-',
//                   '-',
//                ]);
//            }
//        });
//    })->export('xls');
//dd(count($new));

/*
 * All Invoices that has a credit balance on it.
     * Start Date
     * End Date
     * Extract
 *
 */
//    $from = Carbon::parse('1 February 2017');
//    $to = Carbon::parse('31 March 2017');
//
//    $invoices = \App\Billing\Invoice::with('transactions')
//        ->where('status', 'paid')
//        ->where('paid', '1')
//        ->whereBetween('created_at', [$from, $to])
//        ->get();
//
//    $credited = $invoices->reject(function ($invoice) {
//        $credits = $invoice->transactions->where('type', 'credit')->sum('amount');
//        $debits = $invoice->transactions->where('type', 'debit')->sum('amount');
//        return $debits - $credits >= 0;
//    });
//
//    dd($credited->count());

//    $start = Carbon::parse('28 December 2016');
//    $end = Carbon::parse('28 February 2017');
//
//    $transactions = Transaction::with('invoice', 'user', 'user.profile')
//        ->whereBetween('date', [$start, $end])
//        ->with('user')
//        ->get();
//
//    Excel::create('Transactions', function($excel) use($transactions) {
//        $excel->sheet('sheet', function($sheet) use ($transactions){
//            $sheet->appendRow([
//                'Date',
//                'Type',
//                'Reference',
//                'Description',
//                'Category',
//                'Debit',
//                'Credit',
//                'Balance'
//            ]);
//
//            $balance = 0;
//
//            foreach ($transactions as $transaction) {
//
//                switch ($transaction->type) {
//                    case 'debit':
//                        $balance += $transaction->amount;
//                        break;
//                    case 'credit':
//                        $balance -= $transaction->amount;
//                        break;
//                }
//
//                $sheet->appendRow([
//                    $transaction->date->format('d-m-Y'),
//                    $transaction->display_type,
//                    $transaction->ref,
//                    $transaction->description,
//                    ucfirst($transaction->category),
//                    ($transaction->type == 'debit') ? $transaction->amount : '-',
//                    ($transaction->type == 'credit') ? $transaction->amount : '-',
//                    $balance
//                ]);
//            }
//        });
//    })->export('xls');


//
//    dd(money_format('R%i', $transactions->sum('amount') / 100));

//
//    dd($transactions->count());
//
////dd($subscriptions);
////
//
//    $subscriptions = Subscription::with('user', 'plan')->get();
//
//    Excel::create('Subscribers', function($excel) use($subscriptions) {
//        $excel->sheet('sheet', function($sheet) use ($subscriptions){
//            $sheet->appendRow(['first_name', 'last_name', 'email', 'package', 'subscription status', 'payment method', 'balance']);
//            foreach ($subscriptions as $subscription) {
//                $sheet->appendRow([
//                    $subscription->user->first_name,
//                    $subscription->user->last_name,
//                    $subscription->user->email,
//                    $subscription->plan->name,
//                    ($subscription->canceled() ? "Cancelled" : "Active"),
//                    ($subscription->payment_method ? : "No Payment Method"),
//                    $subscription->user->getBalance() / 100
//                ]);
//            }
//        });
//    })->export('xls');

//    $feature = Feature::where('slug', 'monthly-accounting-webinar-various-topics')->first();
//    dd($feature->pricings);
//    $user = auth()->user();
////    * We can now record manual $user->recordActivity('created', $model("profile"))
//    $address = \App\Users\Address::first();
//
//    $user->recordActivity('joined', $address);

//    $data = 'Hello Tiaan Theunissen!';
//    $message = PusherInstance::sendNotification($data);

//    $users = collect();
//    $subscriptions = Subscription::with('user')->where('plan_id', '5')->get();
//    $subscriptions->each(function ($subscription) use($users){
//        $users->push($subscription->user);
//    });
//
//    dd($users);



//
////$start = Carbon::now()->subDays(90)->startOfMonth();
//$end = Carbon::now()->subDays(120)->endOfMonth();
//
//$invoices = \App\Billing\Invoice::where('paid', false)
//                                ->where('cancelled', false)
//                                ->where('paid', false)
//                                ->where('created_at', '<=' ,[$end])
//                                ->get();
//
//    Excel::create('Users', function($excel) use ($users) {
//        $excel->sheet('sheet', function($sheet) use ($users){
//            foreach ($users as $user) {
//                $sheet->appendRow(array_dot($user->toArray()));
//            }
//        });
//    })->export('xls');


//
//$users = collect();
//$subscriptions = Subscription::with('user', 'plan', 'user')->active()->get();
//
//    /*
//     * Check user plan features if he does have access to the relevant event.
//     */
//$subscriptions->each(function ($subscription) use($users){
//    if ($subscription->plan->features->contains('slug', 'monthly-sars-and-tax-update-webinar')){
//        $users->push($subscription->user);
//
//    /*
//     * Check if user has custom plan features, if yes, check for the event.
//     */
//    }elseif ($subscription->user->custom_features){
//        if (in_array('monthly-sars-and-tax-update-webinar', $subscription->user->custom_features->features)){
//            $users->push($subscription->user);
//        }
//    };
//});

//    $debits = collect();
//        $subscriptions = Subscription::with('user', 'user.debit')->active()->get();
//        foreach ($subscriptions as $subscription){
//            if ($subscription->user){
//                $user = $subscription->user;
//                if ($user->debit != null && $user->debit->has_been_contacted == true){
//                    $this->info('updating'.' '.$user->first_name.' '.$user->last_name.' '.'subscription payment method to debit order');
//                    $subscription->update(['payment_method' => 'debit_order']);
//                    $subscription->save();
//                }
//            }
//        }
//
//        dd($debits->count());

//
//    $invoices = \App\Billing\Invoice::with('items', 'user')->where('type', 'subscription')
//        ->where('cancelled', '!=', 1 )
//        ->where('type', 'subscription')
//        ->whereBetween('created_at', [Carbon::parse('31 November 2016'), Carbon::parse('31 January 2017')])
//        ->where('created_at', 'NOT LIKE', '2016-12-07%')
//        ->get();
////
//    $new = collect([]);
//    $renewals = collect([]);
//
//    foreach ($invoices->groupBy('user_id') as $invoice) {
//        if(count($invoice) < 2) {
//            $new->push($invoice->first()->user);
//        } else {
//            $renewals->push($invoice->first()->user);
//        }
//    }
////
//    $invoices = \App\Billing\Invoice::with('user', 'user.subscriptions')->get();
//
//    Excel::create('Invoices', function($excel) use ($invoices) {
//        $excel->sheet('sheet', function($sheet) use ($invoices){
//            $sheet->appendRow(['id', 'reference', 'user_id', 'discount', 'vat_rate', 'sub_total' , 'total', 'balance', 'date_settled', 'paid', 'created_at', 'updated_at', 'cart_discount', 'cancelled', 'status', 'deleted_at', 'type', 'total due']);
//            foreach ($invoices as $invoice) {
//                dd($invoice);
//                $sheet->appendRow(array_dot($invoice->toArray()));
//            }
//        });
//    })->export('xls');
//
//
//    foreach ($invoices as $invoice) {
//        echo "<b>{$invoice->items->first()->name}</b> ({$invoice->items->first()->price}) </br>";
//    }

//    foreach ($subscriptions as $plan){
////        $mapped = $plan->subscriptions->map(function($subscription) {
////            $subscription->text_interval = 'Monthly';
////            return $subscription;
////        });
//
////        $splitted = $mapped->map(function($map) {
////            if($map->user) {
////                foreach ($map->user->invoices as $invoice) {
////                    if($invoice->total > 3000) {
////                        $map->text_interval = 'Yearly';
////                        return $map;
////                    } else {
////                        return $map;
////                    }
////                }
////            }
////        });
//
//        Excel::create($plan->plan->name, function($excel) use ($subscriptions) {
//                $excel->sheet('sheet', function($sheet) use ($subscriptions){
//                    $sheet->appendRow(['name', 'id_number', 'email', 'subscription', 'cell']);
//                    foreach ($subscriptions as $subscription) {
//
//                        if(! is_null($subscription->user))
//                        {
//                            $sheet->appendRow([
//                                $subscription->user->first_name . ' ' . $subscription->user->last_name,
//                                $subscription->user->id_number,
//                                $subscription->user->email,
//                                $subscription->plan->name,
//                                $subscription->user->cell,
//                            ]);
//                        }
//                    }
//                });
//        })->export('xls');
//    }
//});


//    $body = \App\Body::first();
//    $plan = \App\Subscriptions\Models\Plan::find(2);
//    $body->plans()->attach($plan);
//
////    $currentDaysInMonth = cal_days_in_month(1, \Carbon\Carbon::now()->month, \Carbon\Carbon::now()->year);
////    $days = collect();
////
////    for ($i = 0; $i < $currentDaysInMonth; $i++){
////        $days->push($i);
////    }
////
////
////
////    // This is how we get the imports.
////    $users = \App\Users\User::with('batches')->get();
////    $filtered = $users->filter(function ($user){
////       if (count($user->batches)){
////           return $user;
////       }
////    });
////    dd(count($filtered));
//
////$invoices = \App\Billing\Invoice::whereBetween('created_at', [\Carbon\Carbon::parse('1 December 2016'), \Carbon\Carbon::parse('31 December 2016')])
////    ->where('type', 'subscription')->get();
////
////dd(count($invoices->unique('user_id')));
//
////    $invoices = \App\Billing\Invoice::with('user')->whereBetween('created_at', [\Carbon\Carbon::parse('1 January 2017'), \Carbon\Carbon::parse('20 June 2017')])->get();
//////     $transactions = Transaction::with('user', 'invoice')->whereBetween('date', [Carbon::parse('22 March 2017'), Carbon::parse('21 April 2017')])
//////         ->where('display_type', 'Credit Note')->get();
////
////    Excel::create('Invoices from 1 January 2017 - 20 June 2017', function($excel) use($invoices) {
////        $excel->sheet('sheet', function($sheet) use ($invoices){
////            $sheet->appendRow([
////                'Name',
////                'Email',
////                'Cell',
////                'Date',
////                'reference',
////                'total',
////                'type',
////                'Status',
////            ]);
////
////            foreach ($invoices as $invoice){
////                $sheet->appendRow([
////                    $invoice->user->first_name. ' '.$invoice->user->last_name,
////                    strtolower($invoice->user->email),
////                    $invoice->user->cell,
////                    date_format($invoice->created_at, 'd F Y'),
////                    $invoice->reference,
////                    $invoice->total,
////                    $invoice->type,
////                    ($invoice->paid ? "Yes": "No"),
////                ]);
////            }
////        });
////    })->export('xls');
//
//
////    $debits = \App\DebitOrder::all();
////
////    Excel::create('Debit Orders from 1 January - 31 May 2017', function($excel) use($debits) {
////        $excel->sheet('sheet', function($sheet) use ($debits){
////            $sheet->appendRow([
////                'Client',
////                'Email',
////                'Cell',
////                'Created At',
////                'Should be loaded',
////                'Billable Date',
////                'Amount'
////            ]);
////
////            foreach ($debits as $debit){
////                if ($debit->user && $debit->user->payment_method == 'debit_order'){
////                    $sheet->appendRow([
////                        $debit->user->first_name.' '.$debit->user->last_name,
////                        $debit->user->email,
////                        $debit->user->cell,
////                        date_format($debit->created_at, 'd F Y'),
////                        $debit->has_been_contacted,
////                        $debit->billable_date,
////                        $debit->user->subscription('cpd')->plan->price
////                    ]);
////                }
////            }
////        });
////    })->export('xls');
//
////    $users = \App\Users\User::with('invoices')->get();
////    $overdue = collect();
////
////    $users->each(function ($user) use($overdue){
////        if ($user->ageAnalysis()){
////            if ($user->ageAnalysis() > '60'){
////                $invoices = $user->overdueInvoices()->reject(function ($invoice){
////                    if ($invoice->type != 'subscription'){
////                        return $invoice;
////                    }
////                });
////
////                if ($invoices->count() > 2){
////                    if ($user->subscribed('cpd')){
////                        $overdue->push($user);
////                    }
////                };
////            }
////        }
////    });
////
////    $overdue->each(function ($user){
////        $invoices = $user->invoices->reject(function ($invoice){
////            if ($invoice->type != 'subscription' || $invoice->paid == true || $invoice->status == 'cancelled'){
////                return $invoice;
////            }
////        });
////
////        try{
////            dd($invoices);
////            $invoices->each(function ($invoice){
////                $this->info('cancelling invoice '.$invoice->reference);
////                $this->cancel($invoice->id);
////            });
////
////            $this->info('cancelling Subscription for '.$invoices->first()->user->first_name.' with email '.$invoices->first()->user->email);
////            if ($invoices->first()->user->subscribed('cpd')){
////                $invoices->first()->user->subscription('cpd')->cancel(true);
////            }
////        }catch (Exception $exception){
////            return;
////        }
////    });
////$invoices = \App\Billing\Invoice::with('user')->withTrashed()
////    ->where('created_at', 'LIKE', '2017-01-25%')
////    ->where('balance', '>', '0')
////    ->where('type', 'subscription')
////    ->where('status', '!=', 'paid')
////    ->get();
////
////    Excel::create('All CPD Subscriptions', function($excel) use($invoices) {
////        $excel->sheet('sheet', function($sheet) use ($invoices){
////
////            $sheet->appendRow([
////                'UserId',
////                'Name',
////                'Surname',
////                'Email',
////                'Invoice',
////                'Balance'
////            ]);
////
////            foreach ($invoices as $invoice){
////                $sheet->appendRow([
////                    $invoice->user->id,
////                    $invoice->user->first_name,
////                    $invoice->user->last_name,
////                    $invoice->user->email,
////                    $invoice->reference,
////                    $invoice->balance,
////                ]);
////            }
////        });
////    })->export('xls');
//
////Excel::create('All CPD Subscriptions', function($excel) use($invoices) {
////        $excel->sheet('sheet', function($sheet) use ($invoices){
////
////            $sheet->appendRow([
////                'Name',
////                'Surname',
////                'Email',
////                'Method',
////                'Frequently',
////                'D/O Date',
////                'D/O Amount',
////                'O/F Payment date',
////                'O/F Payment Amount',
////            ]);
////
////            foreach ($subscribers as $subscriber){
////                $sheet->appendRow([
////                    $subscriber->first_name,
////                    $subscriber->last_name,
////                    $subscriber->email,
////                    (str_replace('_', ' ', $subscriber->payment_method)? : "Null"),
////                    $subscriber->subscription('cpd')->plan->interval,
////                    ($subscriber->debit ? $subscriber->debit->billable_date : "-"),
////                    ($subscriber->debit()->count() > 0? $subscriber->subscription('cpd')->plan->price : "-" ),
////                    ($subscriber->subscription('cpd')->plan->interval == 'year') ? date_format($subscriber->subscription('cpd')->starts_at, 'd F Y') : "-",
////                    ($subscriber->subscription('cpd')->plan->interval == 'year') ? $subscriber->subscription('cpd')->plan->price : "-"
////                ]);
////            }
////        });
////    })->export('xls');
////    $user = \App\Users\User::find(2);
////    $invoice = \App\Billing\Invoice::find(21488);
////
//////    $money = $user->wallet->add('500', 'eft');
////    $money = $user->wallet->sub($invoice->balance, $invoice);
////    dd($money);
///
/// public function checkBodyPricings($event)
{
    if (auth()->user()->body) {
        foreach ($event->venues as $venue) {
            $pricings = collect();
            $memberBodyPricing = auth()->user()->body->pricings->where('venue_id', $venue->id);

            if (count($memberBodyPricing)) {
                foreach ($memberBodyPricing as $pricing) {
                    if ($venue->pricings->contains($pricing)) {
                        $filtered_pricings = $venue->pricings->reject(function ($pric) use ($pricing) {
                            if ($pricing->id != $pric->id) {
                                return $pric;
                            }
                        });
                        $pricings->push($filtered_pricings->first());
                    }
                }
                $venue->setRelation('pricings', $pricings);
            } else {
                $bodies = Body::with('pricings')->get();
                $pricings = collect();

                foreach ($bodies as $body) {
                    foreach ($body->pricings as $pricing) {
                        if ($venue->pricings->contains($pricing)) {
                            $filtered_pricings = $venue->pricings->reject(function ($pric) use ($pricing) {
                                if ($pricing->id == $pric->id) {
                                    return $pric;
                                }
                            });
                            $pricings->push($filtered_pricings->first());
                        }
                    }
                }
                $venue->setRelation('pricings', $pricings);
            }
        }
    } else {
        foreach ($event->venues as $venue) {
            $pricings = collect();

            if (auth()->user()->body) {
                $memberBodyPricing = auth()->user()->body->pricings->where('venue_id', $venue->id);
                if (count($memberBodyPricing)) {
                    foreach ($memberBodyPricing as $pricing) {
                        if ($venue->pricings->contains($pricing)) {
                            $filtered_pricings = $venue->pricings->reject(function ($pric) use ($pricing) {
                                if ($pricing->id == $pric->id) {
                                    return $pric;
                                }
                            });
                            $pricings->push($filtered_pricings->first());
                        }
                    }
                    $venue->setRelation('pricings', $pricings);
                }
            }else {
                $bodies = Body::with('pricings')->get();
                $pricings = collect();

                foreach ($bodies as $body) {
                    foreach ($body->pricings as $bodyPricing) {
                        if ($venue->pricings->contains($bodyPricing)) {
                            $filtered_pricings = $venue->pricings->filter(function ($pric) use ($bodyPricing) {
                                if ($pric->id = $bodyPricing->id) {
                                    return $pric;
                                }
                            });
                            $pricings->push($filtered_pricings->first());
                        }
                    }
                }
                $venue->setRelation('pricings', $pricings);
            }
        }
    }
}