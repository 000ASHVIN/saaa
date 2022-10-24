<?php

namespace App\Jobs;
error_reporting(0);

use App\AppEvents\Ticket;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Facades\Excel;

class FileForRob extends Job implements SelfHandling, ShouldQueue
{
    public $ids;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tickets = Ticket::whereIn('event_id', $this->ids)->get();
        Excel::create('report', function($excel) use($tickets) {
            $excel->sheet('sheet', function($sheet) use ($tickets){
                $sheet->appendRow([
                    'NAMES OF THE LEARNER',
                    'SURNAME OF THE LEARNER',
                    'ID NUMBER',
                    'ID NUMBER TYPE',
                    'TOPIC / DESCRIPTION OF TRAINING',
                    'START DATE OF TRAINING',
                    'END DATE OF TRAINING',
                    'NQF LEVEL',
                    'NAME OF THE EMPLOYER',
                    'EMPLOYER SDL NUMBER',
                    'EMPLOYER CONTACT DETAILS',
                    'NAME OF THE TRAINING PROVIDER / PROFESSIONAL BODY',
                    'TRAINING PROVIDER OR PROFESSIONAL BODY ACCREDITATION / REGISTRATION NUMBER',
                    'TRAINING PROVIDER / PROFESSIONAL BODY CONTACT DETAILS',
                    'IS TRAINING PROVIDER PRIVATE /PUBLIC ',
                    'LEARNER PROVINCE',
                    'LEARNER LOCAL/DISTRICT MUNICIPALITY ',
                    'SPECIFY LEARNER RESIDENTIAL  AREA',
                    'IS THE LEARNER RESIDENTIAL AREA URBAN / RURAL',
                    'TRAINING COST',
                    'RACE',
                    'GENDER',
                    'AGE',
                    'DISABILITY',
                    'NON-RSA CITIZEN',
                ]);

                foreach ($tickets as $ticket){
                    $sheet->appendRow([
                        $ticket->user->first_name,
                        $ticket->user->last_name,
                        $ticket->user->id_number,
                        '-',
                        $ticket->event->name,
                        $ticket->event->start_date,
                        $ticket->event->start_date,
                        '-',
                        $ticket->user->profile->company,
                        '-',
                        '-',
                        '-',
                        '-',
                        '-',
                        '-',
                        (count($ticket->user->addresses) ? $ticket->user->addresses->first()->province : "N/A"),
                        '-',
                        (count($ticket->user->addresses) ? $ticket->user->addresses->first()->city : "N/A"),
                        '-',
                        ($ticket->event->type == 'webinar' ? "399" : "650"),
                        '-',
                        ($ticket->user->profile->gender ? : "-"),
                        '-',
                        '-',
                        '-',
                    ]);
                }
            });
        })->store('xls', storage_path('exports'));
    }
}
