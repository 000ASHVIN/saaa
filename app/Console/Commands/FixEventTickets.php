<?php

namespace App\Console\Commands;
error_reporting(0);

use App\AppEvents\Event;
use App\AppEvents\Ticket;
use App\Jobs\FileForRob;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class FixEventTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:TicketNames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will fix ticket names';
    /**
     * @var Ticket
     */
    private $ticket;

    /**
     * Create a new command instance.
     *
     * @param Ticket $ticket
     */
    public function __construct(Ticket $ticket)
    {
        parent::__construct();
        $this->ticket = $ticket;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $events = \App\AppEvents\Event::where('start_date' ,'>=', '2018-04-01')->where('end_date', '<=', '2019-03-31')->get();
        $ids = [];


        foreach ($events as $event){
            $ids[] = $event->id;
        }

        $tickets = Ticket::whereIn('event_id', $ids)->get();

        $file = fopen(storage_path("report.csv"), "w");

        fwrite($file, "NAMES OF THE LEARNER, SURNAME OF THE LEARNER, ID NUMBER, ID NUMBER TYPE,TOPIC / DESCRIPTION OF TRAINING,START DATE OF TRAINING,END DATE OF TRAINING,NQF LEVEL,NAME OF THE EMPLOYER,EMPLOYER SDL NUMBER,EMPLOYER CONTACT DETAILS,NAME OF THE TRAINING PROVIDER / PROFESSIONAL BODY,TRAINING PROVIDER OR PROFESSIONAL BODY ACCREDITATION / REGISTRATION NUMBER,TRAINING PROVIDER / PROFESSIONAL BODY CONTACT DETAILS,IS TRAINING PROVIDER PRIVATE /PUBLIC ,LEARNER PROVINCE,LEARNER LOCAL/DISTRICT MUNICIPALITY ,SPECIFY LEARNER RESIDENTIAL  AREA,IS THE LEARNER RESIDENTIAL AREA URBAN / RURAL,TRAINING COST,RACE,GENDER,AGE,DISABILITY,NON-RSA CITIZEN,\n");
        
        foreach ($tickets as $ticket){
            $province = (count($ticket->user->addresses) ? $ticket->user->addresses->first()->province : "N/A");
            $city = (count($ticket->user->addresses) ? $ticket->user->addresses->first()->city : "N/A");
            $price = ($ticket->event->type == 'webinar' ? "399" : "650");
            $gender = ($ticket->user->profile->gender ? : "-");
            $company = ($ticket->user->profile->company ? preg_replace("/[^a-zA-Z0-9]/", "", $ticket->user->profile->company) : "N/A");
            $event = preg_replace("/[^a-zA-Z0-9]/", "", $ticket->event->name);

            fwrite($file, "{$ticket->user->first_name},{$ticket->user->last_name},{$ticket->user->id_number},-,{$event},{$ticket->event->start_date},{$ticket->event->start_date},-,{$company},-,-,-,-,-,-,{$province},-,{$city},-,{$price},-,{$gender},-,-,-,\n");
        }

        fclose($file);


//        $tickets = $this->ticket->where('name', 'Online webinar / recording')->get();
//        $this->info("We have ".count($tickets));
//
//        foreach ($tickets as $ticket){
//            $ticket->update([
//                'name' => 'Online admission',
//                'description' => 'Online Admission'
//            ]);
//            $this->info("we are done with ".$ticket->id);
//        }
    }
}
