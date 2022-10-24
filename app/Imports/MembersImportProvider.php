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

class MembersImportProvider implements ImportProviderInterface
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

        $generateOneTimePasswords = boolval($request->get('generate_temp_passwords', false));


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

            $cell = ucwords(trim($row->cell));
            // if (!$cell || $cell == '') {
            //     $rowInvalid[] = 'Blank cell.';
            // }

            $id_number = ucwords(trim($row->id_number));
            // if (!$id_number || $id_number == '') {
            //     $rowInvalid[] = 'Blank id_number.';
            // }

            $body = ucwords(trim($row->body_id));
            // if (!$body || $body == '') {
            //     $rowInvalid[] = 'Blank body_id.';
            // }

            $membershipNumber = ucwords(trim($row->membership_number));
            // if (!$membershipNumber || $membershipNumber == '') {
            //     $rowInvalid[] = 'Blank membership_number.';
            // }

            if ($generateOneTimePasswords) {
                $password = $this->generateTemporaryPassword(8);
            } else {
                $password = $row->password;
            }
            if (!$password || $password == '') {
                $rowInvalid[] = 'Blank password.';
            }

            if (count($rowInvalid) > 0) {
                $invalid->push([$index => $rowInvalid]);
                continue;
            }

            $newUser = new User([
                'membership_number' => ($membershipNumber) ? : "0",
                'body_id' => ($body) ? : "",
                'id_number' => ($id_number) ? : "0",
                'cell' => ($cell) ? : "0",
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'password' => $password,
                'payment_method' => 'eft'
            ]);

            if ($generateOneTimePasswords) {
                $newUser->password_is_temporary = true;
                $newUser->temp_password = $password;
            }

            if (!$importUsers->has($email))
                $importUsers->put($email, $newUser);
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
                    $plan = Plan::findOrFail($planId);
                }else{
                    $plan = null;
                }

                // If existing account than change plan id as pr selected
                if ($existingAccount) 
                 {
                    foreach($existingUsers as $existingUser)
                    {  
                        $existing = User::with('subscriptions', 'subscriptions.plan')->where('email', $existingUser->email)->first();
                        if ($existing->subscription('cpd'))
                        {
                                       /* Safety Check */
                            $existing->subscriptions->where('name','cpd')->each(function ($subscription){
                                $subscription->delete();
                            });
                            $existing->newSubscription('cpd', $plan)->create();
                        }
                    }
                }
                // End

                foreach ($newUsers as $newUser) {
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
                }

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
                    'imported_count' => count($newUsers),
                    'imported' => $newUsers->toArray(),
                    'existing_count' => count($existingUsers),
                    'existing' => $existingUsers->toArray(),
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
        $free = ['0' => 'Free Membership'];
        $plans = Plan::where('inactive', false)->get()->sortBy('id')->pluck('custom_name', 'id')->toArray();
        $planOptions = $free + $plans;
        $mailerOptions = [0 => 'None'] + Mailer::all()->sortBy('id')->pluck('title', 'id')->toArray();
        return compact('planOptions', 'mailerOptions');
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

    protected function generateTemporaryPassword($length)
    {
        return substr(preg_replace("/[^a-zA-Z0-9]/", "", base64_encode($this->getRandomBytes($length + 1))), 0, $length);
    }
}