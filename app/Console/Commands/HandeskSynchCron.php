<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Handesk;
use Carbon\Carbon;
use App\SupportTicket;
use App\Thread;
use App\Users\User;
use App\AgentGroup;
use Illuminate\Support\Str;
use Sendinblue\Mailin;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Api\ContactsApi;
use GuzzleHttp\Client;
use Exception;
use App\Repositories\Sendinblue\SendingblueRepository;

class HandeskSynchCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handesk:sync';
    private $sendingblueRepository;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SendingblueRepository $sendingblueRepository)
    {
        parent::__construct();
        $this->sendingblueRepository = $sendingblueRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \DB::enableQueryLog();

        $this->syncAgentTokens();

        $this->syncAgentGroupWithTeam();

        // Get updated tickets from handesk
        $time = Carbon::now()->subHours(24);
        $updated_tickets = Handesk\Ticket::where('updated_at', '>=', $time)->get();
        $this->info('Found tickets in handesk: '.$updated_tickets->count());

        // Fetch tickets from website
        $threads = Thread::whereIn('handesk_ticket_id', $updated_tickets->pluck('id'))
                    ->get()
                    ->keyBy('handesk_ticket_id');
        $this->info('Found tickets on website: '.$threads->count());

        foreach($updated_tickets as $ticket) {

            /*
            * Sync Ticket
            */
            $thread = $this->createOrUpdateTicket($ticket, $threads);
            
            /*
            * Sync agent and team
            */
            $this->syncAgentAndTeam($ticket, $thread);

            /*
            * Sync Comments
            */
            $this->syncComments($ticket, $thread);

        }

        // $log = \DB::getQueryLog();
        // dump(count($log));
    }

    /*
    * Function will get updated users from handesk and match the login tokens to the website users
    */
    public function syncAgentTokens() {

        $handesk_agents = Handesk\User::all();
        $this->info('Found agents in handesk: '.$handesk_agents->count());

        // Fetch agents from website
        $agents = User::whereIn('email', $handesk_agents->pluck('email'))
                    ->get();
        $this->info('Found agents on website: '.$agents->count());

        foreach($agents as $agent) {

            $handesk_agent = $handesk_agents->filter(function($item) use($agent) {
                return strtolower($item->email) == strtolower($agent->email);
            })->first();

            if($handesk_agent) {

                if($handesk_agent->login_token) {

                    // Update the website user to add token
                    if($handesk_agent->login_token != $agent->handesk_token) {
                        $agent->update([
                            'handesk_token' => $handesk_agent->login_token
                        ]);
                    }

                }
                else {

                    // Generate new token and update to handesk and website
                    $login_token = Str::random(54);

                    $handesk_agent->update([
                        'login_token' => $login_token
                    ]);

                    $agent->update([
                        'handesk_token' => $login_token
                    ]);

                }

                // Update handesk user id in website
                if($agent->handesk_id != $handesk_agent->id) {
                    $agent->update([
                        'handesk_id' => $handesk_agent->id
                    ]);
                }

            }

        }


    }

    public function createOrUpdateTicket($ticket, $threads) {

        $thread = null;
        if(isset($threads[$ticket->id])) {

            // Update case
            $this->info('Update: '.$ticket->title);

            $thread = $threads[$ticket->id];
            $thread->update([
                'priority' => $ticket->priority,
                'status' => convertToWebsiteThreadStatus($ticket->status)
            ]);

        }
        else {

            // Create case
            $this->info('Create: '.$ticket->title);

            $user = null;
            if($ticket->requester) {

                // Find user in website
                $email = trim($ticket->requester->email);
                $user = User::where('email',$email)->first();

                if($user) {

                    // Create thread on website
                    $thread = Thread::create([
                        'title' => $ticket->title, 
                        // 'category' => $category_title, 
                        'description' => $ticket->body,
                        'handesk_ticket_id' => $ticket->id,
                        'user_id' => $user->id,
                        'priority' => $ticket->priority,
                        'is_login' => 0,
                        'mobile_number' => '',
                        'status' => convertToWebsiteThreadStatus($ticket->status)
                    ]);

                }
                else {
                    $this->info('User not found: '.$ticket->title.' - '.$email);
                }

                // Manage handesk requester in website
                $this->syncRequester($ticket->requester, $user);

            }

        }

        return $thread;

    }

    public function syncRequester($requester, $user) {

        if($user) {
            if($requester->name == 'Unknown' || $requester->name == 'Unknown.') {
                $requester->update([
                    'name' => $user->first_name.' '.$user->last_name
                ]);
            }
        }
        else {
            
            if($requester->name != 'Unknown.') {

                $identifier = $requester->email;
                $data = [
                    'email' => $requester->email,
                    'listIds' => [
                        (int)env('SENDINBLUE_HANDESK_LIST_ID')
                    ]
                ];
            
                $this->sendingblueRepository->createUpdateContact($data);
               
                if($requester->name == 'Unknown') {
                    $requester->update([
                        'name' => 'Unknown.'
                    ]);
                }

            }

        }

    }

    public function syncAgentAndTeam($ticket, $thread) {
        
        if($thread) {
                
            // Compare current agent emails in website and handesk
            $website_agent_email = $thread->agent?($thread->agent->email):null;
            $handesk_agent_email = $ticket->user?($ticket->user->email):null;

            if($website_agent_email != $handesk_agent_email) {

                $agent = null;
                if($handesk_agent_email) {
                    $agent = User::where('email', $handesk_agent_email)->first();
                }

                $thread->update([
                    'agent_id' => $agent?$agent->id:0
                ]);

            }

            // Compare current agent group in website and handesk
            $website_team_id = $thread->agentGroup?$thread->agentGroup->handesk_team_id:null;
            $handesk_team_id = $ticket->team?($ticket->team->id):null;

            if($website_team_id != $handesk_team_id) {

                $agentGroup = null;
                if($handesk_team_id) {
                    $agentGroup = AgentGroup::where('handesk_team_id', '=', $handesk_team_id)->first();
                }

                $thread->update([
                    'agent_group_id' => $agentGroup?$agentGroup->id:0
                ]);

            }

        }

    }

    public function syncComments($ticket, $thread) {
        
        if($thread && count($ticket->comments)) {

            $comments = $ticket->comments;
            $support_tickets = $thread->tickets;
            $handesk_comment_ids = $support_tickets->pluck('handesk_comment_id')->toArray();

            foreach($comments as $comment) {

                $agent = null;
                $user_id = 0;
                if($comment->user) {
                    $agent = User::where('email',$comment->user->email)->first();
                }
                else {
                    $user_id = $thread->user_id;
                }

                if(!in_array($comment->id, $handesk_comment_ids)) {

                    $support_ticket = SupportTicket::create([
                        'subject' => $thread->title,
                        'description' => $comment->body,
                        'handesk_comment_id' => $comment->id,
                        'agent_id' => $agent?$agent->id:0,
                        'thread_id' => $thread->id,
                        'user_id' => $user_id?$user_id:0
                    ]);
                    $support_ticket->save();
                    $thread->addReply();

                }

            }

        }

    }
    
    public function syncAgentGroupWithTeam() {

        $time = Carbon::now()->subHours(24);
        $updated_teams = Handesk\Team::where('updated_at', '>=', $time)->get();
        $this->info('Found teams in handesk: '.$updated_teams->count());

        // Fetch agent groups from website
        $agentGroups = AgentGroup::whereIn('handesk_team_id', $updated_teams->pluck('id'))
                    ->get()
                    ->keyBy('handesk_team_id');
        $this->info('Found teams on website: '.$agentGroups->count());

        foreach($updated_teams as $team) {

            /*
            * Create or update Agent Group in website
            */
            $agentGroup = $this->createOrUpdateAgentGroup($team, $agentGroups);

            /*
            * Sync Group members
            */
            $agentGroup = $this->syncAgentGroupMembers($team, $agentGroup);

        }

    }

    public function createOrUpdateAgentGroup($team, $agentGroups) {

        $agentGroup = null;
        if(isset($agentGroups[$team->id])) {

            // Update case
            $this->info('Update: '.$team->name);

            $agentGroup = $agentGroups[$team->id];
            $agentGroup->update([
                'name' => $team->name
            ]);

        }
        else {

            // Create case
            $this->info('Create: '.$team->name);

            // Create thread on website
            $agentGroup = AgentGroup::create([
                'name' => $team->name,
                'handesk_team_id' => $team->id
            ]);

        }

        return $agentGroup;


    }

    public function syncAgentGroupMembers($team, $agentGroup) {

        $agent_group_member_ids = collect();
        if($team->members) {

            foreach($team->members as $handesk_member) {

                // Find or create user in website
                $user = User::where('email','=',$handesk_member->email)->first();
                if($user) {
                    $agent_group_member_ids->push($user->id);
                }
    
            }

            if($agent_group_member_ids->count()) {
                $agentGroup->agents()->sync($agent_group_member_ids->toArray());
            }

        }

    }

}
