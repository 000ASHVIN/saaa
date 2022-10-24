<?php

namespace App\Console\Commands;

use App\Jobs\SendCollectionEmailToStaff;
use App\Users\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Mail;

class SendSuspendedNotifications extends Command
{
    use DispatchesJobs;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lets send an email to all the people that was suspended today';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::where('suspended_at', '>=', Carbon::today()->startOfDay())
                ->where('suspended_at', '<=', Carbon::today()->endOfDay())->get();

        $this->info('We have ' . count($users) . ' that was suspended today');

        foreach ($users as $user) {
            $this->info('Sending email to ' . $user->first_name . ' ' . $user->last_name);

            $job = (new SendCollectionEmailToStaff($user));
            $this->dispatch($job);
        }

        try {
            $this->info('Sending Report...');
            $mail_addresses = config('app.email');

            Mail::send('emails.accounts.suspended_report', ['users' => $users], function ($m) use ($users, $mail_addresses) {
                $m->from(config('app.email'), config('app.name'));
                $m->to($mail_addresses, $users)->subject('Suspension Report ' . date_format(Carbon::today(), 'd F Y'));
            });
        } catch (\Exception $e) {
            $this->error('Could not send report...');
        }
        $this->info('We are done! :)');
    }
}
