<?php

namespace App\Console\Commands;

use App\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class SendSystemReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send daily reports';
    /**
     * @var User
     */
    private $user;

    /**
     * Create a new command instance.
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->SendSuspensionReport();
//        $this->SendCPDSubscriptionReports();
    }

    /*
       * Send Suspension Report.
       */

    public function SendSuspensionReport()
    {
        $users = $this->user->whereBetween('suspended_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])->get();

        Excel::create('suspended_users', function ($excel) use ($users) {
            $excel->sheet('sheet', function ($sheet) use ($users) {
                $sheet->appendRow([
                    'User ID',
                    'Join Date',
                    'Email',
                    'Payment Method',
                    'Invoices',
                    'ID Number',
                    'Cell',
                    'Subscription'
                ]);

                foreach ($users as $user) {
                    if ($user->subscribed('cpd')) {
                        $sheet->appendRow([
                            $user->id,
                            date_format($user->created_at, 'd F Y'),
                            $user->email,
                            $user->payment_method,
                            $user->invoices->where('status', 'unpaid')->count(),
                            $user->id_number,
                            $user->cell,
                            $user->subscription('cpd')->plan->name . ' ' . $user->subscription('cpd')->plan->interval . 'ly'
                        ]);
                    } else {
                        $sheet->appendRow([
                            $user->id,
                            date_format($user->created_at, 'd F Y'),
                            $user->email,
                            $user->payment_method,
                            $user->invoices->where('status', 'unpaid')->count(),
                            $user->id_number,
                            $user->cell,
                            'No Plan'
                        ]);
                    }
                }
            });
        })->store('xls', storage_path('app/public/exports'));

        try {
            $location = storage_path('app/public/exports/suspended_users.xls');
            Mail::send('emails.reports.suspended', ['users' => $users], function ($m) use ($users, $location) {
                $m->from(config('app.email'), config('app.name'));
                $m->to(config('app.email'))->subject('Suspended Users');
                $m->attach($location);
            });
        } catch (\Exception $e) {
            $this->error('Could not send report...');
        }
    }

//    public function SendCPDSubscriptionReports()
//    {
//        $dates = [Carbon::yesterday()->startOfDay(), Carbon::today()->endOfDay()];
//        $subscribers = $this->user->whereBetween('created_at', $dates)->get();
//        $filteredSubscribers = $subscribers->filter(function ($subscriber){
//            if ($subscriber->subscribed('cpd')){
//                return $subscriber;
//            }
//        });
//
//
//        $this->info("We have ".count($filteredSubscribers));
//    }
}
