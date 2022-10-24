<?php

namespace App\Http\Controllers\Admin;

use App\Billing\Invoice;
use App\Billing\Transaction;
use App\Body;
use App\Http\Controllers\Controller;
use App\Jobs\SaveUnpaidsReportToSystem;
use App\Note;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;
use App\UnpaidInvoiceExport;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\SponsorFormSubmission;
use App\ExtractReport;
use App\CourseProcess;
use App\SponsorList;
use Yajra\Datatables\Facades\Datatables;
use App\CourseAdresses;
use App\Models\Course;
use App\ActivityLog;
use App\AppEvents\Ticket;
use App\Video;
use Illuminate\Support\Facades\DB;
use DateTime;

class ReportController extends Controller
{
    public function getIncome()
    {
        return view('admin.reports.payments.income');
    }

    public function postIncome(Request $request)
    {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();
        return $data = Transaction::whereBetween('date', [$from, $to])->orderBy('date')->has('user')->get();
    }

    public function postExport(Request $request)
    {
        $file = Excel::create($request->file_name, function ($excel) use ($request) {

            foreach ($request->only('discounts', 'cancellations', 'invoices', 'payments') as $key => $value) {

                $excel->sheet(ucfirst($key), function ($sheet) use ($value) {

                    $sheet->appendRow(['Date', 'Status', 'Reference', 'Description', 'Amount']);

                    foreach ($value as $data) {
                        $sheet->appendRow([
                            Carbon::parse($data['date'])->format('d-m-Y'),
                            $data['status'],
                            $data['ref'],
                            $data['description'],
                            $data['amount'],
                        ]);
                    }
                });

            }

        })->store('xls', public_path('exports'));

        return response()->json([
            'file' => "/exports/{$request->file_name}.xls"
        ]);
    }

    public function getLedger()
    {
        $transactions = Transaction::with('invoice')->has('user')->orderBy('date', 'asc')->paginate(15);

        return view('admin.reports.payments.ledger', compact('transactions'));
    }

    public function getDebtors()
    {
        $data = Transaction::with('user', 'invoice')->thisYear()->tillNow()->orderBy('date')->get();
        $transactions = $data->reject(function ($transaction) {
            if (!$transaction->user) {
                return $transaction;
            }
        });

        return view('admin.reports.payments.debtors', compact('transactions'));
    }

    public function postDebtors(Request $request)
    {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

        $data = Transaction::with('user')->where('date', '<=', $to)->orderBy('date')->get();
        $transactions = $data->reject(function ($transaction) {
            if (!$transaction->user) {
                return $transaction;
            }
        });

        return view('admin.reports.payments.debtors_results', compact('transactions', 'from', 'to'));
    }

    public function exportDebtors(Request $request)
    {
        $to = Carbon::parse($request->to)->endOfDay();

        $transactions = Transaction::with('user')->where('date', '<=', $to)->orderBy('date')->get();

        foreach ($transactions->groupBy('category') as $category => $transactions) {
            foreach ($transactions->groupBy('user_id') as $user => $transactions) {
                $user = User::find($user);
                $total = $transactions->where('type', 'debit')->sum('amount') - $transactions->where('type', 'credit')->sum('amount');

                if ($user) {
                    $data[$category][] = [
                        $user->id,
                        $user->first_name,
                        $user->last_name,
                        $user->email,
                        number_format($total, 2, ".", "")
                    ];
                }
            }
        }

        Excel::create($to->format('Y-m-d') . ' Debors Report', function ($excel) use ($data) {
            foreach ($data as $key => $users) {
                $excel->sheet(ucfirst($key), function ($sheet) use ($users) {
                    $sheet->appendRow([
                        'User ID',
                        'First Name',
                        'Last Name',
                        'Email',
                        'Balance'
                    ]);

                    foreach ($users as $user) {
                        $sheet->appendRow($user);
                    }

                });
            }
        })->export('xls');
    }

    public function outstanding()
    {
        
        $users = User::select('users.*', 'invoices.created_at','invoices.total','invoices.reference','invoices.status', DB::raw('count(invoices.id) as invoice_count'))
            ->leftJoin('invoices', 'invoices.user_id', '=', 'users.id')
            ->where(function ($query) {
                $query->whereNull('users.payment_method')
                        ->orWhere('users.payment_method', '!=', 'debit_order');
            })
            ->where(function ($query) {
                $query->where('invoices.status', 'unpaid')
                    ->orWhere('invoices.status', 'partial');
            })
            ->where('invoices.paid', false)
            ->whereNull('invoices.deleted_at')
           ->whereRaw("((SELECT IFNULL(sum(amount),0) FROM `transactions` WHERE invoice_id=invoices.id and deleted_at is null and type='debit') - (SELECT IFNULL(sum(amount),0) FROM `transactions` WHERE invoice_id=invoices.id and deleted_at is null and type='credit'))>0")
            ->groupBy('users.id')
            ->get();
     

        return view('admin.reports.payments.outstanding', compact('users'));
    }

    public function exportOustanding(Request $request)
    {
        UnpaidInvoiceExport::create([
            'user_id' => auth()->user()->id,
            'processed' => false
        ]);

        alert()->success('Due to the size of your report, your report will be sent via email within the next 15 - 20 minutes with a download link.', 'Success!')->persistent('Thank you');
        return back();
    }

    public function claim_invoices()
    {
        return view('admin.invoices.claim.index');
    }

