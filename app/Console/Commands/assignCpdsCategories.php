<?php

namespace App\Console\Commands;

use App\Users\Cpd;
use Illuminate\Console\Command;

class assignCpdsCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:categorizeCpds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'We will now set all the cpd categories accodingly';

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
        $cpds = Cpd::all();
        $filtered = $cpds->filter(function ($cpd){
            if ($cpd->ticket){
                return $cpd;
            }
        });

        foreach ($filtered as $cpdTicket){
//            dd($cpdTicket->event());
//         add cpd_category to events table and the set the category.
        }
    }
}
