<?php

namespace App\Imports;

use App\AppEvents\Date;
use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Ticket;
use App\AppEvents\Venue;
use App\Http\Requests\ImportRequest;
use App\Import;
use App\ImportBatch;
use App\Jobs\SendNewMemberShipInviteToUser;
use App\Mailer;
use App\Mailers\UserMailer;
use App\Subscriptions\Models\Plan;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use DB;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Course;
use App\InvoiceOrder;
use App\Billing\Item;
use App\Note;

class CourseImportProvider implements ImportProviderInterface
{
    use DispatchesJobs;
    protected $userMailer;

    public function __construct(UserMailer $userMailer)
    {
        $this->userMailer = $userMailer;
    }

    use BasicImportProvider;

    protected $actions = [
        'registerForEvent' => [
            'function_name' => 'registerForEvent',
            'text' => 'Register for event',
            'view' => 'admin.imports.members.register-for-event',
            'validation_rules' => [
                'event_id' => ['required'],
                'venue_id' => ['required'],
                'date_id' => ['required']
            ]
        ]
    ];

    public function import(ImportRequest $request)
    {
        //Parse CSV
        $dataFile = $request->file('data');
        if (!$dataFile->isValid()) {
            alert()->error('Invalid file', 'Error');
            return back();
        }

        $rows = Excel::load($dataFile, function ($reader) {
        })->get();
        
        $importUsers = collect([]);
        $invalid = collect([]);
        $duplicates = collect([]);


        foreach ($rows as $index => $row) {
            $rowInvalid = [];

            $email = strtolower(trim($row->email));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $rowInvalid[] = 'Invalid email address.';
            }
            if (!$email || $email == '') {
                $rowInvalid[] = 'Blank email address.';
            }

            $firstName = ucwords(trim($row->first_name));
            if (!$firstName || $firstName == '') {
                $rowInvalid[] = 'Blank first_name.';
            }

            $lastName = ucwords(trim($row->last_name));
            if (!$lastName || $lastName == '') {
                $rowInvalid[] = 'Blank last_name.';
            }


          
            if (!$importUsers->has($email))
                $importUsers->put($email, $row);
            else {
                if ($duplicates->has($email))
                    $duplicates->put($email, $duplicates->get($email) + 1);
                else
                    $duplicates->put($email, 1);
            }
        }

//        $existingUsers = User::with('subscriptions', 'subscriptions.plan')->whereIn('email', $importUsers->keys()->toArray())->get();
        $existingUsers = collect();
        $newUsers = collect();

        foreach ($importUsers as $user){
            $existing = User::with('subscriptions', 'subscriptions.plan')->where('email', $user->email)->first();
            if ($existing){
                $existingUsers->push($user);
            }else{
                $newUsers->push($user);
            }
        }

//        $newUsers = $importUsers;
//
//        foreach ($existingUsersEmails as $existingUserEmail) {
//            $newUsers->forget($existingUserEmail);
//        }
        $import = Import::create([
            'import_provider_id' => $this->provider->id,
            'title' => $title = $request->get('title'),
            'description' => $request->get('description'),
            'data' => $rows,
            'total_count' => count($rows->toArray())
        ]);

