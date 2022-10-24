<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ContactFormRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'number' => 'required',
            'department' => 'required',
            'subject' => 'required',
            'body_message' => 'required',
            'g-recaptcha-response'=>'required|recaptcha'
        ];
    }
}
