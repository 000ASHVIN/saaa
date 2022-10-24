<?php

namespace App\Console\Commands;

use App\Users\User;
use Illuminate\Console\Command;
use App\Billing\InvoiceRepository;
use App\Subscriptions\Models\Plan;
use Maatwebsite\Excel\Facades\Excel;

class GenerateDecemberInvoicesForOldSubscriptions extends Command
{
    private $invoiceRepository;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'december:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate December Invoices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(InvoiceRepository $invoiceRepository)
    {
        parent::__construct();
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $results = Excel::load(public_path('dec.xls'), function($reader) {
        })->get();

        $lookup = [
            'monthly-accounting-only' => 4,
            'yearly-accounting-and-tax' => 5,
            'monthly-accounting-and-tax' => 5
        ];

        foreach ($results->unique('user_id') as $result) {
            $plan = Plan::find($lookup[$result->slug]);
            $user = User::with('invoices')->find($result->user_id);

            if($user && $user->invoices->contains('type', 'subscription')) {
                $this->info("Generating December invoice for: {$user->email} : {$user->id}");
                $this->invoiceRepository->createSubscriptionInvoice($user, $plan);
            }
        }
    }
}
