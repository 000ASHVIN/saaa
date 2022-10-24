<?php

namespace App\Console\Commands;

use App\Presenters\Presenter;
use Illuminate\Console\Command;

class UpdatePresenterPositions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presenter:positions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update the presenter positions on the website';

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
        $presenters = Presenter::all()->sortByDesc('created_at');
        $i = 1;
        foreach ($presenters as $presenter){
            $presenter->update(['position' => $i]);
            $this->info('The position of '.$presenter->name.' presenter is now '.$i);
            $i ++;
        }
    }
}
