<?php

namespace App\Console\Commands;

use App\Act;
use App\ActList;
use Illuminate\Console\Command;

class GenerateActDescripton extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:act_description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate the acts descrption.';

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
        $emptyActs = Act::where('description', null)->where('content', null)->get();
        foreach ($emptyActs as $emptyAct){
            $emptyAct->delete();
        }

        $acts = ActList::where('description', null)->get();
        foreach ($acts as $act){
            $this->info('saving act..');
            $act->update([
                'description' => ($act->acts->first()->description ? : "N/A")
            ]);
        }
    }
}
