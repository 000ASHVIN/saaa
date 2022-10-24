<?php

namespace App\Http\Controllers\Admin;
error_reporting(0);

use App\AppEvents\Ticket;
use App\BulkMailStat;
use App\Installment;
use App\Store\Order;
use App\Users\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Debit;
use App\Billing\Invoice;
use App\Subscriptions\Models\Subscription;
use Maatwebsite\Excel\Facades\Excel;

class StatsController extends Controller
{
    public function getMembers(Request $request)
    {
        $startDate = new Carbon($request->get('start-date', ''));
        $startDate->setTime(0, 0, 0);
        $endDate = new Carbon($request->get('end-date', ''));
        $endDate->setTime(23, 59, 59);
        $members = User::with(['invoices', 'subscriptions', 'profile'])->whereBetween('created_at', [$startDate, $endDate])->get();
        return view('admin.stats.members', compact('members'));
    }

    public function getInstallments(Request $request)
    {
        $startDate = new Carbon($request->get('start-date', ''));
        $startDate->setTime(0, 0, 0);
        $endDate = new Carbon($request->get('end-date', ''));
        $endDate->setTime(23, 59, 59);
        $installments = Installment::with(['subscription', 'subscription.plan', 'invoice'])->whereBetween('due_date', [$startDate, $endDate])->get();
        //dd($installments->toArray());
        return view('admin.stats.installments', compact('installments'));
    }

    public function getOrders(Request $request)
    {
        $startDate = new Carbon($request->get('start-date', ''));
        $startDate->setTime(0, 0, 0);
        $endDate = new Carbon($request->get('end-date', ''));
        $endDate->setTime(23, 59, 59);
        $orders = Order::with(['invoice', 'shippingInformation', 'shippingInformation.status', 'shippingMethod'])->whereBetween('created_at', [$startDate, $endDate])->get();
        return view('admin.stats.orders', compact('orders'));
    }

    public function getRegistrations(Request $request)
    {
        $startDate = new Carbon($request->get('start-date', ''));
        $startDate->setTime(0, 0, 0);
        $endDate = new Carbon($request->get('end-date', ''));
        $endDate->setTime(23, 59, 59);

        $registrations = Ticket::with(['event', 'venue', 'invoice', 'user', 'user.profile'])->whereBetween('created_at', [$startDate, $endDate])->get();

        $registrations->each(function($registration) {
            if($registration->user && $registration->user->subscribed('cpd')) {
                $registration->user->subscription = $registration->user->subscription();
                $registration->user->subscription->plan = $registration->user->subscription()->plan;
            }
        });
        return \View::make('admin.stats.registrations', compact('registrations'));
    }

    public function getMailers()
    {
        $bulkMailStats = BulkMailStat::withCampaignEvent()->get();
        return $bulkMailStats;
    }

    public function getSendGridCampaigns()
    {
        $apiKey = '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';
        $client = $client = new \GuzzleHttp\Client();
        $res = $client->get('http://api.sendgrid.com/v3/contactdb/lists', [
            'auth' => ['SAAASG', 'SAAAadmin12345']
        ]);
        return response()->json(json_decode($res->getBody()));
    }

