<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Users\User;
use App\Billing\Transaction;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExtractDebtors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:debtors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract debtors per category';

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
        $data = [];

        $to = Carbon::parse('31 December 2016')->endOfDay();

        $transactions = Transaction::with('user')->where('date', '<=', $to)->get();

        $this->info("Extracting Debtors as at " . $to->format('Y-m-d'));

        foreach ($transactions->groupBy('category') as $category => $transactions) {

            foreach ($transactions->groupBy('user_id') as $user => $transactions) {

                $user = User::find($user);

                if($user) {
                    $total = 0;
                    foreach ($transactions as $transaction) {
                        $this->info("Exporting Row for $user->id");

                        $total += $transaction->where('user_id', $user->id)->where('type', 'debit')->sum('amount') -
                                $transaction->where('user_id', $user->id)->where('type', 'credit')->sum('amount');
                    }

                    if($total > 0)
                    {
                        $data[$category][] = [
                            $user->first_name,
                            $user->last_name,
                            $user->email,
                            number_format(($total / 100), 2)
                        ];
                    }
                }
            }
        }

        // dd($data);

        Excel::create('Debtors Report', function($excel) use($data) {
            foreach ($data as $key => $users) {
                $excel->sheet(ucfirst($key), function($sheet) use($users) {
                    $sheet->appendRow([
                        'First Name',
                        'Last Name',
                        'Email',
                        'Balance'                   
                    ]);

                    foreach ($users as $user) {
                        $sheet->appendRow($user);
                    }

                });
            }
        })->store('xls', storage_path('exports'));
    }
}
