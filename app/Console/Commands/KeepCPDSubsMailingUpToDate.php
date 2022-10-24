<?php

namespace App\Console\Commands;

use App\Body;
use App\Jobs\UnlinkSubscriberFromList;
use App\Jobs\UploadCpdSubsToSendinBlue;
use App\Repositories\Sendinblue\SendingblueRepository;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Sendinblue\Mailin;
use Illuminate\Support\Facades\Log;

class KeepCPDSubsMailingUpToDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MailingUpdate:CPDSubs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will ensure that out mailing platform stays up to date.';

    use DispatchesJobs;

    /**
     * @var SendingblueRepository
     */
    private $sendingblueRepository;
    private $cpdId;
    private $CourseId;

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
        $data['list_parent']= env('SUBSCRIPTIONS_FOLDER_ID');
        $user = $this->call()->get_lists($data);
        $collect = collect($user['data']);
        $cpdId = $collect->pluck('id')->toArray();
        $this->cpdId = $cpdId;
		
		
		$datac['list_parent']= env('COURSE_FOLDER_ID');
        $course = $this->call()->get_lists($datac);
        $collectCourse = collect($course['data']);
        $CourseId = $collectCourse->pluck('id')->toArray();
        $this->CourseId = $CourseId;
		
        // add COURSE_FOLDER_ID to env first to run this code
        $planList = [];
        $subscriptions = Subscription::with('user')->where('name','cpd')->get()->groupBy('plan_id');

        foreach ($subscriptions as $key => $value){
            $plan = Plan::find($key);
            $planList[] = $plan->id;
			//$this->remove_subscriptions($value);
            if ($plan->list_id){
                $this->import_or_remove($value, $plan);
            }else{
                $this->info('Creating List..');
                $this->create_list($plan);
                $this->import_or_remove($value, $plan);
            }
        }
        $subscriptionCourse = Subscription::with('user')->where('name','course')->get()->groupBy('plan_id');

        foreach ($subscriptionCourse as $key => $value){
            $plan = Plan::find($key);
            $planList[] = $plan->id;
           // $this->remove_course_subscriptions($value);
            if ($plan->list_id){
                $this->course_import_or_remove($value, $plan);
            }else{
                $this->info('Creating List..');
                $this->create_list_course($plan);
                $this->course_import_or_remove($value, $plan);
            }
        }
    
        
    }

    private function create_list($plan)
    {
        $list = $this->call()->create_list([
            'list_name' => $plan->name . ' ' . ucwords($plan->interval) . 'ly',
            'list_parent' => env('SUBSCRIPTIONS_FOLDER_ID')
        ]);

        $this->info('Saving list ID to plans table..');
        $plan->list_id = $list['data']['id'];
        $plan->save();
    }

    private function create_list_course($plan)
    {
        $list = $this->call()->create_list([
            'list_name' => $plan->name . ' ' . ucwords($plan->interval) . 'ly',
            'list_parent' => env('COURSE_FOLDER_ID')
        ]);

        $this->info('Saving list ID to plans table..');
        $plan->list_id = $list['data']['id'];
        $plan->save();
    }

    public function call()
    {
        $mailin = new Mailin('https://api.sendinblue.com/v2.0', env('SENDINBLUE_KEY'));
        return $mailin;
    }

    private function import_or_remove($value, $plan)
    {
        foreach ($value as $subscriber) {
            if ($subscriber->active() && !$subscriber->canceled() && !$subscriber->suspended()){
                if ($subscriber->user->list_id != null){
                    if ($subscriber->user->list_id != $plan->list_id && $subscriber->name == 'cpd'){
                        $this->info('Subscriber plan changed '.$subscriber->user->email);
                        $this->dispatch(new UnlinkSubscriberFromList($plan, $subscriber));
                        $this->dispatch(new UploadCpdSubsToSendinBlue($plan, $subscriber));
                    }
                }else{
                    $this->info('Adding subscriber '.$subscriber->user->email);
                    $this->dispatch(new UploadCpdSubsToSendinBlue($plan, $subscriber));
                }
            }else{
                if($subscriber->name == 'cpd'){
                    $this->info('Removing subscriber '.$subscriber->user->email);
                    $this->dispatch(new UnlinkSubscriberFromList($plan, $subscriber));
                    $subscriber->user->list_id = null;
                    $subscriber->user->save();
                }
            }
        }
    }
    private function course_import_or_remove($value, $plan)
    {
        foreach ($value as $subscriber) {
            if ($subscriber->active()){
                    if ($subscriber->name == 'course'){
                        $this->info('Adding subscriber '.$subscriber->user->email);
                        $this->dispatch(new UploadCpdSubsToSendinBlue($plan, $subscriber));
                    }
                
            }
        }
    }
    private function remove_subscriptions($value)
    {
        foreach ($value as $subscriber) {
                
            $data['email']=$subscriber->user->email;
            $user = $this->call()->get_user($data);
            \Log::info(json_encode($user));
			if($user["code"]=="success"){
				$key = array_search($subscriber->user->list_id,$user['data']["listid"]);
				if($key)
                {
                   unset($user['data']["listid"][$key]); 
                }
				foreach($user['data']["listid"] as $k=>$keys){
					if(in_array($keys,$this->CourseId))
					{
						unset($user['data']["listid"][$k]);
					}
                }
                if(!empty($user['data']["listid"])){
				    $datas = [
                        'email' => $subscriber->user->email,
                        'listid_unlink' => $user['data']["listid"]
                    ];
                    $this->call()->create_update_user($datas);
                }
            }
        }
    }
	private function remove_course_subscriptions($value)
    {
        foreach ($value as $subscriber) {
                
            $data['email']=$subscriber->user->email;
            $user = $this->call()->get_user($data);
            if($user["code"]=="success"){
                $key = array_search($subscriber->user->list_id,$user['data']["listid"]);
                if($key)
                {
                   unset($user['data']["listid"][$key]); 
                }
				$datas = [
                    'email' => $subscriber->user->email,
                    'listid_unlink' => $user['data']["listid"]
                ];
                $this->call()->create_update_user($datas);
            }
        }
    }
}




