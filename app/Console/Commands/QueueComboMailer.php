<?php

namespace App\Console\Commands;

use App\ComboMailer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Sendinblue\Mailin;

class QueueComboMailer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:combo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will send the combo mailer every second Tuesday';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $mailer = ComboMailer::all()->last();

        if (! $mailer){
            ComboMailer::create([
                'subject' => 'Your '.date_format(Carbon::now(), 'F').' CPD Planner',
                'sent_at' => Carbon::now()->addDay(7),
            ]);

        }else{
            if ($mailer->should_send != true){
                $mailer->update(['should_send' => true]);

            }else{
                $users = collect();
                $mailin = $this->call();
                $listIds = ['100', '4'];

                $count = str_replace('.0', '', round($mailin->display_list_users(['listids' => $listIds])['data']['total_list_records'] / 500, 0));

                for ($i = 1; $i <= $count; $i++) {
                    $this->info($i);
                    $subscribers = $mailin->display_list_users(['listids' => $listIds, null, "page" => $i]);
                    foreach ($subscribers['data']['data'] as $subscriber){
                        if ($subscriber['blacklisted'] != 1){
                            $users->push($subscriber['email']);
                        }
                    }
                }

                $this->info('We have sent the mailer');
                ComboMailer::create([
                    'subject' => 'Your '.date_format(Carbon::parse($mailer->sent_at)->addDay(14), 'F').' CPD Planner',
                    'sent_at' => Carbon::parse($mailer->sent_at)->addDay(14),
                    'count' => $mailin->display_list_users(['listids' => $listIds])['data']['total_list_records']
                ]);

            }
        }
    }

    public function call()
    {
        $mailin = new Mailin('https://api.sendinblue.com/v2.0', env('SENDINBLUE_KEY'));
        return $mailin;
    }
}
