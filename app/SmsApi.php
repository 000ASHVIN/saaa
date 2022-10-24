<?php
/**
 * Created by PhpStorm.
 * User: Tiaan
 * Date: 2018/01/12
 * Time: 12:06
 */

namespace App;

use Illuminate\Support\Facades\App;


class SmsApi
{
    protected $key, $to, $from, $callback, $text, $tag, $webaction = 'SENDSMS', $url = 'http://ws.mailin.fr/', $type='marketing';

    public function __construct($key){
        $this->key = $key;
    }

    public function addTo($to){
        $this->to = $to;
        return $this;
    }
    public function setFrom($from){
        $this->from = $from;
        return $this;
    }
    public function setCallback($callback_url){
        $this->callback = $callback_url;
        return $this;
    }
    public function setText($text){
        $this->text = $text;
        return $this;
    }
    public function setTag($text){
        $this->tag = $text;
        return $this;
    }

    public function setType($text){
        $this->type = $text;
        return $this;
    }
    public function send(){

            $sns = App::make('aws')->createClient('sns');

            $result = $sns->publish([
                'Message' => $this->text, // REQUIRED
                'MessageAttributes' => [
                    'AWS.SNS.SMS.SenderID' => [
                        'DataType' => 'String', // REQUIRED
                        'StringValue' =>  $this->from
                    ],
                    'AWS.SNS.SMS.SMSType' => [
                        'DataType' => 'String', // REQUIRED
                        'StringValue' => 'Transactional' // or 'Promotional'
                    ]
                ],
                'PhoneNumber' => $this->to,
            ]);
            return $result;
        
    }
}