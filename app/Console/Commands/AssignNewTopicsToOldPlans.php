<?php

namespace App\Console\Commands;

use App\Subscriptions\Models\CustomPlanFeature;
use App\Subscriptions\Models\Feature;
use App\Subscriptions\Models\Plan;
use Illuminate\Console\Command;

class AssignNewTopicsToOldPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assign:new_topics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will assign topics to old subscribers on old packages.';

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
        $topics = [
            'auditing-2018',
            'ifrs-2018',
            'ifrs-for-sme-2018',
            'taxation-2018',
            'cipc-Notices-2018',
            'sars-Operations-2018',
            'labour-2018',
            'other-Legilation-updates-2018',
        ];

        $customPlanFeatures = CustomPlanFeature::whereIn('plan_id', ['9', '10'])->get();
        foreach ($customPlanFeatures as $custom){
            $this->info('We are removing topics for '.$custom->user_id);
            $shouldBeSaved = array_diff($custom->features, $topics);
            $custom->features = collect($shouldBeSaved)->flatten();
            $custom->save();
        }
    }
}
