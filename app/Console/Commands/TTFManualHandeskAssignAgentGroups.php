<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\AgentGroup;
use App\Handesk;
use App\HandeskApi;
use App\Thread;

class TTFManualHandeskAssignAgentGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handesk:assign-agentgroups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command will manually assign unassigned tickets to agent groups on the basis of selected category.';

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
        $threads = Thread::where('agent_group_id', 0)
            ->with('cat')
            ->get();
        foreach($threads as $thread) {

            if($thread->cat && $thread->handesk_ticket_id) {
                dd($thread->id);
                $agentGroups = $thread->cat->agentGroups;
                if($agentGroups->count()) {

                    $agentGroup = $agentGroups[0];
                    $handesk_team_id = $agentGroup->handesk_team_id;
                    if($handesk_team_id) {

                        // Update on website
                        $thread->update([
                            'agent_group_id' => $agentGroup->id
                        ]);

                        // Update on handesk
                        $handesk_ticket = Handesk\Ticket::find($thread->handesk_ticket_id); 
                        if($handesk_ticket) {
                            $handesk_ticket->update([
                                'team_id' => $handesk_team_id
                            ]);
                        }

                    }
                }
            }
        }
    }
}
