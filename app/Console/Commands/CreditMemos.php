<?php

namespace App\Console\Commands;

use App\Billing\Transaction;
use App\CreditMemo;
use App\Repositories\CreditMemo\CreditMemoRepository;
use Illuminate\Console\Command;

class CreditMemos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit:memos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will create credit memos for transactions where the type is credit note';
    /**
     * @var CreditMemoRepository
     */
    private $creditMemoRepository;

    public function __construct(CreditMemoRepository $creditMemoRepository)
    {
        parent::__construct();
        $this->creditMemoRepository = $creditMemoRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $transactions = Transaction::with('invoice', 'user')->where('display_type', 'Credit Note')->get();
        $transactions->each(function ($transaction){
            $this->info('We are busy with '.$transaction->invoice->reference);
            $this->creditMemoRepository->store($transaction);
            $this->info('We are done with '.$transaction->invoice->reference);
        });
    }
}