        try {
            DB::transaction(function () use ($request, $import, $newUsers, $existingUsers, $invalid, $duplicates, $rows) {
                //Import provider specific data
                $planId = $request->get('plan_id');
                $existingAccount = $request->get('exiting_account');

                if ($planId){
                    $plan = Course::findOrFail($planId);
                }else{
                    $plan = null;
                }

                // If existing account than change plan id as pr selected
                // if ($existingAccount) 
                 {
                    foreach($existingUsers as $existingUser)
                    {  
                        $existing = User::with('subscriptions', 'subscriptions.plan')->where('email', $existingUser->email)->first();
                        $subscriptions = $existing->subscriptions()->where('name','course')->where('plan_id',$plan->monthly_plan_id)->first();
                        
                        if ($subscriptions && $subscriptions->course_type == 'semester' && $plan->type_of_course=='semester')
                        {
                            
                            ImportBatch::create([
                                'import_id' => $import->id,
                                'importable_id' => $existing->id,
                                'importable_type' => Course::class
                            ]);
                        
                                       /* Safety Check */
                           $subscriptions->completed_semester = $subscriptions->completed_semester+1;
                           if( $subscriptions->completed_semester == $plan->no_of_semesters)
                           {
                            $subscriptions->completed_order = 0;
                           }
                           $subscriptions->save();
                         $order =  $this->generateInvoice($existing,$plan,$request,$plan->semester_price,'monthly');

                           $note = new Note([
                            'type' => 'course_subscription',
                            'description' => "I have generated Purchase order for ".$existing->first_name." ".$existing->last_name." for Video ".$plan->title,
                            'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
                        ]);
                        $note->save();
                        $order->note()->associate($note);
                        
                        $order->addItems($this->products);
                        $order->autoUpdateAndSave();
                        // dd($order);
                        }
                        // dd('yes');

                        $subscriptionsYearly = $existing->subscriptions()->where('name','course')->where('plan_id',$plan->yearly_plan_id)->first();
                        if ($subscriptionsYearly && $subscriptionsYearly->course_type == 'semester' && $plan->type_of_course=='semester')
                        {
                        
                            ImportBatch::create([
                                'import_id' => $import->id,
                                'importable_id' => $existing->id,
                                'importable_type' => Course::class
                            ]);
                                       /* Safety Check */
                           $subscriptionsYearly->completed_semester = $subscriptionsYearly->completed_semester+1;
                           if( $subscriptionsYearly->completed_semester == $plan->no_of_semesters)
                           {
                            $subscriptionsYearly->completed_order = 0;
                           }
                           $subscriptionsYearly->save();
                        if(!$subscriptionsYearly->full_payment){
                                $order =  $this->generateInvoice($existing,$plan,$request,$plan->semester_price,'yearly');

                                $note = new Note([
                                    'type' => 'course_subscription',
                                    'description' => "I have generated Purchase order for ".$existing->first_name." ".$existing->last_name." for Video ".$plan->title,
                                    'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
                                ]);
                                $note->save();
                                $order->note()->associate($note);
                    
                                $order->addItems($this->products);
                                $order->autoUpdateAndSave();
                           }
                        // dd($order);
                        }
                    }
                }
                // End

               /* foreach ($newUsers as $newUser) {
                    $newUser->save();

                    $newUser->update(['settings' => [
                        'send_invoices_via_email' => '1',
                        'event_notifications' => '1',
                        'sms_notifications' => '1',
                        'marketing_emails' => '1',
                    ]]);
                    $newUser->save();
                    // Create New Profile
                    $newUser->profile()->create([]);

                    // Create New wallet for user
                    $newUser->wallet()->create([]);

                    // Create New CPD Subscription
                    if ($plan){
                        $subscription = $newUser->fresh()->newSubscription('cpd', $plan)->create();

                        if ($request->free_subscription == true){
                            $subscription->billable = 0;
                            $subscription->save();
                        }
                    }

                    $mailerId = $request->get('mailer_id');
                    if ($mailerId && $mailerId > 0) {
                        $this->dispatch(new SendNewMemberShipInviteToUser($newUser));
//                        $this->userMailer->sendNewMembershipWithOneTimePasswordTo($newUser, $mailer->view);
                    }

                    ImportBatch::create([
                        'import_id' => $import->id,
                        'importable_id' => $newUser->id,
                        'importable_type' => User::class
                    ]);
                }*/

//                $updateExisting = boolval($request->get('update_existing_members', false));

//                if ($updateExisting) {
//                    foreach ($existingUsers as $existingUser) {
//                        if ($existingUser->subscribed('cpd')){
//                            /*
//                             * In a perfect world we would change their plan to the provided.
//                             * Do Nothing
//                             * $existingUser->subscription('cpd')->changePlan($plan)->save();
//                             */
//                        }else{
//                            $existingUser->newSubscription('cpd', $plan)->create();
//                        }
//                    }
//                }

                $import->update([
                    'imported_count' => count($existingUsers),
                    'imported' => $existingUsers->toArray(),
                    'existing_count' => 0,
                    'existing' =>[],
                    'invalid_count' => count($invalid),
                    'invalid' => $invalid->toArray(),
                    'duplicates_count' => count($duplicates),
                    'duplicates' => $duplicates->toArray(),
                    'completed_successfully' => true
                ]);

                return $import->fresh();
            });
        } catch (\Exception $e) {
            $import->error = $e->getMessage();
            throw $e;
        }