    public function claim_invoices_post(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = Excel::load($request->file('file'), function ($reader) {
            })->get();

            if (count($file) > 0) {
                $failed = 0;
                $success = 0;

                foreach ($file as $invoices) {
                    $data = $invoices->toArray();

                    if (empty($data['reference'])) {
                        $failed++;
                        continue;
                    }

                    $invoice = Invoice::where('reference', $data['reference'])->first();
                    if ($invoice) {
                        $note = $invoice->note;
                        if ($note) {
                            if ($request->com_claimed == 'yes') {
                                $note->update(['commision_claimed' => true]);
                                $note->save();
                            } else {
                                $note->update(['commision_claimed' => false]);
                                $note->save();
                            }
                            $success++;
                        } else {
                            $failed++;
                            continue;
                        }
                    } else {
                        $failed++;
                        continue;
                    }
                }

                if ($success == 0) {
                    alert()->error("Something went wrong, we were not able to read any of the invoices that you have listed, please insure that your file is .XLS and that you have an 'reference' column", "Whoops!")->persistent('Close');
                    return redirect()->back();
                } else {
                    alert()->success("Your invoices has been processed, {$success} invoices was marked successfully and {$failed} could not be found.", 'Success!')->persistent('Close');
                    return redirect()->back();
                }
            } else {
                alert()->error('No invoices found in uploaded file, please try again', 'Error!')->persistent('Close');
                return redirect()->back();
            }
        }
    }

    public function customDebtTransactions()
    {
        return view('admin.reports.debt.custom.index');
    }

    public function exportCustomDebtTransactionsExport(Request $request)
    {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

        $transactions = Transaction::with('invoice', 'user', 'user.profile')->whereBetween('date', [$from, $to])->orderBy('date')->get();

        $transactions->filter(function ($transaction) {
            if ($transaction->user) {
                return $transaction;
            }
        });

        Excel::create('Transactions', function ($excel) use ($transactions) {
            $excel->sheet('sheet', function ($sheet) use ($transactions) {
                $sheet->appendRow([
                    'User_id',
                    'Date',
                    'Type',
                    'Reference',
                    'Description',
                    'Category',
                    'Debit',
                    'Credit',
                    'Balance'
                ]);

                $balance = 0;
                foreach ($transactions as $transaction) {
                    switch ($transaction->type) {
                        case 'debit':
                            $balance += $transaction->amount;
                            break;
                        case 'credit':
                            $balance -= $transaction->amount;
                            break;
                    }

                    if ($transaction->user) {
                        $sheet->appendRow([
                            $transaction->user->id,
                            $transaction->date->format('d-m-Y'),
                            $transaction->display_type,
                            $transaction->ref,
                            $transaction->description,
                            ucfirst($transaction->category),
                            ($transaction->type == 'debit') ? $transaction->amount : '-',
                            ($transaction->type == 'credit') ? $transaction->amount : '-',
                            $balance
                        ]);
                    }
                }
            });
        })->export('xls');
    }

    public function credited_invoices()
    {
        return view('admin.reports.invoices.credit');
    }

    public function post_credited_invoices_export(Request $request)
    {
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $invoices = Invoice::with('transactions', 'user')
            ->where('status', 'paid')
            ->where('paid', '1')
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $credited = $invoices->reject(function ($invoice) {
            $credits = $invoice->transactions->where('type', 'credit')->sum('amount');
            $debits = $invoice->transactions->where('type', 'debit')->sum('amount');
            return $debits - $credits >= 0;
        });

        Excel::create('Invoices in credit ' . date_format($from, 'Y-m-d') . ' ' . 'to' . ' ' . date_format($to, 'Y-m-d') . ' ', function ($excel) use ($credited) {
            $excel->sheet('Outstanding Invoices', function ($sheet) use ($credited) {
                $sheet->appendRow([
                    'Invoice Date',
                    'Reference',
                    'User',
                    'Email',
                    'Discount',
                    'Total',
                    'Total Due',
                    'Outstanding Invoices'
                ]);

                foreach ($credited as $invoice) {
                    $sheet->appendRow([
                        $invoice->created_at,
                        '#' . $invoice->reference,
                        $invoice->user->first_name . ' ' . $invoice->user->last_name,
                        $invoice->user->email,
                        $invoice->transactions->where('tags', 'Discount')->sum('amount'),
                        $invoice->total,
                        $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount'),
                        ($invoice->user ? count($invoice->user->invoices->where('status', 'unpaid')) : "-")
                    ]);
                }

            });
        })->export('xls');

        return back();
    }


    public function reward_export()
    {
        $product = SponsorList::get()->pluck('name','slug')->toArray();
        // $product = [];
        // $product['acts']='acts';
        // $product['aon']='aon';
        // $product['AON PI']='AON PI';
        // $product['Draftworx']='Draftworx';
        // $product['quickbooks']='quickbooks';
        // $product['SAIBA']='SAIBA';
        // $product['Sanlam']='Sanlam';
        // $product['santam']='santam';
        $product['Webinars']='Webinars';

        return view('admin.reports.rewards',compact('product'));
    }

    public function post_reward_export_export(Request $request)
    {
        $product = $request->product;
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);

        $SponsorFormSubmission = SponsorFormSubmission::where('product', $product)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        Excel::create('Sponsor Data Of '.$product.' From  ' . date_format($from, 'Y-m-d') . ' ' . 'to' . ' ' . date_format($to, 'Y-m-d') . ' ', function ($excel) use ($SponsorFormSubmission) {
            $excel->sheet('Sponsor Data', function ($sheet) use ($SponsorFormSubmission) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Contact Number',
                    'Product',
                    'Age',
                    'Accountant Type',
                    'Gender',
                    'Race',
                    'Level of Management',
                    'Income',
                    'Registered Professional Accountancy Body',
                    'Professional Body Name',
                    'Other Professional Body Name',
                    'Do You Adhere To A Code Of Conduct',
                    'Are your cpd hours up to date',
                    'Do you use engagement letters',
                    'Latest technical knowledge or library',
                    'Do you have the required infrastructure',
                    'Do you or your firm perform reviews of all work',
                    'Do you apply relevant auditing and assurance standards',
                    'Do you use the latest technology and software'
                ]);

                foreach ($SponsorFormSubmission as $sponsor) {
                    $sheet->appendRow([
                        $sponsor->name,
                        $sponsor->email,
                        $sponsor->contact_number,
                        $sponsor->product,
                        $sponsor->age,
                        $sponsor->accountant_type,
                        $sponsor->gender,
                        $sponsor->race,
                        $sponsor->level_of_management,
                        $sponsor->income,
                        ($sponsor->registered_professional_accountancy_body==0)?'No':'Yes',
                        $sponsor->professional_body_name,
                        $sponsor->other_professional_body_name,
                        ($sponsor->do_you_adhere_to_a_code_of_conduct==0)?'No':'Yes',
                        ($sponsor->are_your_cpd_hours_up_to_date==0)?'No':'Yes',
                        ($sponsor->do_you_use_engagement_letters==0)?'No':'Yes',
                        ($sponsor->latest_technical_knowledge_or_library==0)?'No':'Yes',
                        ($sponsor->do_you_have_the_required_infrastructure==0)?'No':'Yes',
                        ($sponsor->do_you_or_your_firm_perform_reviews_of_all_work==0)?'No':'Yes',
                        ($sponsor->do_you_apply_relevant_auditing_and_assurance_standards==0)?'No':'Yes',
                        ($sponsor->do_you_use_the_latest_technology_and_software==0)?'No':'Yes'
                    ]);
                }

            });
        })->export('xls');

        return back();
    }

    public function cpd_members()
    {
        return view('admin.reports.cpd.index');
    }

    public function export_cpd_members(Request $request)
    {
        /*
         * Filter the subscriptions.
         */
        $subscriptions = Subscription::whereBetween('created_at', [Carbon::parse($request->from), Carbon::parse($request->to)])
            ->get()->filter(function ($subscription) {
                if ($subscription->active()) {
                    return $subscription;
                }
            });

        /*
         * Filter the subscriptions with cpd hours.
         */
        $filtered = $subscriptions->filter(function ($subscription) use ($request) {
            if ($request['cpd_hours'] > 0) {
                if ($subscription->user->cpds->sum('hours') >= $request['cpd_hours']) {
                    return $subscription;
                }
            } else {
                return $subscription;
            }
        });

        /*
         * Let's filter again the filtered but this time by status.
         */
        $filteredStatus = $filtered->filter(function ($subscription) use ($request) {
            if ($request->has('status')) {
                if ($subscription->user->status == $request['status']) {
                    return $subscription;
                }
            } else {
                return $subscription;
            }
        });

        Excel::create('CPD Members Report', function ($excel) use ($filteredStatus) {
            $excel->sheet('Outstanding Invoices', function ($sheet) use ($filteredStatus) {
                $sheet->appendRow([
                    'User',
                    'ID Number',
                    'Email',
                    'Contact Number',
                    'CPD Plan',
                    'CPD Hours',
                ]);

                foreach ($filteredStatus as $subscription) {
                    $sheet->appendRow([
                        ucfirst($subscription->user->full_name()),
                        ($subscription->user->id_number) ?: "N/A",
                        ($subscription->user->email) ?: "N/A",
                        ($subscription->user->cell) ?: "N/A",
                        ($subscription->plan->name) ?: "N/A",
                        $subscription->user->cpds->sum('hours')
                    ]);
                }

            });
        })->export('xls');
    }

    public function download_course()
    {
        return view('admin.reports.invoices.download_course');
    }

    public function post_download_course(Request $request)
    {
        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();

        $CourseProcess = ActivityLog::with('productable')->where('model', CourseProcess::class)->whereIn('action',['download_brochure'])->whereBetween('created_at', [$from, $to])->orderby('created_at','desc')->get();
        
        return Excel::create('Course Process from'.date_format($from, 'Y-m-d').' - '.date_format($to, 'Y-m-d').Carbon::now()->timestamp, function ($excel) use ($CourseProcess) {
            $excel->sheet('sheet', function ($sheet) use ($CourseProcess) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Contact No',
                    'Course Name',
                    'User register in System',
                    'Download Time'
                ]);

                foreach ($CourseProcess as $course) {
                    $model = $course->productable()->first();
                    $data = $course->activityData();
                    if($model){
                    $sheet->appendRow([
                        $model->first_name,
                        $model->last_name,
                        $model->email,
                        $model->mobile,
                        ($data)?$data->course_name :"",
                        ($model->user)?'Yes': 'No',
                        $course->created_at
                    ]);
                    }
                }
            });
        })->export('xls');
    }


    public function talk_to_human()
    {
        return view('admin.reports.invoices.talk_to_human');
    }

    public function post_talk_to_human(Request $request)
    {
        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();

        $CourseProcess = CourseProcess::with('user','course')->whereBetween('created_at', [$from, $to])->where('type','talk_to_human')->get();
        return Excel::create('Talk To Human from'.date_format($from, 'Y-m-d').' - '.date_format($to, 'Y-m-d').Carbon::now()->timestamp, function ($excel) use ($CourseProcess) {
            $excel->sheet('sheet', function ($sheet) use ($CourseProcess) {
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Contact No',
                    'Course Name',
                    'User register in System',
                    'Download Time'
                ]);

                foreach ($CourseProcess as $course) {
                    $sheet->appendRow([
                        $course->first_name,
                        $course->last_name,
                        $course->email,
                        $course->mobile,
                        ($course->course)?$course->course->title:'',
                        ($course->user)?'Yes': 'No',
                        $course->created_at
                    ]);
                }
            });
        })->export('xls');
    }

    public function extract_invoices()
    {
        return view('admin.reports.invoices.extract');
    }

    public function post_extract_invoices(Request $request)
    {
        if(env('APP_THEME') == 'taxfaculty'){
            return $this->post_extract_invoices_ttf($request);
        }
        if (strtolower($request->method()) == 'post' && $request->submit == 'export_report') {
            return $this->reportData($request,'post_extract_invoices');
        }

        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();

        $invoices = Invoice::with('user','items')->select('invoices.*','user_notes.logged_by')->leftjoin('user_notes', 'invoices.id', '=', 'user_notes.invoice_id')->whereBetween('invoices.created_at', [$from, $to])->groupBy('invoices.id')->get();
        return Excel::create('Invoices from'.date_format($from, 'Y-m-d').' - '.date_format($to, 'Y-m-d').Carbon::now()->timestamp, function ($excel) use ($invoices) {
            $excel->sheet('sheet', function ($sheet) use ($invoices) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Cell',
                    'Date',
                    'Date Settles',
                    'Credit Date',
                    'Discount Date',
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
                    'Invoice VAT',
                    'Amount  Paid',
                    'Balance  due',
                    'Professional Body',
                    'Subscription Plan Type',
                    'Type of Subscription Invoice'
                ]);

                foreach ($invoices as $invoice) {
                    $item_description = ($invoice->items->count())?implode(",",$invoice->items->pluck('name')->toArray()):"";
                    $balance_due = 0;
                    if(isset($invoice)) {
                        $debit =  $invoice->transactions->where('type', 'debit')->sum('amount');
                        $credit =  $invoice->transactions->where('type', 'credit')->sum('amount');

                        $credit > $debit ? $balance_due =  0 : $balance_due = $debit - $credit;
                    }
                    $credit_transaction = $invoice->transactions->where('type', 'credit')->first();
                    $discount_transaction = $invoice->transactions->where('tags', 'Discount')->first();
                    $sheet->appendRow([
                        $invoice->user->first_name.' '.$invoice->user->last_name,
                        strtolower($invoice->user->email),
                        $invoice->user->cell,
                        date_format($invoice->created_at, 'Y-m-d'),
                        (isset($invoice->date_settled) && (strtotime($invoice->date_settled)>0)) ? date_format($invoice->date_settled, 'Y-m-d') : 'N/A',
                        isset($credit_transaction) ? date_format($credit_transaction->created_at, 'Y-m-d') : 'N/A',
                        isset($discount_transaction) ? date_format($discount_transaction->created_at, 'Y-m-d') : 'N/A',
                        $invoice->reference,
                        $invoice->type,
                        $item_description,
                        ($invoice->paid ? 'Yes' : 'No'),
                        ($invoice->status),
                        $invoice->user->subscription('cpd') ? date_format(Carbon::parse(($invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : ' - ',
                        $invoice->logged_by,
                        ($invoice ? $invoice->total : '0'),
                        ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                        ($invoice ? (($invoice->status == 'credit noted' || $invoice->status == 'cancelled') ?  $invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : 0) : '0'),
                        $invoice->vat_rate."%",
                        ($invoice ? (($invoice->status == 'paid') ? $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0') : '0'),
                        ($invoice ? $balance_due : '0'),
                        ($invoice->user->body ? $invoice->user->body->title : ($invoice->user->specified_body ?: "N/A")),
                        $invoice->user->subscription('cpd') ? $invoice->user->subscription('cpd')->plan->interval : 'N/A',
                        ($invoice->type=='subscription')? ($invoice->isRecurring()?'Recurring Subscription Invoice':'New Subscription Invoice'):''
                    ]);
                }
            });
        });

    }
    public function post_extract_invoices_ttf(Request $request)
    {
        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();

        $subscription_plans = Plan::where('invoice_description','NOT LIKE','%Course:%')->where('is_practice','1')->get()->pluck('id')->toArray();
        $invoices = Invoice::with('user','items','transactions')->select('invoices.*','user_notes.logged_by')->leftjoin('user_notes', 'invoices.id', '=', 'user_notes.invoice_id')->whereBetween('invoices.created_at', [$from, $to])->groupBy('invoices.id')->get();
        Excel::create('Invoices from' . date_format($from, 'Y-m-d') . ' - ' . date_format($to, 'Y-m-d'), function ($excel) use ($invoices,$subscription_plans) {
            $excel->sheet('sheet', function ($sheet) use ($invoices,$subscription_plans) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Cell',
                    'Date',
                    'Date Settled',
                    'Date Credited',
                    'Date Discounted',
                    'Invoice Number',
                    'type',
                    'Description',
                    // 'Paid',
                    'Paid Status',
                    // 'User Since',
                    'Sales Rep',
                    'Invoice Total',
                    'Donation',
                    'Invoice Discount',
                    'Invoice Credit',
                    'Invoice VAT',
                    'Amount  Paid',
                    'Balance  due',
                    'Professional Body',
                    'Subscription Plan Type'
                ]);

                foreach ($invoices as $invoice) {

                    $credit_transactions = $transactions = $invoice->transactions->filter(function ($transaction){
                        return ($transaction->display_type == 'Credit Note' && $transaction->tags != 'Discount');
                    });

                    $item_description = ($invoice->items->count())?implode(",",$invoice->items->pluck('name')->toArray()):"";
                    $balance_due = 0;
                    if(isset($invoice)) {
                        $debit =  $invoice->transactions->where('type', 'debit')->sum('amount');
                        $credit =  $invoice->transactions->where('type', 'credit')->sum('amount');

                        $balance_due = $debit - $credit;
                    }
                    $credit_transaction = $invoice->transactions()->where('type', 'credit')->orderBy('id','desc')->first();
                    $discount_transaction = $invoice->transactions->where('tags', 'Discount')->first();
                    $amountPaid = ($invoice->transactions->where('type', 'credit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') - $invoice->transactions->where('tags', 'Adjustment')->sum('amount') - 
                    $invoice->transactions->where('tags', 'Cancellation')->sum('amount'));
                    $sheet->appendRow([
                        $invoice->user->first_name.' '.$invoice->user->last_name,
                        strtolower($invoice->user->email),
                        $invoice->user->cell,
                        date_format($invoice->created_at, 'Y-m-d'),
                        (isset($credit_transaction) && (strtotime($credit_transaction->date)>0)) ? date_format($credit_transaction->date, 'Y-m-d') : 'N/A',
                        isset($credit_transaction) ? date_format($credit_transaction->created_at, 'Y-m-d') : 'N/A',
                        isset($discount_transaction) ? date_format($discount_transaction->created_at, 'Y-m-d') : 'N/A',
                        $invoice->reference,
                        $invoice->type == 'subscription' && isPractice($invoice,$subscription_plans) ? 'practice' : $invoice->type,
                        $item_description,
                        // ($invoice->paid ? 'Yes' : 'No'),
                        (($invoice->cancelled)?"credit noted":$invoice->status),
                        // $invoice->user->subscription('cpd') ? date_format(Carbon::parse(($invoice->user->subscription('cpd')->created_at)), 'Y-m-d') : ' - ',
                        $invoice->logged_by,
                        ($invoice ? (($invoice->total<99999999.99)?$invoice->total:$invoice->transactions->where('type', 'debit')->sum('amount') ) : '0'),
                        ($invoice ? $invoice->donation : '0'),
                        ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
                        ($invoice ? (($invoice->status == 'credit noted' || $invoice->cancelled) ?  $credit_transactions->sum('amount') : 0) : '0'),
                        $invoice->vat_rate."%",
                        ($invoice ? (($amountPaid>=0)?$amountPaid:0) : '0'),
                        ($invoice ? $balance_due : '0'),
                        ($invoice->user->body ? $invoice->user->body->title : ($invoice->user->specified_body ?: "N/A")),
                        $invoice->user->subscription('cpd') ? $invoice->user->subscription('cpd')->plan->interval : 'N/A',

                    ]);
                }
            });
        })->export('xls');

        alert()->success('Your report was extracted', 'Success!');
        return redirect()->back();
    }

    public function agent_report()
    {
        $users = User::has('roles')->get()->filter(function ($user) {
            if ($user->hasRole('sales')) {
                return $user;
            };
        });

        return view('admin.reports.sales.index', compact('users'));
    }

    public function export_agent_report(Request $request)
    {

        if (strtolower($request->method()) == 'post' && $request->submit == 'export_report' && env('APP_THEME') == 'saaa') {
            return $this->reportData($request,'export_agent_report');
        }
        $name = "";
        if($request['agent']>0)
        {
            $agent = User::find($request['agent']);
            $name = $agent->first_name.' '.$agent->last_name;
        }else{
            $name = $request['agent'];
        }

        if ($request['category'] === 'subscription_upgrade_procedure') {
            $notes = Note::select('user_notes.*')->with('invoice')->leftjoin('invoices', 'invoices.id', '=', 'user_notes.invoice_id')->whereIn('user_notes.type', ['subscription_upgrade', 'subscription_upgrade_procedure', 'new_subscription', 'recurring_subscription'])

                ->where('user_notes.logged_by', $name)
                ->whereBetween('user_notes.created_at', [Carbon::parse($request['from'])->startOfDay(), Carbon::parse($request['to'])->endOfDay()])
                ->where(function ($query) use ($request) {
                    if ($request['commision_claimed']  != "") {
                        $query->where('commision_claimed', $request['commision_claimed']);
                    }
                })->where(function ($query) use ($request) {
                    if ($request['status'] != '') {
                        $query->where('invoices.status', $request['status']);
                    }
                })->get();
        } elseif ($request['category'] === 'store_items') {
            $notes = Note::select('user_notes.*')->with('invoice')->leftjoin('invoices', 'invoices.id', '=', 'user_notes.invoice_id')->whereIn('user_notes.type', ['store_items'])
                ->where('user_notes.logged_by', $name)
                ->whereBetween('user_notes.created_at', [Carbon::parse($request['from'])->startOfDay(), Carbon::parse($request['to'])->endOfDay()])
                ->where(function ($query) use ($request) {
                    if ($request['commision_claimed']  != "") {
                        $query->where('commision_claimed', $request['commision_claimed']);
                    }
                })->where(function ($query) use ($request) {
                    if ($request['status'] != '') {
                        $query->where('invoices.status', $request['status']);
                    }
                })->get();
        } elseif ($request['category'] === 'course_subscription') {
            $notes = Note::select('user_notes.*')->with('invoice')->leftjoin('invoices', 'invoices.id', '=', 'user_notes.invoice_id')->whereIn('user_notes.type', ['course_subscription'])
                ->where('user_notes.logged_by', $name)
                ->whereBetween('user_notes.created_at', [Carbon::parse($request['from'])->startOfDay(), Carbon::parse($request['to'])->endOfDay()])
                ->where(function ($query) use ($request) {
                    if ($request['commision_claimed']  != "") {
                        $query->where('commision_claimed', $request['commision_claimed']);
                    }
                })->where(function ($query) use ($request) {
                    if ($request['status'] != '') {
                        $query->where('invoices.status', $request['status']);
                    }
                })->get();
        } else {
            $notes = Note::select('user_notes.*')->with('invoice')->leftjoin('invoices', 'invoices.id', '=', 'user_notes.invoice_id')->where('user_notes.type', $request['category'])
                ->where('user_notes.logged_by', $name)
                ->whereBetween('user_notes.created_at', [Carbon::parse($request['from'])->startOfDay(), Carbon::parse($request['to'])->endOfDay()])
                ->where(function ($query) use ($request) {
                    if ($request['commision_claimed'] != "") {
                        $query->where('commision_claimed', $request['commision_claimed']);
                    }
                })
                ->where(function ($query) use ($request) {
                    if ($request['status'] != '') {
                        $query->where('invoices.status', $request['status']);
                    }
                })->get();
        }


        if (count($notes)) {
            if ($request['category'] == 'event_registration') {
                $events = $notes->filter(function ($note) {
                    if ($note->invoice && $note->invoice->ticket) {
                        return $note;
                    } elseif ($note->order && $note->order->ticket) {
                        return $note;
                    }
                });

                /*
                 * Events Export
                 */
               $excel = Excel::create('My Sales for ' . $agent->first_name . ' ' . $agent->last_name . ' Event Registrations from ' . $request->from . " - " . $request->to." ".Carbon::now()->timestamp, function ($excel) use ($events) {
                    $excel->sheet('Summary', function ($sheet) use ($events) {
                        $sheet->appendRow([
                            'Date',
                            'Member ID',
                            'Member',
                            'Invoice Reff',
                            'PO Reff',
                            'Status',
                            'Claimed',
                            'Total',
                            'Balance',
                            'Discount',
                            'Paid',
                            'Refund',
                            'Event',
                            'Venue'
                        ]);

                        /* * Summary Sheet Here! */
                        foreach ($events as $note) {
                            $sheet->appendRow([
                                date_format(Carbon::parse($note->created_at), 'Y-m-d'),
                                $note->user->id,
                                ucwords($note->user->first_name . ' ' . $note->user->last_name),
                                ($note->invoice ? $note->invoice->reference : "-"),
                                ($note->order ? $note->order->reference : "-"),
                                ($note->invoice ? $note->invoice->status : ($note->order ? $note->order->status : "-")),
                                ($note->commision_claimed ? "Yes" : "No"),
                                ($note->invoice ? $note->invoice->transactions->where('type', 'debit')->sum('amount') : ($note->order ? $note->order->total : "-")),
                                ($note->invoice ? $note->invoice->transactions->where('type', 'debit')->sum('amount') - $note->invoice->transactions->where('type', 'credits')->sum('amount') : ($note->order ? $note->order->balance : "-")),
                                ($note->invoice ? $note->invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : ($note->order ? $note->order->discount : "-")),
                                ($note->invoice ? $note->invoice->paid : ($note->order ? $note->order->paid : "-")),
                                ($note->invoice ? $note->invoice->refund : ($note->order ? $note->order->paid : "-")),
                                ($note->order ? $note->order->ticket->event->name : ($note->invoice->ticket ? $note->invoice->ticket->event->name : "")),
                                ($note->order ? $note->order->ticket->venue->name : ($note->invoice->ticket ? $note->invoice->ticket->venue->name : "")),
                            ]);
                        }
                    });
                    $char = \PHPExcel_Worksheet::getInvalidCharacters();

                    foreach ($events->groupBy('event') as $key => $value) {
                        $title = str_limit(strtolower(ucfirst($key)), 20);
						$title = str_replace($char, '', $title);
                        $excel->sheet($title, function ($sheet) use ($value, $events) {
                            /*
                             * Sheet Per Event
                             */
                            $sheet->appendRow(['Date', 'User_id', 'Member', 'Invoice', 'Order', 'Status', 'Claimed', 'Total', 'Balance', 'Discount', 'Paid', 'Event', 'Venue']);
                            foreach ($value as $note) {
                                $sheet->appendRow([
                                    date_format(Carbon::parse($note->created_at), 'Y-m-d'),
                                    $note->user->id,
                                    ucwords($note->user->first_name . ' ' . $note->user->last_name),
                                    ($note->invoice ? $note->invoice->reference : "-"),
                                    ($note->order ? $note->order->reference : "-"),
                                    ($note->invoice ? $note->invoice->status : ($note->order ? $note->order->status : "-")),
                                    ($note->commision_claimed ? "Yes" : "No"),
                                    ($note->invoice ? $note->invoice->transactions->where('type', 'debit')->sum('amount') : ($note->order ? $note->order->total : "-")),
                                    ($note->invoice ? $note->invoice->transactions->where('type', 'debit')->sum('amount') - $note->invoice->transactions->where('type', 'credits')->sum('amount') : ($note->order ? $note->order->balance : "-")),
                                    ($note->invoice ? $note->invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : ($note->order ? $note->order->discount : "-")),
                                    ($note->invoice ? $note->invoice->paid : ($note->order ? $note->order->paid : "-")),
                                    ($note->order ? $note->order->ticket->event->name : ($note->invoice->ticket ? $note->invoice->ticket->event->name : "")),
                                    ($note->order ? $note->order->ticket->venue->name : ($note->invoice->ticket ? $note->invoice->ticket->venue->name : "")),
                                ]);
                            }
                        });
                    }
                });

            } elseif ($request['category'] == 'store_items') {
                $excel = Excel::create( $agent->first_name . ' ' . $agent->last_name .' Store Orders from' . $request->from . " - " . $request->to." ".Carbon::now()->timestamp, function ($excel) use ($notes) {
                    $excel->sheet('All Store Items', function ($sheet) use ($notes) {
                        $sheet->appendRow(['Date', 'User_id', 'Member', 'Invoice', 'Order', 'Status', 'Claimed', 'Total', 'Balance', 'Discount', 'Paid']);
                        foreach ($notes as $store) {
                            if ($store->invoice) {
                                $sheet->appendRow([
                                    Carbon::parse($store['created_at'])->format('Y-m-d'),
                                    $store->user_id,
                                    $store->invoice->user->first_name . " " . $store->invoice->user->last_name,
                                    $store->invoice->reference,
                                    '-',
                                    $store->invoice->status,
                                    ($store->invoice->note->commision_claimed ? "Yes" : "No"),
                                    $store->invoice->total,
                                    $store->invoice->total - $store->invoice->transactions->where('type', 'credit')->sum('amount'),
                                    $store->invoice->transactions->where('display_type', 'Credit Note')->sum('amount'),
                                    $store->invoice->transactions->where('display_type', 'Payment')->sum('amount'),
                                ]);
                            } elseif ($store->order) {
                                $sheet->appendRow([
                                    Carbon::parse($store['created_at'])->format('d-m-Y'),
                                    $store->user_id,
                                    $store->order->user->first_name . " " . $store->order->user->last_name,
                                    '-',
                                    $store->order->reference,
                                    $store->order->status,
                                    ($store->order->note->commision_claimed ? "Yes" : "No"),
                                    $store->order->total,
                                    $store->order->balance,
                                    $store->order->discount,
                                    $store->order->payments->sum('amount'),
                                ]);
                            } else {
                            }
                        }
                    });
                });
            } elseif ($request['category'] == 'course_subscription') {
                $excel =  Excel::create( $agent->first_name . ' ' . $agent->last_name  .' Course subscriptions from' . $request->from . " - " . $request->to." ".Carbon::now()->timestamp, function ($excel) use ($notes) {
                    $excel->sheet('All Store Items', function ($sheet) use ($notes) {
                        $sheet->appendRow(['Date', 'User_id', 'Member', 'Invoice', 'Order', 'Status', 'Claimed', 'Total', 'Balance', 'Discount', 'Paid']);
                        foreach ($notes as $store) {
                            if ($store->invoice) {
                                $sheet->appendRow([
                                    Carbon::parse($store['created_at'])->format('Y-m-d'),
                                    $store->user_id,
                                    $store->invoice->user->first_name . " " . $store->invoice->user->last_name,
                                    $store->invoice->reference,
                                    '-',
                                    $store->invoice->status,
                                    ($store->invoice->note->commision_claimed ? "Yes" : "No"),
                                    $store->invoice->total,
                                    $store->invoice->total - $store->invoice->transactions->where('type', 'credit')->sum('amount'),
                                    $store->invoice->transactions->where('display_type', 'Credit Note')->sum('amount'),
                                    $store->invoice->transactions->where('display_type', 'Payment')->sum('amount'),
                                ]);
                            } elseif ($store->order) {
                                $sheet->appendRow([
                                    Carbon::parse($store['created_at'])->format('d-m-Y'),
                                    $store->user_id,
                                    $store->order->user->first_name . " " . $store->order->user->last_name,
                                    '-',
                                    $store->order->reference,
                                    $store->order->status,
                                    ($store->order->note->commision_claimed ? "Yes" : "No"),
                                    $store->order->total,
                                    $store->order->balance,
                                    $store->order->discount,
                                    $store->order->payments->sum('amount'),
                                ]);
                            } else {
                            }
                        }
                    });
                });
            } else {
                $subject =  $agent->first_name . ' ' . $agent->last_name ." CPD Subscription Registrations from ";
                if($request['category']=="webinars_on_demand")
                {
                    $subject =  $agent->first_name . ' ' . $agent->last_name ." Webinar Registrations from ";
                }
                $excel = Excel::create($subject . $request->from . " - " . $request->to." ".Carbon::now()->timestamp, function ($excel) use ($notes) {
                    $excel->sheet('ALL CPD SUBS', function ($sheet) use ($notes) {
                        $sheet->appendRow(['Date', 'User_id', 'Member', 'Invoice', 'Status', 'Claimed', 'Total', 'Balance', 'Discount', 'Paid']);
                        foreach ($notes as $subs) {
                            $sheet->appendRow([
                                Carbon::parse($subs['created_at'])->format('Y-m-d'),
                                $subs->user_id,
                                ($subs->user ? $subs->user->first_name . " " . $subs->user->last_name : "Cancelled"),
                                ($subs->invoice ? $subs->invoice->reference : "-"),
                                ($subs->invoice ? $subs->invoice->status : "-"),
                                ($subs->invoice ? ($subs->invoice->note->commision_claimed ? "Yes" : "No") : "-"),
                                ($subs->invoice ? $subs->invoice->total : "-"),
                                ($subs->invoice ? $subs->invoice->total - $subs->invoice->transactions->where('type', 'credit')->sum('amount') : ""),
                                ($subs->invoice ? $subs->invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : "-"),
                                ($subs->invoice ? $subs->invoice->transactions->where('display_type', 'Payment')->sum('amount') : "-"),
                            ]);
                        }
                    });

                    foreach ($notes->groupBy('type') as $key => $value) {
                        $excel->sheet(str_replace('_', ' ', ucfirst($key)), function ($sheet) use ($value) {
                            $sheet->appendRow(['Date', 'User_id', 'Member', 'Invoice', 'Status', 'Claimed', 'Total', 'Balance', 'Discount', 'Paid', 'Old Subscription', 'New Subscription']);
                            foreach ($value as $data) {
                                if ($data->upgrade)
                                    $sheet->appendRow([
                                        Carbon::parse($data['created_at'])->format('Y-m-d'),
                                        $data->user_id,
                                        ($data->user ? $data->user->first_name . " " . $data->user->last_name : "Cancelled"),
                                        ($data->invoice ? $data->invoice->reference : "-"),
                                        ($data->invoice ? $data->invoice->status : "-"),
                                        ($data->invoice ? ($data->invoice->note->commision_claimed ? "Yes" : "No") : "-"),
                                        ($data->invoice ? $data->invoice->total : "-"),
                                        ($data->invoice ? $data->invoice->total - $data->invoice->transactions->where('type', 'credit')->sum('amount') : "-"),
                                        ($data->invoice ? $data->invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : "-"),
                                        ($data->invoice ? $data->invoice->transactions->where('display_type', 'Payment')->sum('amount') : "="),
                                        Plan::find($data->upgrade->old_subscription_package)->name . " " . Plan::find($data->upgrade->old_subscription_package)->interval,
                                        Plan::find($data->upgrade->new_subscription_package)->name . " " . Plan::find($data->upgrade->new_subscription_package)->interval
                                    ]);
                                else {
                                    $sheet->appendRow([
                                        Carbon::parse($data['created_at'])->format('d-m-Y'),
                                        $data->user_id,
                                        ($data->user ? $data->user->first_name . " " . $data->user->last_name : "Cancelled"),
                                        ($data->invoice ? $data->invoice->reference : "-"),
                                        ($data->invoice ? $data->invoice->status : "-"),
                                        ($data->invoice ? ($data->invoice->note->commision_claimed ? "Yes" : "No") : "-"),
                                        ($data->invoice ? $data->invoice->total : "-"),
                                        ($data->invoice ? $data->invoice->total - $data->invoice->transactions->where('type', 'credit')->sum('amount') : "-"),
                                        ($data->invoice ? $data->invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : "-"),
                                        ($data->invoice ? $data->invoice->transactions->where('display_type', 'Payment')->sum('amount') : "-"),
                                        ' - ',
                                        ($data->user ? $data->user->subscribed('cpd') ? $data->user->subscription('cpd')->plan->name : "No Subscription Plan" : "Cancelled")
                                    ]);
                                }
                            }
                        });
                    }
                });
            }
            if(env('APP_THEME') == 'taxfaculty'){
                $excel->export('xls');
            }
            return $excel;
        } else {
            if(env('APP_THEME') == 'saaa'){
                return false;
            }
            alert()->error('No entries found for the date range specified', 'Please try again')->persistent('Close');
            return back();
        }
    }

     public function cpd_stats_all(Request $request)
    {
       
        if(env('APP_THEME') == 'taxfaculty'){
            set_time_limit(0); // 

            ini_set('memory_limit', '-1');
             $this->cpd_stats_all_report($request)->export('xls');
             
        }
        if (strtolower($request->method()) == 'get') {
            return $this->reportData($request,'cpd_stats_all_report');
        }
    }
    public function cpd_stats_all_report(Request $request)
    {
        $Body = Body::get();
        $now = Carbon::now();
        return Excel::create('Complete CPD Subscription Export'.Carbon::now()->timestamp, function ($excel) use ($Body, $now) {
            $excel->sheet('All System Users', function ($sheet) use ($Body, $now) {
                $sheet->appendRow([
                    'Created At',
                    'Subscription Date',
                    'Subscription Expiry Date',
                    'Active Subscription',
                    'Subscription Type',
                    'Previous Subscription',
                    'Account belongs to',
                    'First Name',
                    'Last Name',
                    'Email',
                    'Cell',
                    'Province',
                    'Status',
                    'Subscription Price',
                    'Professional Body',
                    'User Last Login'
                ]);
                User::with('subscriptions', 'subscriptions.agent','subscriptions.plan','addresses','activity_log','body')
                    ->select(
                        'users.*',
                        DB::raw("(SELECT plans.name FROM `subscriptions` LEFT JOIN plans ON subscriptions.plan_id = plans.id WHERE subscriptions.name = 'cpd' AND subscriptions.user_id = users.id AND subscriptions.deleted_at IS NULL AND( subscriptions.ends_at < '$now' OR subscriptions.canceled_at IS NOT NULL ) GROUP BY subscriptions.id ORDER BY subscriptions.created_at DESC LIMIT 1) as inactive_plan"),
                        DB::raw("(SELECT plans.name FROM `subscriptions` left join plans on subscriptions.plan_id = plans.id WHere subscriptions.name = 'cpd' and subscriptions.user_id = users.id AND subscriptions.deleted_at is not null group by subscriptions.id order by subscriptions.created_at DESC limit 1) as previous_plan")
                    )
                    ->chunk(800, function($users) use ($sheet,$Body) {
                
                        foreach ($users as $user) {
                            $active_subscription = $user->activeCPDSubscription();
                            $bodies = [];
                            $agent_name = "N/A";
                            $cpd_subscription = $user->subscriptions->where('name','cpd')->sortByDesc(function ($value) {
                                return $value->created_at->getTimestamp();
                            })
                            ->first();
                            if($cpd_subscription && $cpd_subscription->agent) {
                                $agent_name = ucfirst($cpd_subscription->agent->first_name) . ' ' . ucfirst($cpd_subscription->agent->last_name);
                            }

                            $previous_plan = "N/A";
                            if($user->inactive_plan) {
                                $previous_plan = ucwords(strtolower($user->inactive_plan));
                            } elseif($user->previous_plan) {
                                $previous_plan = ucwords(strtolower($user->previous_plan));
                            }
                            if ($user->additional_professional_bodies) {
                                foreach ($user->additional_professional_bodies as $bodyId) {
                                    $body = $Body->where('id',$bodyId)->first();
                                    array_push($bodies, $body->title);
                                }

                                if ($user->body) {
                                    array_push($bodies, $Body->where('id',$user->body->id)->first()->title);
                                }

                                $sheet->appendRow([
                                    date_format($user->created_at, 'Y-m-d'),
                                    $active_subscription ? date_format($active_subscription->starts_at, 'Y-m-d') : 'N/A',
                                    $active_subscription ? date_format($active_subscription->ends_at, 'Y-m-d') : 'N/A',
                                    $active_subscription ? ucwords(strtolower($active_subscription->plan->name)) : 'N/A',
                                    $active_subscription ? $active_subscription->plan->interval . 'ly' : 'N/A',
                                    $previous_plan,
                                    $agent_name,
                                    ucwords(strtolower($user->first_name)),
                                    ucwords(strtolower($user->last_name)),
                                    ucwords(strtolower($user->email)),
                                    ucwords(strtolower($user->cell)),
                                    $user->addresses->first() ? ucwords(strtolower($user->addresses->first()->province)) : 'N/A',
                                    ucwords(strtolower($user->status)),
                                    $cpd_subscription->plan->price,
                                    implode(', ', array_values(array_unique($bodies))),
                                    $user->lastLoginActivity() ? $user->lastLoginActivity()->created_at :'N/A'
                                ]);
                            } else {
                                if ($cpd_subscription) {
                                    $sheet->appendRow([
                                        date_format($user->created_at, 'Y-m-d'),
                                        $active_subscription ? date_format($active_subscription->starts_at, 'Y-m-d') : 'N/A',
                                        $active_subscription ? date_format($active_subscription->ends_at, 'Y-m-d') : 'N/A',
                                        $active_subscription ? ucwords(strtolower($active_subscription->plan->name)) : 'N/A',
                                        $active_subscription ? $active_subscription->plan->interval . 'ly' : 'N/A',
                                        $previous_plan,
                                        $agent_name,
                                        ucwords(strtolower($user->first_name)),
                                        ucwords(strtolower($user->last_name)),
                                        ucwords(strtolower($user->email)),
                                        ucwords(strtolower($user->cell)),
                                        $user->addresses->first() ? ucwords(strtolower($user->addresses->first()->province)) : 'N/A',
                                        ucwords(strtolower($user->status)),
                                        $cpd_subscription->plan->price,
                                        ($user->body ? $user->body->title : ($user->specified_body ?: "N/A")),
                                        $user->lastLoginActivity() ? $user->lastLoginActivity()->created_at :'N/A'
                                    ]);
                                }

                            }


                        }
                });
            });
        });
    }

    public function course_stats_all(Request $request)
    {
        set_time_limit(0); // 

        ini_set('memory_limit', '-1');
        $this->course_stats_all_report($request)->export('xls');
    }

    public function course_stats_all_report(Request $request)
    {
        $subscriptions = Subscription::where('name', 'course')->get();
        
        return Excel::create('Complete Subscription Courses Export'.Carbon::now()->timestamp, function ($excel) use ($subscriptions) {
            $excel->sheet('All Subscription Courses', function ($sheet) use ($subscriptions) {
                $sheet->appendRow([
                    'Subscription Date',
                    'Subscription Expiry Date',
                    'Course Name',
                    'ID Number',
                    'Street number and street name',
                    'Complex/Building',
                    'Suburb',
                    'City/Town',
                    'Province',
                    'Country',
                    'Postal code'
                ]);

                foreach ($subscriptions as $subscription) {
                    $plan = Plan::find($subscription->plan_id);
                    if($plan->interval == 'month') {
                        $course = Course::where('monthly_plan_id', $plan->id)->first();
                    } elseif($plan->interval == 'year') {
                        $course = Course::where('yearly_plan_id', $plan->id)->first();
                    }

                    $course_address = CourseAdresses::where('subscription_id', $subscription->id)->first();
                    $sheet->appendRow([
                        date_format($subscription->starts_at, 'Y-m-d'),
                        date_format($subscription->ends_at, 'Y-m-d'),
                        ($course ? $course->title : 'N/A'),
                        ($course_address ? $course_address->id_number : 'N/A'),
                        ($course_address ? $course_address->street_name : 'N/A'),
                        ($course_address ? $course_address->building : 'N/A'),
                        ($course_address ? $course_address->suburb : 'N/A'),
                        ($course_address ? $course_address->city : 'N/A'),
                        ($course_address ? $course_address->province : 'N/A'),
                        ($course_address ? $course_address->country : 'N/A'),
                        ($course_address ? $course_address->postal_code : 'N/A'),
                    ]);

                }
            });
        });
    }

    public function monthlyCPDReport()
    {
        $plans = Plan::where('interval', 'month')->where('id', '!=', '5')->where('id', '!=', '4')->where('id', '!=', '45')->get();

        Excel::create('Subscription Plan Stats', function ($excel) use ($plans) {
            foreach ($plans as $plan) {

                $excel->sheet(str_limit(preg_replace('/[^A-Za-z0-9\-]/', '', strtolower($plan->name)), 20), function ($sheet) use ($plan) {

                    $filtered = $plan->subscriptions()->active()->get()->groupBy(function ($subscription) {
                        return Carbon::parse($subscription->created_at)->format('F Y');
                    });

                    $months = collect();
                    foreach ($filtered as $key => $value) {
                        $systemValue = $plan->price;

                        $active = count($plan->subscriptions()->active()->where('created_at', '<=', Carbon::parse($key)->endOfMonth())->get());
                        $inactive = count($plan->subscriptions()->cancelled()->where('created_at', '<=', Carbon::parse($key)->endOfMonth())->get());
                        $total = count($plan->subscriptions()->where('created_at', '<=', Carbon::parse($key)->endOfMonth())->get());
                        $inMonth = count($plan->subscriptions()->where('created_at', '>=', Carbon::parse($key)->startOfMonth())->where('created_at', '<=', Carbon::parse($key)->endOfMonth())->get());

                        $months->push([
                            'Month' => $key,
                            'Subscription Price' => number_format($plan->price, 2),

                            'In Months' => $inMonth,
                            'Total Subscriptions' => ($total ?: "0"),
                            'Total Subscriptions Value' => number_format($systemValue * $total, 2),

                            'Cancelled Subscriptions' => ($inactive ?: "0"),
                            'Cancelled Subscriptions Value' => number_format($systemValue * $inactive, 2),

                            'Active Subscriptions' => $active,
                            'Active Subscriptions Value' => number_format($systemValue * $total, 2),
                        ]);
                    }

                    $newMonths = $months->sortBy(function ($month) {
                        return Carbon::parse($month['Month']);
                    });

                    $sheet->fromArray($newMonths);
                });
            }
        })->export('xls');
    }
    public function getProfessionalBody()
    { 
        $bodies = Body::all()->pluck('title','id');
        // dd($bodies);
        return view('admin.reports.professional_bodies', compact('bodies'));
    }
    public function postProfessionalBody(Request $request)
    {
        $from = Carbon::parse($request['from'])->startOfDay();
        $to = Carbon::parse($request['to'])->endOfDay();

        //   ->with(['subscription','invoice','body','plan'])
        $bodies = User::select('users.first_name as First_name', 'users.last_name as Last_name', 'users.email as Email', 'users.id_number as ID_number', 'invoices.created_at as Date_of_invoice', 'invoices.reference as Invoice_number', 'invoices.total as Invoice_amount', 'invoices.status as Invoice_status','invoices.type as Invoice_type', 'bodies.title as Professional_body', 'plans.name as subscription_name', 'plans.interval as interval')
            ->leftjoin('subscriptions', 'subscriptions.user_id', '=', 'users.id')->leftjoin('bodies', 'bodies.id', '=', 'users.body_id')
            ->leftjoin('invoices', 'users.id', '=', 'invoices.user_id')
            ->leftjoin('plans', 'plans.id', '=', 'subscriptions.plan_id')
            ->where('users.body_id', $request['bodies'])
            ->where('subscriptions.name', 'cpd')
            ->whereBetween('invoices.created_at', [$from, $to])
            ->get();
        

        Excel::create('Proffessional Body' . date_format($from, 'd F Y') . ' - ' . date_format($to, 'd F Y'), function ($excel) use ($bodies) {
            $excel->sheet('sheet', function ($sheet) use ($bodies) {
                $sheet->appendRow([
                    'First_name',
                    'Last_name',
                    'Email',
                    'ID_number',
                    'Date of invoice',
                    'Invoice number',
                    'Type',
                    'Invoice amount',
                    'Invoice status',
                    'Professional body',
                    'Subscription name',
                    'Interval',
                ]);

                foreach ($bodies as $body){
                    $sheet->appendRow([
                        // $body->First_name. ' '.$body->Last_name,
                        $body->First_name,
                        $body->Last_name,
                        strtolower($body->Email),
                        $body->ID_number,
                        $body->Date_of_invoice,
                        $body->Invoice_number,
                        $body->Invoice_type,
                        $body->Invoice_amount,
                        $body->Invoice_status,
                        $body->Professional_body,
                        $body->subscription_name,
                        $body->interval
                    ]);
                }
            });
        })->export('xls');

        alert()->success('Your report was extracted', 'Success!');
        return redirect()->back();

    }
    public function download_againg_report()
    {
        $days29 = collect();
        $days30to59 = collect();
        $days60to89 = collect();
        $days90to119 = collect();
        $days120 = collect();

        User::all()->each(function ($user) use ($days29, $days30to59, $days60to89, $days90to119, $days120) {
            foreach ($user->overdueInvoices() as $invoice) {
                if (Carbon::now()->endOfDay()->diffInDays($invoice->created_at) <= 29) {
                    $days29->push($invoice);
                } elseif (Carbon::now()->endOfDay()->diffInDays($invoice->created_at) >= 29 && Carbon::now()->endOfDay()->diffInDays($invoice->created_at) <= 59) {
                    $days30to59->push($invoice);
                } elseif (Carbon::now()->endOfDay()->diffInDays($invoice->created_at) >= 59 && Carbon::now()->endOfDay()->diffInDays($invoice->created_at) <= 89) {
                    $days60to89->push($invoice);
                } elseif (Carbon::now()->endOfDay()->diffInDays($invoice->created_at) >= 89 && Carbon::now()->endOfDay()->diffInDays($invoice->created_at) <= 119) {
                    $days90to119->push($invoice);
                } else {
                    $days120->push($invoice);
                }
            }
        });

        $all = collect([
            '0 - 29 Days' => $days29,
            '30 - 59 Days' => $days30to59,
            '60 - 89 Days' => $days60to89,
            '60 - 89 Days' => $days60to89,
            '90 - 119 Days' => $days90to119,
            '120 + Days' => $days120,
        ]);

        Excel::create('Aging Report', function ($excel) use ($all) {

            $excel->sheet('Summary', function ($sheet) use ($all) {
                $sheet->appendRow([
                    'Age',
                    'Total Invoices',
                    'Store Invoices',
                    'Event Invoices',
                    'Subscription Invoices',
                    'Total Balance',
                ]);

                foreach ($all as $key => $value) {
                    $sheet->appendRow([
                        $key,
                        count($value),
                        number_format($value->where('type', 'store')->sum('balance'), 2, ".", ""),
                        number_format($value->where('type', 'event')->sum('balance'), 2, ".", ""),
                        number_format($value->where('type', 'subscription')->sum('balance'), 2, ".", ""),
                        number_format($value->sum('balance'), 2, ".", "")
                    ]);
                }
            });

            foreach ($all as $key => $value) {
                $excel->sheet($key, function ($sheet) use ($key, $value) {
                    $sheet->appendRow([
                        'ID',
                        'Invoice Date',
                        'Reference',
                        'Name',
                        'Email',
                        'Discounts',
                        'Payments',
                        'CR',
                        'Total',
                        'Balance',
                    ]);

                    foreach ($value as $invoice) {
                        $sheet->appendRow([
                            $invoice->id,
                            date_format(Carbon::parse($invoice->created_at), 'Y-m-d'),
                            '#' . $invoice->reference,
                            $invoice->user->first_name . ' ' . $invoice->user->last_name,
                            $invoice->user->email,
                            number_format($invoice->transactions->where('tags', 'Discount')->sum('amount'), 2, ".", ""),
                            number_format($invoice->transactions->where('tags', 'Payment')->sum('amount'), 2, ".", ""),
                            number_format($invoice->transactions->where('tags', 'Cancellation')->sum('amount'), 2, ".", ""),
                            $invoice->total,
                            number_format($invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount'), 2, ".", ""),
                        ]);
                    }
                });
            };
        })->export('xls');
    }

    public function extract_transactions()
    {
        return view('admin.reports.transactions.index');
    }

    public function post_extract_transactions(Request $request)
    {
        set_time_limit(0); // 

        ini_set('memory_limit', '-1');
        if (strtolower($request->method()) == 'post' && $request->submit == 'export_report') {
            return $this->reportData($request,'post_extract_transactions');
        }
       
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

        $dataReport = [];
        
        if($request->submit == "view_report"){

            $transactions = Transaction::select(['transactions.*'])->join('invoices','invoices.id','=','transactions.invoice_id')
            ->whereRaw('invoices.deleted_at is null')
            ->whereRaw('transactions.deleted_at is null')
                ->whereBetween('date', [$from, $to]);
                
            $transactions = $transactions->groupBy('transactions.invoice_id')->get();
            $SubscriptionInvoice = 0;
            $EventInvoice = 0;
            $StoreInvoice = 0;
            $CourseInvoice = 0;

            $SubscriptionCancellations = 0;
            $EventCancellations = 0;
            $StoreCancellations = 0;
            $CourseCancellations = 0;

            $SubscriptionDiscounts = 0;
            $EventDiscounts = 0;
            $StoreDiscounts = 0;
            $CourseDiscounts = 0;

            $SubscriptionPayments = 0;
            $EventPayments = 0;
            $StorePayments = 0;
            $CoursePayments = 0;

            foreach ($transactions as $transaction) {
                if($transaction->invoice){
                    ${ucFirst($transaction->invoice->type)."Invoice"} +=$transaction->invoice->total;
                    if($transaction->invoice->status =='credit noted' || $transaction->invoice->status =='cancelled'){
                        ${ucFirst($transaction->invoice->type)."Cancellations"} +=$transaction->invoice->total;
                    }
                    if($transaction->invoice->status =='paid'){
                        ${ucFirst($transaction->invoice->type)."Discounts"} +=$transaction->invoice->discount;
                    }

                    if($transaction->invoice->status =='paid'){
                        ${ucFirst($transaction->invoice->type)."Payments"} +=$transaction->invoice->total;
                    }
                    
                }
            
            }

            $dataReport['SubscriptionInvoice'] = $SubscriptionInvoice;
            $dataReport['EventInvoice'] = $EventInvoice;
            $dataReport['StoreInvoice'] = $StoreInvoice;
            $dataReport['CourseInvoice'] = $CourseInvoice;

            $dataReport['SubscriptionCancellations'] = $SubscriptionCancellations;
            $dataReport['EventCancellations'] = $EventCancellations;
            $dataReport['StoreCancellations'] = $StoreCancellations;
            $dataReport['CourseCancellations'] = $CourseCancellations;

            $dataReport['SubscriptionDiscounts'] = $SubscriptionDiscounts;
            $dataReport['EventDiscounts'] = $EventDiscounts;
            $dataReport['StoreDiscounts'] = $StoreDiscounts;
            $dataReport['CourseDiscounts'] = $CourseDiscounts;

            $dataReport['SubscriptionPayments'] = $SubscriptionPayments;
            $dataReport['EventPayments'] = $EventPayments;
            $dataReport['StorePayments'] = $StorePayments;
            $dataReport['CoursePayments'] = $CoursePayments;
            return view('admin.reports.transactions.index',compact('dataReport'));
        }

        $transactions = Transaction::select(['transactions.*','user_notes.commision_claimed'])->with('user', 'invoice', 'invoice.items', 'invoice.transactions', 'invoice.user', 'invoice.user.subscriptions', 'user.subscriptions.agent', 'invoice.user.subscriptions.agent')->leftjoin('user_notes','user_notes.invoice_id','=','transactions.invoice_id')
            ->whereBetween('date', [$from, $to])->get();
        
        return Excel::create('from ' .Carbon::now()->timestamp, function ($excel) use ($transactions) {
            $excel->sheet('sheet', function ($sheet) use ($transactions) {
                $sheet->appendRow([
                    'Name',
                    'Email',
                    'Cell',
                    'Date',
                    'Invoice Number',
                    'total',
                    'Type',
                    'Debit / Credit',
                    'Transaction Type',
                    'Invoice description',
                    'INV Status',
                    'Payment Method',
                    'Sales Consultant',
                    'Invoice Total',
                    'Invoice Discount',
                    'Invoice Credit',
                    'Amount  Paid',
                    'Balance  due',
                    'Commision Claimed',
                ]);
                
                foreach ($transactions as $transaction) {
                    $amount = ($transaction->type == 'credit')?'-':'';
                    $amount = $amount.$transaction->amount;
                    $description = "";
                    if($transaction->invoice){
                        if($transaction->invoice->items){
                            $item = $transaction->invoice->items->first();
                            if($item){
                                $description = $item->name;
                            }
                        }
                    }
                    $sheet->appendRow([
                        ($transaction->user ? $transaction->user->first_name . ' ' . $transaction->user->last_name : "N/A"),
                        strtolower(($transaction->user ? $transaction->user->email : "N/A")),
                        ($transaction->user ? $transaction->user->cell : "N/A"),
                        date_format($transaction->created_at, 'Y-m-d'),
                        $transaction->ref,
                        $amount,
                        ($transaction->invoice ? $transaction->invoice->type : "N/A"),
                        $transaction->type,
                        $transaction->display_type,
                        $description,
                        ($transaction->invoice ? $transaction->invoice->status : "N/A"),
                        $transaction->method,
                        ($transaction->invoice)?(($transaction->invoice->user->subscribed('cpd') ? (($transaction->invoice->user->subscription('cpd')->agent ? $transaction->invoice->user->subscription('cpd')->agent->name : "") ?: "N/A") : "N/A")):"N/A" ,
                        (($transaction->type=='credit') ? -1 * $transaction->invoice->total : $transaction->invoice->total),
                        ( $transaction->tags == 'Discount' ? $transaction->amount : "0"),
                        ($transaction->invoice ? (($transaction->invoice->status =='credit noted' || $transaction->invoice->status =='cancelled'))? $transaction->invoice->transactions->where('display_type', 'Credit Note')->sum('amount') : "0":"0"),
                        ($transaction->invoice ? ($transaction->invoice->transactions->where('type', 'debit')->sum('amount') - $transaction->invoice->transactions->where('tags', 'Discount')->sum('amount')) : "0"),
                        ($transaction->invoice ? $transaction->invoice->BalanceStatic : "0"),
                        ($transaction->commision_claimed ? 'Yes' : "No")
                        ]);
                }
            });
        });
        //$excelFile->export('xls');;
        
    }

    public function reportData(Request $request,$function)
    {
        
        $data = $request->except('_token');
        $extractreport = ExtractReport::create(['report' => $function, 'user_id' => auth()->user()->id, 'request' => json_encode($data)]);
        alert()->success('Due to the size of your report, your report will be sent via email within the next 15 - 20 minutes with a download link.', 'Success!')->persistent('Thank you');

        return back();
    }

    public function getUpcomingRenewal($type = '')
    { 
        $from = request()->from;
        $to = request()->to;
        return view('admin.reports.upcoming_renewals', compact('type', 'from', 'to'));
    }

    public function postUpcomingRenewal(Request $request, $type = '')
    { 
        if($type == 'past') {
            $end_date = Carbon::now();
            $now = Carbon::now();
            $start_date = $now->subDays(90);
        }
        else {
            $start_date = Carbon::now();
            $now = Carbon::now();
            $end_date = $now->addDays(31);
        }
        
        if($request->from) {
            $start_date = Carbon::parse($request->from);
        }

        if($request->to) {
            $end_date = Carbon::parse($request->to);
        }

        $current = Carbon::now();
        $subscriptions = Subscription::withTrashed()->select('subscriptions.*','users.first_name', 'users.last_name', 'users.email', 'users.cell', 'plans.name as plan_name', 'plans.price as plan_price', 'subscriptions.name as subscription_name', 'plans.interval as subscription_type','s2.id as sid', 'subscriptions.id as subscriptionsid','s2.plan_id as splan_id',
            DB::raw("(SELECT s3.id FROM `subscriptions` as s3 where s3.id != subscriptions.id AND s3.plan_id != 45 AND s3.name = 'cpd' AND s3.user_id = users.id AND s3.deleted_at is null AND s3.ends_at > '$current' limit 1) as active_plan")
        )
        ->leftjoin('subscriptions as s2', function($join)
            {
            $join->on('s2.user_id', '=', 'subscriptions.user_id');
            $join->on('s2.id', '>', 'subscriptions.id');
            $join->where('s2.plan_id', '!=', DB::raw('45'));

            })
            ->join('users', 'users.id', '=', 'subscriptions.user_id')
            ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')
            ->where('subscriptions.name', 'cpd')
            ->where('plans.interval', 'year')
            ->where('plans.price', '>', 0)
            ->where('users.status','active')
            ->where(function($query) use ($type, $request){
                if($type == 'past' || ($request->input('from') && $request->input('to'))) {
                    $query->orWhere('s2.plan_id','=',45)
                        ->orWhere('s2.id','=',null);
                }else{
                    $query->whereNull('s2.id');   
                }
            })   
            ->whereBetween('subscriptions.ends_at', [$start_date->format('Y-m-d').' 00:00:00', $end_date->format('Y-m-d').' 23:59:59'])
            ->groupBy('subscriptions.id')
            ->get();

        Excel::create('Subscriptions from '.date('d F Y', strtotime($start_date)).' - '.date('d F Y', strtotime($end_date)), function($excel) use($subscriptions) {
            $excel->sheet('sheet', function($sheet) use ($subscriptions){
                $sheet->appendRow([
                    'First Name',
                    'Last Name',
                    'Email',
                    'Phone',
                    'Plan Name',
                    'Plan Price',
                    'Subscription Type',
                    'Subscription Start Date',
                    'Subscription End Date',
                    'Agent Name',
                    'Agent Email'
                ]);

                foreach ($subscriptions as $subscription){
                    if($subscription->active_plan == null) {
                    $agent_name = '';
                    $agent_email = '';
                    if($subscription->agent_id > 0 && $subscription->agent_id != '' && $subscription->agent_id != null) {
                        $agent = User::find($subscription->agent_id);
                        if($agent){
                        $agent_name = $agent->first_name.' '.$agent->last_name;
                        $agent_email = $agent->email;
                        }
                    } 
                    
                    $sheet->appendRow([
                        $subscription->first_name,
                        $subscription->last_name,
                        strtolower($subscription->email),
                        $subscription->cell,
                        $subscription->plan_name,
                        $subscription->plan_price,
                        ucfirst($subscription->subscription_type).'ly',
                        $subscription->starts_at,
                        $subscription->ends_at,
                        $agent_name,
                        $agent_email
                    ]);
                    }
                }
            });
        })->export('xls');

        alert()->success('Your report was extracted', 'Success!');
        return redirect()->back();

    }

    public function datatableUpcomingRenewal(Request $request, $type = '')
    { 
        if($type == 'past') {
            $end_date = Carbon::now();
            $now = Carbon::now();
            $start_date = $now->subDays(90);
        }
        else {
            $start_date = Carbon::now();
            $now = Carbon::now();
            $end_date = $now->addDays(31);
        }

        if($request->input('from') && $request->input('to')) {
            $start_date = Carbon::parse($request->input('from'));
            $end_date = Carbon::parse($request->input('to'));
        }

        $current = Carbon::now();
        $subscriptions = Subscription::withTrashed()->select('subscriptions.*','users.first_name', 'users.last_name', 'users.email', 'users.cell', 'plans.name as plan_name', 'plans.price as plan_price', 'subscriptions.name as subscription_name', 'plans.interval as subscription_type','s2.id as sid', 'subscriptions.id as subscriptionsid',
            DB::raw("(SELECT s3.id FROM `subscriptions` as s3 where s3.id != subscriptions.id AND s3.plan_id != 45 AND s3.name = 'cpd' AND s3.user_id = users.id AND s3.deleted_at is null AND s3.ends_at > '$current' limit 1) as active_plan")
        )
        ->leftjoin('subscriptions as s2', function($join)
            {
            $join->on('s2.user_id', '=', 'subscriptions.user_id');
            $join->on('s2.id', '>', 'subscriptions.id');
            $join->where('s2.plan_id', '!=', DB::raw('45'));

            })
            ->join('users', 'users.id', '=', 'subscriptions.user_id')
            ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')
            ->where('subscriptions.name', 'cpd')
            ->where('plans.interval', 'year')
            ->where('plans.price', '>', 0)
            ->where('users.status','active')
            ->where(function($query) use ($type, $request){
                if($type == 'past' || ($request->input('from') && $request->input('to'))) {
                    $query->where('s2.plan_id','=',45)
                        ->orWhere('s2.id','=',null);
                }else{
                    $query->whereNull('s2.id');
                }
            })
            ->whereBetween('subscriptions.ends_at', [$start_date->format('Y-m-d').' 00:00:00', $end_date->format('Y-m-d').' 23:59:59']);
        
        if($request->search) {
            if(isset($request->search['value']) && !empty($request->search['value'])) {
                $search = trim($request->search['value']);
                $subscriptions->where(function($q) use($search){
                    $q->where('users.email', $search);
                    $q->orWhereRaw('concat(users.first_name," ", users.last_name) LIKE "%'.$search.'%"');
                    $q->orWhere('users.cell', $search);
                    $q->orWhere('plans.name', 'LIKE', '%'.$search.'%');
                });
            }
        }
        $subscriptions->groupBy('subscriptions.id');

        return Datatables::of($subscriptions->get())
            ->editColumn('starts_at', function($user) {
                return $user->starts_at->format('d-m-Y');
            })
            ->editColumn('ends_at', function($user) {
                return $user->ends_at->format('d-m-Y');
            })
            ->addColumn('agent_name', function($user) {
                $agent = User::find($user->agent_id);
                return ($agent) ? $agent->first_name." ".$agent->last_name : "-";
            })
            ->addColumn('agent_email', function($user) {
                $agent = User::find($user->agent_id);
                return ($agent) ? $agent->email : "-";
            })
            ->make(true);
    }

    
    // Monthly Income Report
    public function get_monthly_income_report()
    {
        return view('admin.reports.invoices.monthly_income_extract');
    }

    public function monthly_income_report(Request $request)
    {

        if (strtolower($request->method()) == 'post' && $request->submit == 'export_report' && env('APP_THEME') == 'saaa') {
            return $this->reportData($request,'monthly_income_report');
        }
        $year = $request->year;
        // $year = date('Y');
        $for_practice = Invoice::with('items','transactions')
                                ->select('invoices.*', 'plans.is_practice')
                                ->leftjoin('items_lists', 'items_lists.invoice_id', '=', 'invoices.id')
                                ->leftjoin('items', 'items.id', '=', 'items_lists.item_id')
                                ->leftjoin('plans', 'items.item_id', '=', 'plans.id')
                                ->where('invoices.type', 'subscription')
                                ->where('invoices.status', '!=', 'credit noted')
                                ->where('plans.is_practice', '1')
                                ->whereYear('invoices.created_at','=', $year)
                                ->groupBy('invoices.id')
                                ->orderBy('invoices.type')
                                ->get();
        $for_subscription = null;
        $invoices = Invoice::with('items','transactions')
                            ->select('invoices.*')
                            ->whereYear('invoices.created_at','=',$year)
                            ->where('invoices.status', '!=', 'credit noted')
                            ->groupBy('invoices.id')
                            ->orderBy('invoices.type','desc')
                            ->get();        
        $invoices = $invoices->diff($for_practice);

        $subscription_plans = Plan::where('invoice_description','NOT LIKE','%Course:%')->where('is_practice','1')->get()->pluck('id')->toArray();
        $excel = Excel::create('Monthly Income Report'.Carbon::now()->timestamp, function ($excel) use ($invoices,$for_practice,$for_subscription,$year,$subscription_plans) {

            $excel->sheet('Summary', function ($sheet) use ($year) {
                $headers = ["MONTH:"];
                for($month = 1; $month <= 12; $month++) {
                    $headers[] = strtoupper(DateTime::createFromFormat('!m', $month)->format('F'));
                    $headers[] = "";
                }
                $sheet->appendRow($headers);

                $typePaidDue = ["TYPE OF INCOME:"];
                for($month = 1; $month <= 12; $month++) {
                    $typePaidDue[] = "Amount Paid";
                    $typePaidDue[] = "Balance Due";
                }
                $typePaidDue[] = "Total amount paid:";
                $typePaidDue[] = "Total balance due:";
                $sheet->appendRow($typePaidDue);
                
                $types = [ 3 => 'subscription', 4 => 'practice', 5 => 'course', 8 => 'event', 9 => 'store', 10 => 'donations'];

                foreach($types as $typeKey => $type) {
                    $invoiceType = [$type == 'store' ? strtoupper($type." (WODS)") : strtoupper($type)];
                    $zeroVat = ['Zero rated VAT'];
                    $vatable = ['Vatable rated sales'];
                    $empty = [];
                    for($month = 1; $month <= 12; $month++) {
                        $empty[] = "";
                        if($type != 'donations' && $type != 'course') {
                            $monthlyInvoices = $this->getInvoices($month, $year, $type);
                            $amountPaid = $this->getAmountPaid($monthlyInvoices);
                            $balanceDue = $this->getBalanceDue($monthlyInvoices);
                        } elseif($type == 'donations') {
                            $amountPaid = $this->getDonationAmount($month, $year, 'paid');
                            $balanceDue = $this->getDonationAmount($month, $year, 'unpaid');
                        }
                        $invoiceType[] = $type == 'course' ? "" : ($amountPaid > 0 ? $amountPaid : 0);
                        $invoiceType[] = $type == 'course' ? "" : $balanceDue;

                        if($type == 'course') {
                            $zeroVatInvoices = $this->getInvoices($month, $year, $type, 'zero_vat');
                            $zeroVatAmountPaid = $this->getAmountPaid($zeroVatInvoices);
                            $zeroVatBalanceDue = $this->getBalanceDue($zeroVatInvoices);

                            $zeroVat[] = $zeroVatAmountPaid > 0 ? $zeroVatAmountPaid : 0;
                            $zeroVat[] = $zeroVatBalanceDue;

                            $vatableInvoices = $this->getInvoices($month, $year, $type, 'vatable');
                            $vatableAmountPaid = $this->getAmountPaid($vatableInvoices);
                            $vatableBalanceDue = $this->getBalanceDue($vatableInvoices);

                            $vatable[] = $vatableAmountPaid > 0 ? $vatableAmountPaid : 0;
                            $vatable[] = $vatableBalanceDue;
                        }
                    }

                    if($type != 'course') {
                        $results = $this->getRowCellSumString($typeKey);
                        $invoiceType[] = $results[0];
                        $invoiceType[] = $results[1];
                    }
                    
                    $sheet->appendRow($invoiceType);
                    if($type == 'course') {
                        $results = $this->getRowCellSumString($typeKey + 1);
                        $zeroVat[] = $results[0];
                        $zeroVat[] = $results[1];
                        $sheet->appendRow($zeroVat);

                        $results = $this->getRowCellSumString($typeKey + 2);
                        $vatable[] = $results[0];
                        $vatable[] = $results[1];
                        $sheet->appendRow($vatable);
                    }
                    // $sheet->appendRow($empty);
                }
                
                $total = ["TOTAL:"];
                foreach (range('B', 'Y') as $letter) {
                    $total[] = "=SUM({$letter}3:{$letter}10)";
                }
                $results = $this->getRowCellSumString(11);
                $total[] = $results[0];
                $total[] = $results[1];
                $sheet->appendRow($total);

                // for apply css in excel
                $this->setStylesToSummarySheet($sheet);
            });

            $excel->sheet('MASTER - ALL DATA', function ($sheet) use ($invoices,$for_practice,$for_subscription,$subscription_plans) {

                $sheet->appendRow([
                    'Invoice Number',
                    'type',
                    'Description',
                    'Date',
                    'Date Settled',
                    'Paid Status',
                    'Invoice Total',
                    'Donation',
                    'Invoice Discount',
                    'Invoice Credit',
                    'Invoice VAT',
                    'Amount  Paid',
                    'Balance  due',
                ]);

                $types = ['course', 'event', 'practice', 'store', 'subscription'];
                foreach($types as $type) {
                    if($type == 'practice') {
                        $typeInvoices = $for_practice;
                    }else {
                        $typeInvoices = $invoices->where('type', $type);
                    }
                    $this->listing_audit_invoices_for_extract($typeInvoices, $sheet,$subscription_plans);
                }
                // for apply css in excel
                foreach (range('A', 'M') as $letter){
                    $sheet->cell("{$letter}1", function($cell) {
                        $cell->setFontWeight('bold');
                    });
                }
            });

            $types = ['course', 'event', 'practice', 'store', 'subscription'];
            foreach($types as $type) {
                if($type == 'practice') {
                    $typeInvoices = $for_practice;
                } else {
                    $typeInvoices = $invoices->where('type', $type);
                }
                $this->sheet_listing_audit_invoices_for_extract(strtoupper($type), $typeInvoices, $excel,$subscription_plans);
            }

        });
        if(env('APP_THEME') != 'saaa'){
            $excel->export('xls');
        }
        return $excel;
    }

    public function getRowCellSumString($cell) {
        $totalAmountPaidCells = "";
        $totalBalanceDueCells = "";
        foreach (range('B', 'Y') as $letterKey => $letter) {
            if($letterKey % 2 == 0) {
                $totalAmountPaidCells .= $totalAmountPaidCells == "" ? "{$letter}{$cell}" : "+{$letter}{$cell}";
            } else {
                $totalBalanceDueCells .= $totalBalanceDueCells == "" ? "{$letter}{$cell}" : "+{$letter}{$cell}";
            }
        }
        $totalAmountPaidCells = "=SUM({$totalAmountPaidCells})";
        $totalBalanceDueCells = "=SUM({$totalBalanceDueCells})";
        return [$totalAmountPaidCells, $totalBalanceDueCells];
    }

    public function setStylesToSummarySheet($sheet) {
        foreach (range('A', 'Z') as $letter){
            $sheet->cell("{$letter}1", function($cell) {
                $cell->setBackground('#00ff99');
                $cell->setFontWeight('bold');
            });
            $sheet->cell("{$letter}2", function($cell) {
                $cell->setFontWeight('bold');
            });
            $sheet->cell("{$letter}11", function($cell) {
                $cell->setBackground('#00ff99');
                $cell->setFontWeight('bold');
            });
        }
        $sheet->cell("AA1", function($cell) {
            $cell->setBackground('#00ff99');
            $cell->setFontWeight('bold');
        });
        $sheet->cell("AA2", function($cell) {
            $cell->setFontWeight('bold');
        });
        $sheet->cell("AA11", function($cell) {
            $cell->setBackground('#00ff99');
            $cell->setFontWeight('bold');
        });

        $sheet->cells('A1:A5', function($cells) {
            $cells->setFontWeight('bold');
        });
        $sheet->cells('A8:A11', function($cells) {
            $cells->setFontWeight('bold');
        });
        
        $sheet->cells('Z1:Z11', function($cells) {
            $cells->setFontWeight('bold');
        });
        $sheet->cells('AA1:AA11', function($cells) {
            $cells->setFontWeight('bold');
        });

        $sheet->cell("C2:C11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("E2:E11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("G2:G11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("I2:I11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("K2:K11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("M2:M11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("O2:O11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("Q2:Q11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("S2:S11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("U2:U11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("W2:W11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("Y2:Y11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
        $sheet->cell("AA2:AA11", function($cell) {
            $cell->setFontColor('#fd0000');
        });
    }

    public function getDonationAmount($month, $year, $paid) {
        $invoices = Invoice::with('transactions')
                            ->whereYear('invoices.created_at', '=', $year)
                            ->whereMonth('invoices.created_at', '=', $month)
                            ->where('invoices.donation', '>', '0');
                            
        if($paid == 'paid') {
            $invoices = $invoices->where('invoices.status', 'paid');
        } else {
            $invoices = $invoices->where('invoices.status', 'unpaid');
        }
        
        return $invoices->sum('donation');
    }

    public function getInvoices($month, $year, $type, $vat = null) {
        if($type == 'practice' || $type == 'subscription' ) {
            $isPractice = 1;
            
            $invoices = Invoice::with('transactions')
                            ->select('invoices.*')
                            ->leftjoin('items_lists', 'items_lists.invoice_id', '=', 'invoices.id')
                            ->leftjoin('items', 'items.id', '=', 'items_lists.item_id')
                            ->leftjoin('plans', 'items.item_id', '=', 'plans.id')
                            ->whereYear('invoices.created_at', '=', $year)
                            ->whereMonth('invoices.created_at', '=', $month)
                            ->where('invoices.type', 'subscription')
                            ->where('invoices.status', '!=', 'credit noted')
                            ->where('plans.is_practice', $isPractice)
                            ->groupBy('invoices.id')
                            ->get();
                            
            if($type == 'subscription')
            {
                $subInvoices = Invoice::with('transactions')
                            ->select('invoices.*')
                            ->whereYear('invoices.created_at', '=', $year)
                            ->whereMonth('invoices.created_at', '=', $month)
                            ->where('invoices.type', $type)
                            ->where('invoices.status', '!=', 'credit noted')
                            ->groupBy('invoices.id')
                            ->get();
                $invoices = $subInvoices->diff($invoices);
            }
            
        } else {
            $invoices = Invoice::with('transactions')
                            ->select('invoices.*')
                            ->whereYear('invoices.created_at', '=', $year)
                            ->whereMonth('invoices.created_at', '=', $month)
                            ->where('invoices.type', $type)
                            ->where('invoices.status', '!=', 'credit noted');
            if($vat) {
                if($vat == 'zero_vat') {
                    $invoices = $invoices->where('invoices.vat_rate', '0');
                } elseif($vat == 'vatable') {
                    $invoices = $invoices->where('invoices.vat_rate', '>', '0');
                }
            }
            $invoices = $invoices->groupBy('invoices.id')->get();
        }
        return $invoices;
    }

    public function getAmountPaid($invoices) {
        $amountPaid = 0;
        foreach($invoices as $invoice) {
            $amount = ($invoice->transactions->where('type', 'credit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') - $invoice->transactions->where('tags', 'Adjustment')->sum('amount') - $invoice->transactions->where('tags', 'Cancellation')->sum('amount'));

            if($amount > 0) {
                $amountPaid += $amount;
            }
            if($invoice->status == 'paid' && $invoice->donation > 0) {
                $amountPaid = $amountPaid - (int)$invoice->donation;
            }
        }
        return $amountPaid;
    }

    public function getBalanceDue($invoices) {
        $balance_due = 0;
        foreach($invoices as $invoice) {
            if(isset($invoice)) {
                $debit = $invoice->transactions->where('type', 'debit')->sum('amount');
                $credit = $invoice->transactions->where('type', 'credit')->sum('amount');
                // if($debit - $credit > 0) {
                    $balance_due += $debit - $credit;
                // }
                if($invoice->status == 'unpaid' && $invoice->donation > 0) {
                    $balance_due = $balance_due - (int)$invoice->donation;
                }
            }
        }
        return $balance_due;
    }

    public function listing_audit_invoices_for_extract($invoices,$sheet,$subscription_plans){
        foreach ($invoices as $invoice) {
            $this->appendInvoiceRowInSheet($sheet, $invoice,$subscription_plans);
        }
    }

    public function sheet_listing_audit_invoices_for_extract($name,$invoices,$excel,$subscription_plans){
        $excel->sheet($name, function ($sheet) use ($invoices,$subscription_plans,$name) {
            $sheet->appendRow([
                'Invoice Number',
                'type',
                'Description',
                'Date',
                'Date Settled',
                'Paid Status',
                'Invoice Total',
                'Donation',
                'Invoice Discount',
                'Invoice Credit',
                'Invoice VAT',
                'Amount  Paid',
                'Balance  due',
            ]);

            foreach ($invoices as $invoice) {
                // if($name  != 'practice')
                // {
                    $this->appendInvoiceRowInSheet($sheet, $invoice,$subscription_plans);
                // }else{
                //     if($invoice->type == 'subscription' && isPractice($invoice,$subscription_plans)){
                //         $this->appendInvoiceRowInSheet($sheet, $invoice,$subscription_plans);
                //     }
                // }
                
            }
            // for apply css in excel
            foreach (range('A', 'M') as $letter){
                $sheet->cell("{$letter}1", function($cell) {
                    $cell->setFontWeight('bold');
                });
            }
        });
    }

    public function appendInvoiceRowInSheet($sheet, $invoice,$subscription_plans) {
        $credit_transactions = $transactions = $invoice->transactions->filter(function ($transaction){
            return ($transaction->display_type == 'Credit Note' && $transaction->tags != 'Discount');
        });

        $item_description = ($invoice->items->count())?implode(",",$invoice->items->pluck('name')->toArray()):"";
        $balance_due = 0;
        if(isset($invoice)) {
            $debit =  $invoice->transactions->where('type', 'debit')->sum('amount');
            $credit =  $invoice->transactions->where('type', 'credit')->sum('amount');

            $amount = $debit - $credit;
            // if($amount > 0) {
                $balance_due = $amount;
            // }
        }
        // $credit_transaction = $invoice->transactions()->where('type', 'credit')->orderBy('id','desc')->first();
        // $discount_transaction = $invoice->transactions->where('tags', 'Discount')->first();
        $amountPaid = ($invoice->transactions->where('type', 'credit')->sum('amount') - $invoice->transactions->where('tags', 'Discount')->sum('amount') - $invoice->transactions->where('tags', 'Adjustment')->sum('amount') - $invoice->transactions->where('tags', 'Cancellation')->sum('amount'));
        $sheet->appendRow([
            
            $invoice->reference,
            // $invoice->type == 'subscription' && isPractice($invoice,$subscription_plans) ? 'practice' : $invoice->type,
            $invoice->type == 'subscription' && isset($invoice->is_practice) && $invoice->is_practice == '1' ? 'practice' : $invoice->type,
            // (isset($invoice->is_practice) && $invoice->is_practice == 1) ? 'practice' : $invoice->type ,
            $item_description,
            date_format($invoice->created_at, 'Y-m-d'),
            $invoice->date_settled && strtotime($invoice->date_settled) != strtotime('0000-00-00 00:00:00') ? date_format($invoice->date_settled, 'Y-m-d') : 'N/A',
            // ($invoice->paid ? 'Yes' : 'No'),
            (($invoice->cancelled)?"credit noted":$invoice->status),
            
            ($invoice ? (($invoice->total<99999999.99)?$invoice->total:$invoice->transactions->where('type', 'debit')->sum('amount') ) : '0'),
            ($invoice ? $invoice->donation : '0'),

            ($invoice ? $invoice->transactions->where('tags', 'Discount')->sum('amount') : '0'),
            ($invoice ? (($invoice->status == 'credit noted' || $invoice->cancelled) ? $credit_transactions->sum('amount') : 0) : '0'),
            $invoice->vat_rate."%",
            ($invoice ? (($amountPaid>=0)?$amountPaid:0) : '0'),
            ($invoice ? $balance_due : '0')
        ]);
    }

    public function wod_export()
    {
        return view('admin.reports.webinar_on_Demand');
    }

    public function post_wod_export()
    {
        $wods = Video::select("videos.id","videos.title","videos.created_at","videos.amount",DB::raw("(SELECT COUNT(activity_log.model_id) FROM activity_log WHERE activity_log.model_id = videos.id AND  activity_log.model = 'App\\\Video'  AND activity_log.action = 'watched' GROUP BY model_id ) AS views_of_video") )->orderBy('videos.id','desc')->get();

        Excel::create('Webinar On Demand', function ($excel) use ($wods) {
            $excel->sheet('Sponsor Data', function ($sheet) use ($wods) {
                $sheet->appendRow([
                    'Index',
                    'Title',
                    'Date',
                    'Type',
                    'Number of Views'
                ]);
                
                foreach ($wods as $key => $wod)
                {
                    $sheet->appendRow([
                        $key + 1,
                        $wod->title,
                        Carbon::parse($wod->created_at)->format('Y-m-d'),
                        $wod->amount > 0 ? 'Paid' : 'Free',
                        $wod->views_of_video > 0 ? $wod->views_of_video : 0
                    ]);
                }
            });
        })->export('xls');
    }

    public function course_reports() {
        $courses = Course::orderBy('id', 'desc')->get();
        return view('admin.reports.courses.index', compact('courses'));
    }

    public function extract_course_report($course_id)
    {
        $course = Course::where('id', $course_id)->first();
        $monthly = Subscription::with('user', 'user.invoices', 'user.invoices.items', 'invoices', 'invoices.items', 'invoices.transactions', 'user.invoiceOrders', 'user.invoiceOrders.items', 'user.debit')
                            ->where('plan_id', $course->monthly_plan_id)
                            ->where('name', 'course')->get();
            
        $yearly = Subscription::with('user', 'user.invoices', 'user.invoices.items', 'invoices', 'invoices.items', 'invoices.transactions', 'user.invoiceOrders', 'user.invoiceOrders.items', 'user.debit')
                            ->where('plan_id', $course->yearly_plan_id)
                            ->where('name', 'course')->get();
        // dd($yearly);
        Excel::create('Report of ' . $course->title, function ($excel) use ($monthly, $yearly, $course) {
            $excel->sheet('sheet', function ($sheet) use ($monthly, $yearly, $course) {
                $header = [
                    'Student Number',
                    'Date of registration',
                    'Name',
                    'Last name',
                    'Email',
                    'Cell',
                    'Description - Course name',
                    'Invoice / purchase order',
                    'Payment Method - Once Off / Debit order',
                    'Payment Status',
                    'Application fee',
                    'ONCE OFF PAYMENT'
                    
                ];
                for($j = 1; $j <= (int)$course->no_of_semesters; $j++) {
                    $header[] = "OO SEM  {$j}";
                }
                $header[] = 'Billing Information loaded - yes/no';
                for($i = 1; $i <= 21; $i++) {
                    $header[] = "Debit order Payment {$i}";
                }
                
                $bottom = [
                    'User suspended - yes/no',
                    'Sales representative - sales or system'
                ];
                $header = array_merge($header, $bottom);
                
                $sheet->appendRow($header);

                // $invoice_order = Invoice::where('id',10649)->first();
                // $invoice_order_items = $invoice_order->items;
                // dd($invoice_order_items[0]);
                if(count($monthly) > 0) {
                    foreach ($monthly as $for_month) {

                        $user = $for_month->user;

                        $invoice = $for_month->invoices;
                        $invoice_items = $invoice ? $invoice->items : null;

                        $invoice_order = null;
                        
                        $invoiceOrOrder = '-';
                        $status = "";
                        if($invoice) {
                            $invoiceOrOrder = "Invoice - #".$invoice->reference;
                            $status = $invoice->status;
                        } else {
                            $user_invoice_orders  = $user->invoiceOrders()->where('type', 'course')->get();
                            
                            foreach ($user_invoice_orders as $order) {
                                $course_item = $order->items()->where('items.type', 'course')->where('items.item_id', $course->id)->first();
                                if($course_item) {
                                    $invoice_order = $order;
                                    $invoiceOrOrder = "Purchase order - #".$invoice_order->reference;
                                    $status = $invoice_order->status;
                                    break;
                                }
                            }
                        }

                        $transactions = [];
                        if($invoice) {
                            $transactions = $invoice->transactions()->where('method', 'debit')->where('description', 'LIKE', '% Debit Order Payment')->get()->toArray();
                        }
                        $data = [
                            $for_month->student_number ? $for_month->student_number : "N/A",
                            Carbon::parse($for_month->created_at)->format('M d Y'),
                            $user->first_name,
                            $user->last_name,
                            $user->email,
                            $user->cell,
                            $course->title,
                            $invoiceOrOrder,
                            "Debit order",
                            $status,
                            $status == "paid" ? $course->monthly_enrollment_fee : 0,
                            "-"
                            
                        ];
                        for($j = 1; $j <= (int)$course->no_of_semesters; $j++) {
                            $data[] = "";
                        }
                        $data[] = $user->debit ? 'yes' : 'no';
                        for($i = 0; $i < 21; $i++) {
                            $data[] = isset($transactions[$i]) ? $transactions[$i]['amount'] : 0;
                        }

                        $data_bottom = [
                            $user->status == "suspended" ? 'yes' : 'no',
                            $for_month->agent_id ? 'Sales representative' : 'System'
                        ];
                        $data = array_merge($data, $data_bottom);
                        $sheet->appendRow($data);
                    }
                }

                if(count($yearly) > 0) {
                    foreach ($yearly as $for_year) {
                        $user = $for_year->user;

                        $invoice = $for_year->invoices;
                        $invoice_items = $invoice ? $invoice->items : null;

                        $invoice_order = null;
                        
                        $invoiceOrOrder = '-';
                        $status = "";
                        $onceOffPayment = 0;
                        if($invoice) {
                            $invoiceOrOrder = "Invoice - #".$invoice->reference;
                            $status = $invoice->status;
                            $onceOffPayment = $invoice->total;
                        } else {
                            $user_invoice_orders  = $user->invoiceOrders()->where('type', 'course')->get();
                            
                            foreach ($user_invoice_orders as $order) {
                                $course_item = $order->items()->where('items.type', 'course')->where('items.item_id', $course->id)->first();
                                if($course_item) {
                                    $invoice_order = $order;
                                    $invoiceOrOrder = "Purchase order - #".$invoice_order->reference;
                                    $status = $invoice_order->status;
                                    $onceOffPayment = $invoice_order->total;
                                    break;
                                }
                            }
                        }

                        $semesterInvoices = [];
                        if($course->type_of_course == 'semester') {
                            $semInvoices = $user->invoices()->where('type', 'course')->where('total', $course->semester_price)->get();
                            
                            if(count($semInvoices) > 0) {
                                foreach ($semInvoices as $semInvoice) {
                                    $course_item = $semInvoice->items()->where('items.type', 'course')->where('items.item_id', $course->id)->first();
                                    if($course_item) {
                                        $semesterInvoices[] = $semInvoice;
                                    }
                                }
                            }
                        }

                        $transactions = [];
                        if($invoice) {
                            $transactions = $invoice->transactions()->where('method', 'debit')->where('description', 'LIKE', '% Debit Order Payment')->get()->toArray();
                        }

                        $data = [
                            $for_year->student_number ? $for_year->student_number : "N/A",
                            Carbon::parse($for_year->created_at)->format('M d Y'),
                            $user->first_name,
                            $user->last_name,
                            $user->email,
                            $user->cell,
                            $course->title,
                            $invoiceOrOrder,
                            'Once off',
                            $status,
                            $course->yearly_enrollment_fee > 0 ? $course->yearly_enrollment_fee : 0,
                            $onceOffPayment
                        ];

                        for($j = 0; $j < (int)$course->no_of_semesters; $j++) {
                            $data[] = isset($semesterInvoices[$j]) ? $semesterInvoices[$j]->total : 0;
                        }

                        $data[] = $user->debit ? 'yes' : 'no';
                        for($i = 0; $i < 21; $i++) {
                            $data[] = isset($transactions[$i]) ? $transactions[$i]['amount'] : 0;
                        }

                        $data_bottom = [
                            $user->status == "suspended" ? 'yes' : 'no',
                            $for_year->agent_id ? 'Sales representative' : 'System'
                        ];
                        $data = array_merge($data, $data_bottom);
                        $sheet->appendRow($data);
                    }
                }
            });
        })->export('xls');
    }
}
