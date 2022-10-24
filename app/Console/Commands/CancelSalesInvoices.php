<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CancelSalesInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will cancel all sales that was created by agens if older than 30 days.';

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
        //
    }
}
