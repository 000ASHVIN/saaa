<?php

namespace App\Console\Commands;

use App\Subscriptions\Models\CustomPlanFeature;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;
use Illuminate\Console\Command;

class UpdateComprehensiveFeatures extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:comprehensive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will add more topics to the comprehensive features';

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
       $features = CustomPlanFeature::all();
       foreach ($features as $feature){
           $feature->features = collect(array_merge(['practice-management-update', $feature->features]))->flatten();
           $feature->save();
       }
    }
}
