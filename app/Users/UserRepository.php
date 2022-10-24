<?php

namespace App\Users;

use App\Http\Requests\Request;
use App\Moodle;
use App\Wallet;
use Carbon\Carbon;

/**
 * Class UserRepository
 * @package App\Users
 */
class UserRepository {

	/**
	 * @var \App\Users\User
     */
	protected $user;

	/**
	 * UserRepository constructor.
	 * @param \App\Users\User $user
     */
	public function __construct(User $user)
	{
		$this->user = $user;
	}

	/**
	 * @param $id
	 * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
	public function find($id)
	{
		return $this->user->with('profile', 'activity', 'addresses', 'cpds', 'tickets', 'tickets.event', 'tickets.dates', 'tickets.invoice', 'debit', 'subscriptions', 'subscriptions.plan')->find($id);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
	public function all()
	{
		return $this->user->all();
	}

	/**
	 * @param int $count
	 * @return mixed
     */
	public function allPaginate($count = 6)
	{
		return $this->user->orderBy('first_name')->paginate($count);
	}

    /**
     * @param User $user
     * @return mixed
     */
    public function save(User $user)
	{
		return $user->save();
	}

    public function assignSettings(User $user)
    {
        $user->update(['settings' => [
            'send_invoices_via_email' => '1',
            'event_notifications' => '1',
            'sms_notifications' => '1',
            'marketing_emails' => '1',
        ]]);
        $user->save();
        return $user;
	}

    /**
     * @param $user
     */
    public function createProfileFor($user)
    {
        $profile = new Profile;
        $user->profile()->save($profile);
    }

    public function createWalletForUser($user)
    {
        $wallet = new Wallet();
        $user->wallet()->save($wallet);
    }

//	Update user with Profile!
	public function Update_User_Profile(Request $request, $id)
	{
		$user = $this->userRepository->find($id);
		$new_data = $request->except(['_token']);

		$user->update($new_data);
		$user->profile->fill($new_data)->save();
		$user->save();
	}


	/*
	 * Suspend the user account.
	 */
    public function suspend($user)
    {
        if (empty($this->suspended_at)){
            $user->fill([ 'status' => 'suspended', 'suspended_at' => Carbon::now()]);
            if($user->moodle_user_id > 0) {
                $moodleUser = new \stdClass();
                $moodleUser->id = (int)$user->moodle_user_id;
                $moodleUser->suspended = 1;

                $moodle = new Moodle();
                $response = $moodle->userUpdate($moodleUser);
            }
            $user->save();
        }

//        /* Cancel user cpd subscription */
//
//        if ($user->subscribed('cpd')) {
//            $user->subscription('cpd')->cancel(Carbon::now());
//        }
        return $user;
    }

    /*
	 * Un-suspend the user account.
	 */
    public function unsuspend($user)
    {
        if (! empty($user->suspended_at) || empty($user->suspended_at)){
            $user->fill(['status' => 'active', 'suspended_at' => null]);
            $user->save();

            if($user->moodle_user_id > 0) {
                $moodleUser = new \stdClass();
                $moodleUser->id = (int)$user->moodle_user_id;
                $moodleUser->suspended = 0;

                $moodle = new Moodle();
                $response = $moodle->userUpdate($moodleUser);
            }
        }
        return $user;
    }

    /*
     * Force suspend the user account.
     */
    public function toggleForceSuspension($user)
    {
        $user->force_suspend = !$user->force_suspend;
        $moodleUser = new \stdClass();
        $moodleUser->id = (int)$user->moodle_user_id;

        if ($user->force_suspend == false){
            $user->fill(['status' => 'active', 'suspended_at' => null]);
            $moodleUser->suspended = 0;
        }else{
            $user->fill(['status' => 'suspended', 'suspended_at' => Carbon::now()]);
            $moodleUser->suspended = 1;
        }
        $user->save();

        $moodle = new Moodle();
        $response = $moodle->userUpdate($moodleUser);

        return $user;
    }
}