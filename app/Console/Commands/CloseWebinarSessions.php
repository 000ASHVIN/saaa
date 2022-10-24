<?php

namespace App\Console\Commands;

use App\AppEvents\Pricing;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Console\Command;

class CloseWebinarSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'close:webinar-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will close all past webinar sessions on a daily basis';

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
        $pricingsGet = Pricing::with('event', 'webinars')->get();
        $pricings = $pricingsGet->filter(function ($pricing){
           if ($pricing->venue->type == 'online'){
               return $pricing;
           }
        });

        if (count($pricings) > 0){
            foreach ($pricings as $pricing){
                if ($pricing->event && $pricing->event->end_date < Carbon::today()->startOfDay()){
                    if ($pricing->webinars){
                        foreach ($pricing->webinars as $webinar){
                            if ($webinar->is_active != 0){
                                $this->info($webinar->pricing->name. " - We are closing this webinar session for ".strtolower(ucfirst($pricing->event->name)). ' now.');
                                $webinar->update(['is_active' => false]);
                                $webinar->save();
                            }
                        }
                    }
                }
            }
            $this->info('We have no webinar sessions to close.');
        }
    }
}
