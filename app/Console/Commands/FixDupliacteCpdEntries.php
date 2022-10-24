<?php

namespace App\Console\Commands;

use App\Activities\Activity;
use App\Users\Cpd;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDupliacteCpdEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:cpds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will remove duplicate CPD Records';

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

        $duplicates = DB::table('cpds')
            ->select('user_id', 'ticket_id', 'source')
            ->groupBy('user_id', 'ticket_id', 'source')
            ->havingRaw('COUNT(*) > 1')
            ->where('ticket_id', '!=', '')
            ->where('source', 'NOT LIKE', '%'.'3rd Annual Practice Management Conference for Accountants'.'%')
            ->where('source', 'NOT LIKE', '%'.'3rd Annual Not-for-Profit Industry Conference'.'%')
            ->get();

        if (count($duplicates)){
            foreach ($duplicates as $cpd) {
                $records = Cpd::where('ticket_id', $cpd->ticket_id)->where('user_id', $cpd->user_id)->get();
                if (count($records) > 1){
                    foreach ($records as $record) {
                        $entries = Cpd::where('ticket_id', $record->ticket_id)->where('user_id', $record->user_id)->get();
                        if ($entries->count() > 1){
                            $this->info('We are removing '.$cpd->id);
                            $record->delete();
                        }
                    }
                }else{
                    $this->info('Skipping!');
                }
            }
        }else{
            $this->info('No Duplicates Found!');
        }
    }
}
