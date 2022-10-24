<?php

namespace App\Console\Commands;

use App\Billing\Invoice;
use App\Note;
use App\Users\User;
use Illuminate\Console\Command;
use Mockery\Exception;

class backdateUserNotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backdate:notes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will backdate user notes for sales';

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
        $list = [

        ];

        // Jarrod This is the agent who will be assigned.
        $agent = User::find('3925');

        $invoices = Invoice::whereIn('reference', $list)
            ->whereDoesntHave('note')
            ->get();

        $this->info("We have ".count($invoices)." Invoices");

        try{
            $invoices->each(function ($invoice) use($agent){

                if ($invoice->type == 'subscription'){
                    $note = new Note([
                        'type' => 'subscription_upgrade_procedure',
                        'created_at' => $invoice->created_at,
                        'updated_at' => $invoice->updated_at,
                        'description' => "CPD subscription upgraded to ".$invoice->user->subscription('cpd')->plan->name." ".$invoice->user->subscription('cpd')->plan->interval."ly",
                        'logged_by' => $agent->first_name .' '.$agent->last_name,
                        'commision_claimed' => true
                    ]);

                    $note->invoice()->associate($invoice);
                    $invoice->user->notes()->save($note);
                    $this->info("We have just fixed ".$invoice->reference. " for ".$agent->first_name.' '.$agent->last_name);

                }elseif ($invoice->type == 'event'){
                    if ($invoice->ticket){
                        $note = new Note([
                            'type' => 'event_registration',
                            'created_at' => $invoice->created_at,
                            'updated_at' => $invoice->updated_at,
                            'description' => "I have registered ".$invoice->user->first_name." ".$invoice->user->last_name." for ".$invoice->ticket->event->name." at ". $invoice->ticket->venue->name,
                            'logged_by' => $agent->first_name .' '.$agent->last_name,
                            'commision_claimed' => true
                        ]);

                        $note->invoice()->associate($invoice);
                        $invoice->user->notes()->save($note);
                        $this->info("We have just fixed ".$invoice->reference. " for ".$agent->first_name.' '.$agent->last_name);
                    }

                }elseif($invoice->type == 'store'){
                    $note = new Note([
                        'type' => 'store_items',
                        'created_at' => $invoice->created_at,
                        'updated_at' => $invoice->updated_at,
                        'description' => "Store Item purchased for ".$invoice->user->first_name." ".$invoice->user->last_name,
                        'logged_by' => $agent->first_name .' '.$agent->last_name,
                        'commision_claimed' => true
                    ]);

                    $note->invoice()->associate($invoice);
                    $invoice->user->notes()->save($note);
                    $this->info("We have just fixed ".$invoice->reference. " for ".$agent->first_name.' '.$agent->last_name);
                }

            });
        }catch (\Exception $exception){
            request($exception);
        }
    }
}
