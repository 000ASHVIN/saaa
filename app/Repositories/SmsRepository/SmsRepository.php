<?php

namespace App\Repositories\SmsRepository;

use App;
use App\SMS;
use App\SmsApi;

class SmsRepository
{
    public function sendSms($data, $member = null)
    {
        if (App::environment(['production', 'staging'])) {
            $mailin = new SmsApi(env('SMS_KEY'));
            $mailin->addTo($data['number'])
                ->setFrom(env('SMS_FROM'))
                ->setText($data['message'])
                ->setTag((isset($data['tag'])) ? $data['tag'] : "System")
                ->setType('sms')
                ->setCallback(route('dashboard'));
            $res = $mailin->send();
        }else{
            $res = \GuzzleHttp\json_encode($array = ['status' => 'KO']);
        }
        $this->LogSms($data, $member, $res);
        return $res;
    }

    /**
     * @param $data
     * @param $member
     * @param $res
     */
    public function LogSms($data, $member, $res)
    {
        if (auth()->user()){
            $user = auth()->user();
        }else
            $user = [
                'id' => '2',
                'first_name' => 'System',
                'last_name' => 'Administrator',
            ];

        $status = "";
        $status = @$res['MessageId'];
        SMS::create([
            'from' => $user['id'],
            'from_name' => ucfirst($user['first_name']) . ' ' . $user['last_name'],
            'user_id' => $member->id,
            'to_name' => ucfirst($member['first_name']) . ' ' . $member['last_name'],
            'message' => $data['message'],
            'number' => $data['number'],
            'status' => $status,
        ]);
    }
}