        return $import->fresh();
    }

    public function getViewData()
    {
        $course = Course::where('type_of_course','semester')->get()->sortBy('id')->pluck('title', 'id')->toArray();
        $mailerOptions = [0 => 'None'] + Mailer::all()->sortBy('id')->pluck('title', 'id')->toArray();
        return compact('course', 'mailerOptions');
    }

    public function registerForEvent($request, $import)
    {
        $batch = $import->batches()->with(['importable', 'importable.tickets'])->get();

        if (!$batch)
            throw new \Exception('Cannot use null batch');

        $users = $batch->pluck('importable');

        if (!$users)
            throw new \Exception('Cannot use null users (importable)');

        $event = Event::findOrFail($request->get('event_id'));
        $venue = Venue::findOrFail($request->get('venue_id'));
        $date = Date::findOrFail($request->get('date_id'));

        $pricing = Pricing::where('event_id', $event->id)->where('venue_id', $venue->id)->first();

        if (!$pricing)
            throw new \Exception('Null pricing. Check event(' + $request->get('event_id') + ') and venue(' + $request->get('venue_id') + ') ids');

        foreach ($users as $user) {
            if ($user->tickets->contains('event_id', $event->id))
                continue;

            $ticket = new Ticket;

            $ticket->code = str_random(20);
            $ticket->name = $pricing->name;
            $ticket->description = $pricing->description;
            $ticket->first_name = $user->first_name;
            $ticket->last_name = $user->last_name;
            $ticket->user_id = $user->id;
            $ticket->event_id = $event->id;
            $ticket->venue_id = $venue->id;
            $ticket->pricing_id = $pricing->id;
            $ticket->invoice_id = 0;
            $ticket->dietary_requirement_id = 0;
            $ticket->email = $user->email;

            $ticket->save();

            $ticket->dates()->save($date);

            $user->tickets()->save($ticket);
        }

        alert()->success('Imported members registered for event.', 'Success');
        return redirect()->route('admin.import.provider.import', [$this->provider->id, $import->id]);
    }

    protected function getRandomBytes($nbBytes = 32)
    {
        $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
        if (false !== $bytes && true === $strong) {
            return $bytes;
        } else {
            throw new \Exception("Unable to generate secure token from OpenSSL.");
        }
    }

    public function generateInvoice($user, $course, $request, $amount,$type)
    {
        $invoice = new InvoiceOrder;
        $invoice->type = 'course';
        $invoice->setUser($user);
        $invoice->save();

        $item = new Item;
        $item->type = 'course';
        $item->name = $course->title;
        $item->description = 'Online Course Access';
        $item->price = $amount;
        $item->discount = '0';
        $item->item_id = $course->id;
        $item->item_type = get_class($course);
        $item->course_type = $type;
        $item->save();

        $this->products[] = $item;

        // Create Course Invoice Entry...
        // DBTable::table('course_invoice')->insert([
        //     'course_id' => $course->id,
        //     'invoice_id' => $invoice->id
        // ]);

        return $invoice;
    }
    protected function generateTemporaryPassword($length)
    {
        return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode($this->getRandomBytes($length + 1))), 0, $length);
    }
}