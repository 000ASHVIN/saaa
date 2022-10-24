<?php

namespace App\Console\Commands;

use App\Note;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class send_ptp_arrangements_to_accounts_daily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:ptp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send accounts a list of all the promise to pay clients.';
    /**
     * @var Note
     */
    private $note;

    /**
     * Create a new command instance.
     *
     * @param Note $note
     */
    public function __construct(Note $note)
    {
        parent::__construct();
        $this->note = $note;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notes = $this->note->with('user')->where('type', 'payment_arrangement')->where('completed', false)->get();
        Mail::send('emails.billing.ptp', ['notes' => $notes], function ($m) use ($notes) {
            $m->from(config('app.email'), config('app.name'));
            $m->to(config('app.email'), config('app.name'))->subject('Pending Promise to pay clients');
        });
    }
}