    public function getCpdCoursesDashboard(Request $request) {

        // Export excel report
        if($request->method() == 'POST') {
            if($request->download_cpds) {
                return $this->downloadCpdSubscribers($request);
            }
            else if($request->download_courses) {
                return $this->downloadCourseSubscribers($request);
            }
            
        }

        $start = Carbon::now()->subMonths(6)->startOfMonth();
        $startClone = clone $start;
        // $end = Carbon::parse('2019-06-01')->endOfMonth();
        $end = Carbon::now()->endOfMonth();

        // Create time range
        $months = [];
        while($startClone < $end) {
            $month = [];
            $month['name'] = $startClone->format('F');
            $month['key'] = $startClone->format('Y-m');

            $months[] = $month;
            $startClone = $startClone->addMonth(1);
        }

        $month = [
            'name' => 'Total',
            'key' => 'total'
        ];
        $months[] = $month;
        
        // Blank month
        $blankMonth = [
            'paid' => [
                'orders' => 0,
                'amount' => 0
            ],
            'unpaid' => [
                'orders' => 0,
                'amount' => 0
            ],
            'cancelled' => [
                'orders' => 0,
                'amount' => 0
            ]
        ];

        // Data
        $data = [
            'cpds' => [
                'debit_orders' => [],
                'once_off' => []
            ],
            'courses' => [
                'debit_orders' => [],
                'once_off' => []
            ]
        ];

        
        // Paid/Unpaid Debit orders for (cpd + courses)
        $debits = Invoice::selectRaw('invoices.*, 
            DATE_FORMAT(invoices.created_at, "%Y-%m") AS month_year, 
            count(DISTINCT(invoices.id)) AS total_orders,
            sum(transactions.amount)/100 AS total_amount')
            ->join('transactions', 'transactions.invoice_id', '=', 'invoices.id')
            ->whereIn('invoices.status', ['paid', 'unpaid', 'credit noted'])
            ->whereBetween('invoices.created_at', [$start, $end])
            ->where('transactions.type', '=', 'credit')
            ->where('transactions.method', '=', 'debit')
            ->groupBy('invoices.type')
            ->groupBy('invoices.status')
            ->groupBy('month_year')
            ->get();

        foreach($debits as $debit) {

            $type = 'unknown';
            if($debit->type == 'course') {
                $type = 'courses';
            }
            else if($debit->type == 'subscription') {
                $type = 'cpds';
            }

            if(!isset($data[$type]['debit_orders'][$debit->month_year])) {
                $data[$type]['debit_orders'][$debit->month_year] = $blankMonth;
            }

            $data[$type]['debit_orders'][$debit->month_year][$debit->status] = [
                'orders' => $debit->total_orders,
                'amount' => $debit->total_amount
            ];

            // Add to total as well
            if(!isset($data[$type]['debit_orders']['total'])) {
                $data[$type]['debit_orders']['total'] = $blankMonth;
            }

            $data[$type]['debit_orders']['total'][$debit->status]['orders'] += $debit->total_orders;
            $data[$type]['debit_orders']['total'][$debit->status]['amount'] += $debit->total_amount;

        }
        
        // Paid/Unpaid/Cancelled Once off (cpd) 
        $invoices = Invoice::selectRaw('invoices.*, 
            DATE_FORMAT(invoices.created_at, "%Y-%m") AS month_year, 
            count(DISTINCT(invoices.id)) AS total_orders,
            sum(invoices.total) AS total_amount')
            ->join('items_lists', 'invoices.id', '=', 'items_lists.invoice_id')
            ->join('items', 'items.id', '=', 'items_lists.item_id')
            ->join('plans', 'plans.id', '=', 'items.item_id')
            // ->where('items.item_type', 'App\Subscriptions\Models\Plan')
            ->where('items.type', 'subscription')
            ->where('plans.interval', '=', 'year')
            ->whereBetween('invoices.created_at', [$start, $end])
            ->groupBy('invoices.status')
            ->groupBy('month_year')
            ->get();
        
        foreach($invoices as $invoice) {

            if(!isset($data['cpds']['once_off'][$invoice->month_year])) {
                $data['cpds']['once_off'][$invoice->month_year] = $blankMonth;
            }

            // Status
            $status = $invoice->status;
            if($invoice->status == 'credit noted') {
                $status = 'cancelled';
            }

            $data['cpds']['once_off'][$invoice->month_year][$status] = [
                'orders' => $invoice->total_orders,
                'amount' => $invoice->total_amount
            ];

            // Add to total as well
            if(!isset($data['cpds']['once_off']['total'])) {
                $data['cpds']['once_off']['total'] = $blankMonth;
            }

            $data['cpds']['once_off']['total'][$status]['orders'] += $invoice->total_orders;
            $data['cpds']['once_off']['total'][$status]['amount'] += $invoice->total_amount;

        }
        

        // Paid/Unpaid/Cancelled Once off (courses)
        $invoices = Invoice::selectRaw('invoices.*, 
            DATE_FORMAT(invoices.created_at, "%Y-%m") AS month_year, 
            count(invoices.id) AS total_orders,
            sum(invoices.total) AS total_amount')
            ->join('items_lists', 'invoices.id', '=', 'items_lists.invoice_id')
            ->join('items', 'items.id', '=', 'items_lists.item_id')
            ->join('courses', 'courses.id', '=', 'items.item_id')
            ->where('items.item_type', 'App\Models\Course')
            // ->where('items.type', 'course')
            ->where('items.price', '>', 2900)
            ->where('items.price', '>', '(courses.monthly_enrollment_fee/100)')
            ->whereBetween('invoices.created_at', [$start, $end])
            ->groupBy('invoices.status')
            ->groupBy('month_year')
            ->get();
        
        foreach($invoices as $invoice) {

            if(!isset($data['courses']['once_off'][$invoice->month_year])) {
                $data['courses']['once_off'][$invoice->month_year] = $blankMonth;
            }
            
            // Status
            $status = $invoice->status;
            if($invoice->status == 'credit noted') {
                $status = 'cancelled';
            }

            $data['courses']['once_off'][$invoice->month_year][$status] = [
                'orders' => $invoice->total_orders,
                'amount' => $invoice->total_amount
            ];

            // Add to total as well
            if(!isset($data['courses']['once_off']['total'])) {
                $data['courses']['once_off']['total'] = $blankMonth;
            }

            $data['courses']['once_off']['total'][$status]['orders'] += $invoice->total_orders;
            $data['courses']['once_off']['total'][$status]['amount'] += $invoice->total_amount;

        }

        // Cancelled debit orders (Course + CPD)
        $invoices = Invoice::selectRaw('invoices.*, 
            DATE_FORMAT(invoices.created_at, "%Y-%m") AS month_year, 
            count(invoices.id) AS total_orders,
            sum(invoices.total) AS total_amount')
            ->join('transactions', 'transactions.invoice_id', '=', 'invoices.id')
            ->whereIn('invoices.status', ['cancelled', 'credit noted'])
            ->whereBetween('invoices.created_at', [$start, $end])
            ->where('transactions.type', '=', 'credit')
            ->where('transactions.method', '=', 'debit')
            ->groupBy('invoices.type')
            ->groupBy('month_year')
            ->get();
        
        foreach($invoices as $invoice) {

            $type = 'unknown';
            if($invoice->type == 'course') {
                $type = 'courses';
            }
            else if($invoice->type == 'subscription') {
                $type = 'cpds';
            }

            if(!isset($data[$type]['debit_orders'][$invoice->month_year])) {
                $data[$type]['debit_orders'][$invoice->month_year] = $blankMonth;
            }

            $data[$type]['debit_orders'][$invoice->month_year]['cancelled'] = [
                'orders' => $invoice->total_orders,
                'amount' => $invoice->total_amount
            ];

            // Add to total as well
            if(!isset($data[$type]['debit_orders']['total'])) {
                $data[$type]['debit_orders']['total'] = $blankMonth;
            }

            $data[$type]['debit_orders']['total']['cancelled']['orders'] += $invoice->total_orders;
            $data[$type]['debit_orders']['total']['cancelled']['amount'] += $invoice->total_amount;

        }

        // Total CPD subscribers
        $currentDate = Carbon::now();
        $totalSubscribers = Subscription::where('subscriptions.name', '=', 'cpd')
            ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')
            ->where('plans.price', '>', '0')
            ->where('subscriptions.starts_at', '<=', $currentDate)
            ->where('subscriptions.ends_at', '>=', $currentDate)
            ->where('subscriptions.suspended', '0')
            ->count();

        return view('admin.stats.cpd_course_dashboard', compact('months', 'data', 'totalSubscribers'));        
    }

    public function downloadCpdSubscribers($request) {
        
        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();

        
        return Excel::create('CPD SUBS ORDERS from ' . date_format($from, 'Y-m-d') . ' - ' . date_format($to, 'Y-m-d'), function ($excel) use ($from, $to) {

            // SUBS ORDERS - PAID
            $invoices = Invoice::with('user', 'items', 'user.subscriptions', 'user.body', 'transactions')
                ->selectRaw('invoices.*')
                ->whereBetween('invoices.created_at', [$from, $to])
                ->where('invoices.type', 'subscription')
                ->where('invoices.status', 'paid')
                ->get();
            $excel->sheet('SUBS ORDERS - PAID', function ($sheet) use ($invoices) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Cell',
                    'Date',
                    'Invoice Number',
                    'type',
                    'Description',
                    'Paid',
                    'Paid Status',
                    'User Since',
                    'Sales Rep',
                    'Invoice Total',
                    'Invoice Discount',
                    'Invoice Credit',
                    'Amount  Paid',
                    'Balance  due',
                    'Professional Body',
                    'Subscription Plan Type'
                ]);
                foreach ($invoices as $invoice) {
                    $item_description = ($invoice->items->count())?implode(",",$invoice->items->pluck('name')->toArray()):"";

                    $sheet->appendRow([
                        $invoice->user->first_name.' '.$invoice->user->last_name,
                        strtolower($invoice->user->email),
                        $invoice->user->cell,
                        date_format($invoice->created_at, 'Y-m-d'),
                        $invoice->reference,
                        $invoice->type,
                        $item_description,
                        ($invoice->paid ? 'Yes' : 'No'),
                        ($invoice->status),
                        $invoice->user->subscription('cpd') ? date_format(Carbon::parse(($invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : ' - ',
                        ($invoice->user->subscribed('cpd') ? (($invoice->user->subscription('cpd')->SalesAgent() ? $invoice->user->subscription('cpd')->SalesAgent()->name : '') ?: 'N/A') : 'N/A'),
                        ($invoice ? $invoice->total : '0'),
                        ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                        ($invoice ? (($invoice->status == 'credit noted' || $invoice->status == 'cancelled') ? $invoice->transactions->where('type', 'credit')->sum('amount') : 0) : '0'),
                        ($invoice ? (($invoice->status == 'paid') ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0') : '0'),
                        ($invoice ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount') : '0'),
                        ($invoice->user->body ? $invoice->user->body->title : ($invoice->user->specified_body ?: "N/A")),
                        $this->getPlanType($invoice)
                        // $invoice->user->subscription('cpd') ? $invoice->user->subscription('cpd')->plan->interval : 'N/A',

                    ]);
                }
            });

            // SUBS ORDERS - UNPAID
            $invoices = Invoice::with('user', 'items', 'user.subscriptions', 'user.body', 'transactions')
                ->selectRaw('invoices.*')
                ->whereBetween('invoices.created_at', [$from, $to])
                ->where('invoices.type', 'subscription')
                ->where('invoices.status', 'unpaid')
                ->get();
            $excel->sheet('SUBS ORDERS - UNPAID', function ($sheet) use ($invoices) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Cell',
                    'Date',
                    'Invoice Number',
                    'type',
                    'Description',
                    'Paid',
                    'Paid Status',
                    'User Since',
                    'Sales Rep',
                    'Invoice Total',
                    'Invoice Discount',
                    'Invoice Credit',
                    'Amount  Paid',
                    'Balance  due',
                    'Professional Body',
                    'Subscription Plan Type'
                ]);

                foreach ($invoices as $invoice) {
                    $item_description = ($invoice->items->count())?implode(",",$invoice->items->pluck('name')->toArray()):"";
                    $sheet->appendRow([
                        $invoice->user->first_name.' '.$invoice->user->last_name,
                        strtolower($invoice->user->email),
                        $invoice->user->cell,
                        date_format($invoice->created_at, 'Y-m-d'),
                        $invoice->reference,
                        $invoice->type,
                        $item_description,
                        ($invoice->paid ? 'Yes' : 'No'),
                        ($invoice->status),
                        $invoice->user->subscription('cpd') ? date_format(Carbon::parse(($invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : ' - ',
                        ($invoice->user->subscribed('cpd') ? (($invoice->user->subscription('cpd')->SalesAgent() ? $invoice->user->subscription('cpd')->SalesAgent()->name : '') ?: 'N/A') : 'N/A'),
                        ($invoice ? $invoice->total : '0'),
                        ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                        ($invoice ? (($invoice->status == 'credit noted' || $invoice->status == 'cancelled') ? $invoice->transactions->where('type', 'credit')->sum('amount') : 0) : '0'),
                        ($invoice ? (($invoice->status == 'paid') ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0') : '0'),
                        ($invoice ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount') : '0'),
                        ($invoice->user->body ? $invoice->user->body->title : ($invoice->user->specified_body ?: "N/A")),
                        // $invoice->user->subscription('cpd') ? $invoice->user->subscription('cpd')->plan->interval : 'N/A',
                        $this->getPlanType($invoice),
                    ]);
                }
            });

            // SUBS ORDERS - CANCELLED
            $invoices = Invoice::with('user', 'items', 'user.subscriptions', 'user.body', 'transactions')
                ->selectRaw('invoices.*')
                ->whereBetween('invoices.created_at', [$from, $to])
                ->where('invoices.type', 'subscription')
                ->whereIn('invoices.status', ['cancelled', 'credit noted'])
                ->get();
            $excel->sheet('SUBS ORDERS - CANCELLED', function ($sheet) use ($invoices) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Cell',
                    'Date',
                    'Invoice Number',
                    'type',
                    'Description',
                    'Paid',
                    'Paid Status',
                    'User Since',
                    'Sales Rep',
                    'Invoice Total',
                    'Invoice Discount',
                    'Invoice Credit',
                    'Amount  Paid',
                    'Balance  due',
                    'Professional Body',
                    'Subscription Plan Type'
                ]);

                foreach ($invoices as $invoice) {
                    $item_description = ($invoice->items->count())?implode(",",$invoice->items->pluck('name')->toArray()):"";
                    $sheet->appendRow([
                        $invoice->user->first_name.' '.$invoice->user->last_name,
                        strtolower($invoice->user->email),
                        $invoice->user->cell,
                        date_format($invoice->created_at, 'Y-m-d'),
                        $invoice->reference,
                        $invoice->type,
                        $item_description,
                        ($invoice->paid ? 'Yes' : 'No'),
                        ($invoice->status),
                        $invoice->user->subscription('cpd') ? date_format(Carbon::parse(($invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : ' - ',
                        ($invoice->user->subscribed('cpd') ? (($invoice->user->subscription('cpd')->SalesAgent() ? $invoice->user->subscription('cpd')->SalesAgent()->name : '') ?: 'N/A') : 'N/A'),
                        ($invoice ? $invoice->total : '0'),
                        ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                        ($invoice ? (($invoice->status == 'credit noted' || $invoice->status == 'cancelled') ? $invoice->transactions->where('type', 'credit')->sum('amount') : 0) : '0'),
                        ($invoice ? (($invoice->status == 'paid') ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0') : '0'),
                        ($invoice ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount') : '0'),
                        ($invoice->user->body ? $invoice->user->body->title : ($invoice->user->specified_body ?: "N/A")),
                        // $invoice->user->subscription('cpd') ? $invoice->user->subscription('cpd')->plan->interval : 'N/A',
                        $this->getPlanType($invoice)
                    ]);
                }
            });

        })->export('xls');

    }

    public function downloadCourseSubscribers($request) {

        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();

        
        return Excel::create('COURSE ORDERS from ' . date_format($from, 'Y-m-d') . ' - ' . date_format($to, 'Y-m-d'), function ($excel) use ($from, $to) {

            // COURSE ORDERS - PAID
            $invoices = Invoice::with('user', 'items', 'user.subscriptions', 'user.body', 'transactions')
                ->selectRaw('invoices.*')
                ->whereBetween('invoices.created_at', [$from, $to])
                ->where('invoices.type', 'course')
                ->where('invoices.status', 'paid')
                ->get();
            $excel->sheet('COURSE ORDERS - PAID', function ($sheet) use ($invoices) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Cell',
                    'Date',
                    'Invoice Number',
                    'type',
                    'Description',
                    'Paid',
                    'Paid Status',
                    'User Since',
                    'Sales Rep',
                    'Invoice Total',
                    'Invoice Discount',
                    'Invoice Credit',
                    'Amount  Paid',
                    'Balance  due',
                    'Professional Body',
                    'Subscription Plan Type'
                ]);

                foreach ($invoices as $invoice) {
                    $item_description = ($invoice->items->count())?implode(",",$invoice->items->pluck('name')->toArray()):"";
                    $sheet->appendRow([
                        $invoice->user->first_name.' '.$invoice->user->last_name,
                        strtolower($invoice->user->email),
                        $invoice->user->cell,
                        date_format($invoice->created_at, 'Y-m-d'),
                        $invoice->reference,
                        $invoice->type,
                        $item_description,
                        ($invoice->paid ? 'Yes' : 'No'),
                        ($invoice->status),
                        $invoice->user->subscription('cpd') ? date_format(Carbon::parse(($invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : ' - ',
                        ($invoice->user->subscribed('cpd') ? (($invoice->user->subscription('cpd')->SalesAgent() ? $invoice->user->subscription('cpd')->SalesAgent()->name : '') ?: 'N/A') : 'N/A'),
                        ($invoice ? $invoice->total : '0'),
                        ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                        ($invoice ? (($invoice->status == 'credit noted' || $invoice->status == 'cancelled') ? $invoice->transactions->where('type', 'credit')->sum('amount') : 0) : '0'),
                        ($invoice ? (($invoice->status == 'paid') ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0') : '0'),
                        ($invoice ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount') : '0'),
                        ($invoice->user->body ? $invoice->user->body->title : ($invoice->user->specified_body ?: "N/A")),
                        // $invoice->user->subscription('cpd') ? $invoice->user->subscription('cpd')->plan->interval : 'N/A',
                        $this->getPlanType($invoice),

                    ]);
                }
            });

            // COURSE ORDERS - UNPAID
            $invoices = Invoice::with('user', 'items', 'user.subscriptions', 'user.body', 'transactions')
                ->selectRaw('invoices.*')
                ->whereBetween('invoices.created_at', [$from, $to])
                ->where('invoices.type', 'course')
                ->where('invoices.status', 'unpaid')
                ->get();
            $excel->sheet('COURSE ORDERS - UNPAID', function ($sheet) use ($invoices) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Cell',
                    'Date',
                    'Invoice Number',
                    'type',
                    'Description',
                    'Paid',
                    'Paid Status',
                    'User Since',
                    'Sales Rep',
                    'Invoice Total',
                    'Invoice Discount',
                    'Invoice Credit',
                    'Amount  Paid',
                    'Balance  due',
                    'Professional Body',
                    'Subscription Plan Type'
                ]);

                foreach ($invoices as $invoice) {
                    $item_description = ($invoice->items->count())?implode(",",$invoice->items->pluck('name')->toArray()):"";
                    $sheet->appendRow([
                        $invoice->user->first_name.' '.$invoice->user->last_name,
                        strtolower($invoice->user->email),
                        $invoice->user->cell,
                        date_format($invoice->created_at, 'Y-m-d'),
                        $invoice->reference,
                        $invoice->type,
                        $item_description,
                        ($invoice->paid ? 'Yes' : 'No'),
                        ($invoice->status),
                        $invoice->user->subscription('cpd') ? date_format(Carbon::parse(($invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : ' - ',
                        ($invoice->user->subscribed('cpd') ? (($invoice->user->subscription('cpd')->SalesAgent() ? $invoice->user->subscription('cpd')->SalesAgent()->name : '') ?: 'N/A') : 'N/A'),
                        ($invoice ? $invoice->total : '0'),
                        ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                        ($invoice ? (($invoice->status == 'credit noted' || $invoice->status == 'cancelled') ? $invoice->transactions->where('type', 'credit')->sum('amount') : 0) : '0'),
                        ($invoice ? (($invoice->status == 'paid') ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0') : '0'),
                        ($invoice ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount') : '0'),
                        ($invoice->user->body ? $invoice->user->body->title : ($invoice->user->specified_body ?: "N/A")),
                        // $invoice->user->subscription('cpd') ? $invoice->user->subscription('cpd')->plan->interval : 'N/A',
                        $this->getPlanType($invoice)

                    ]);
                }
            });

            // COURSE ORDERS - CANCELLED
            $invoices = Invoice::with('user', 'items', 'user.subscriptions', 'user.body', 'transactions')
                ->selectRaw('invoices.*')
                ->whereBetween('invoices.created_at', [$from, $to])
                ->where('invoices.type', 'course')
                ->whereIn('invoices.status', ['cancelled', 'credit noted'])
                ->get();
            $excel->sheet('COURSE ORDERS - CANCELLED', function ($sheet) use ($invoices) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Cell',
                    'Date',
                    'Invoice Number',
                    'type',
                    'Description',
                    'Paid',
                    'Paid Status',
                    'User Since',
                    'Sales Rep',
                    'Invoice Total',
                    'Invoice Discount',
                    'Invoice Credit',
                    'Amount  Paid',
                    'Balance  due',
                    'Professional Body',
                    'Subscription Plan Type'
                ]);

                foreach ($invoices as $invoice) {
                    $item_description = ($invoice->items->count())?implode(",",$invoice->items->pluck('name')->toArray()):"";
                    $sheet->appendRow([
                        $invoice->user->first_name.' '.$invoice->user->last_name,
                        strtolower($invoice->user->email),
                        $invoice->user->cell,
                        date_format($invoice->created_at, 'Y-m-d'),
                        $invoice->reference,
                        $invoice->type,
                        $item_description,
                        ($invoice->paid ? 'Yes' : 'No'),
                        ($invoice->status),
                        $invoice->user->subscription('cpd') ? date_format(Carbon::parse(($invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : ' - ',
                        ($invoice->user->subscribed('cpd') ? (($invoice->user->subscription('cpd')->SalesAgent() ? $invoice->user->subscription('cpd')->SalesAgent()->name : '') ?: 'N/A') : 'N/A'),
                        ($invoice ? $invoice->total : '0'),
                        ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                        ($invoice ? (($invoice->status == 'credit noted' || $invoice->status == 'cancelled') ? $invoice->transactions->where('type', 'credit')->sum('amount') : 0) : '0'),
                        ($invoice ? (($invoice->status == 'paid') ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0') : '0'),
                        ($invoice ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount') : '0'),
                        ($invoice->user->body ? $invoice->user->body->title : ($invoice->user->specified_body ?: "N/A")),
                        // $invoice->user->subscription('cpd') ? $invoice->user->subscription('cpd')->plan->interval : 'N/A',
                        $this->getPlanType($invoice),

                    ]);
                }
            });

        })->export('xls');

    }

    public function getPlanType($invoice) {

        $plan = 'N/A';
        if($invoice->items->count()) {
            $item = $invoice->items[0];
            if($item->item_id && $item->item_type) {
                $product = $item->productable;
                if($product) {
                    
                    if(get_class($product) == 'App\Models\Course') {
                        
                        if($item->price > 2900 && $item->price > $product->monthly_enrollment_fee) {
                            $plan = 'year';
                        }
                        else {
                            $plan = 'month';
                        }

                    }
                    else if($product->interval) {
                        $plan = $product->interval;
                    }
                }
            }
        }
        return $plan;

    }

    /*    
    public function downloadCpdCoursesDashboard($months, $data, $totalSubscribers) {

        return Excel::create('CPD and courses summary'.Carbon::now()->timestamp, function ($excel) use ($months, $data, $totalSubscribers) {
                
            $excel->sheet('sheet', function ($sheet) use ($months, $data, $totalSubscribers) {

                // CPD SUBS - Month headings
                $topRow = ['CPD SUBS'];
                foreach ($months as $month) {
                    $topRow[] = $month['name'];
                }
                $sheet->appendRow($topRow);

                // DEBIT ORDERS:
                $debit_orders = $data['cpds']['debit_orders'];
                $sheet->appendRow([
                    'DEBIT ORDERS:'
                ]);

                // PAID
                $row = ['PAID'];
                foreach ($months as $month) {
                    $row[] = isset($debit_orders[$month['key']])? 'R '.$debit_orders[$month['key']]['paid']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

                // UNPAID
                $row = ['UNPAID'];
                foreach ($months as $month) {
                    $row[] = isset($debit_orders[$month['key']])? 'R '.$debit_orders[$month['key']]['unpaid']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

                // CANCELLED
                $row = ['CANCELLED'];
                foreach ($months as $month) {
                    $row[] = isset($debit_orders[$month['key']])? 'R '.$debit_orders[$month['key']]['cancelled']['amount'] : 'R0';
                }
                $sheet->appendRow($row);


                // ONCE OFF:
                $once_off = $data['cpds']['once_off'];
                $sheet->appendRow(['']);
                $sheet->appendRow([
                    'ONCE OFF:'
                ]);

                // PAID
                $row = ['PAID'];
                foreach ($months as $month) {
                    $row[] = isset($once_off[$month['key']])? 'R '.$once_off[$month['key']]['paid']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

                // UNPAID
                $row = ['UNPAID'];
                foreach ($months as $month) {
                    $row[] = isset($once_off[$month['key']])? 'R '.$once_off[$month['key']]['unpaid']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

                // CANCELLED
                $row = ['CANCELLED'];
                foreach ($months as $month) {
                    $row[] = isset($once_off[$month['key']])? 'R '.$once_off[$month['key']]['cancelled']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

                // AMOUNT OF CPD SUBSCRIBERS
                $sheet->appendRow(['']);
                $sheet->appendRow([
                    'AMOUNT OF CPD SUBSCRIBERS',
                    $totalSubscribers
                ]);
                $sheet->appendRow(['']);
                $sheet->appendRow(['']);

                // COURSES
                $sheet->appendRow(['COURSES']);

                // DEBIT ORDERS:
                $debit_orders = $data['courses']['debit_orders'];
                $sheet->appendRow(['DEBIT ORDERS:']);

                // PAID
                $row = ['PAID'];
                foreach ($months as $month) {
                    $row[] = isset($debit_orders[$month['key']])? 'R '.$debit_orders[$month['key']]['paid']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

                // UNPAID
                $row = ['UNPAID'];
                foreach ($months as $month) {
                    $row[] = isset($debit_orders[$month['key']])? 'R '.$debit_orders[$month['key']]['unpaid']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

                // CANCELLED
                $row = ['CANCELLED'];
                foreach ($months as $month) {
                    $row[] = isset($debit_orders[$month['key']])? 'R '.$debit_orders[$month['key']]['cancelled']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

                // ONCE OFF:
                $once_off = $data['courses']['once_off'];
                $sheet->appendRow(['']);
                $sheet->appendRow([
                    'ONCE OFF:'
                ]);

                // PAID
                $row = ['PAID'];
                foreach ($months as $month) {
                    $row[] = isset($once_off[$month['key']])? 'R '.$once_off[$month['key']]['paid']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

                // UNPAID
                $row = ['UNPAID'];
                foreach ($months as $month) {
                    $row[] = isset($once_off[$month['key']])? 'R '.$once_off[$month['key']]['unpaid']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

                // CANCELLED
                $row = ['CANCELLED'];
                foreach ($months as $month) {
                    $row[] = isset($once_off[$month['key']])? 'R '.$once_off[$month['key']]['cancelled']['amount'] : 'R0';
                }
                $sheet->appendRow($row);

            });
        })->export('xls');

    }
    */


}
