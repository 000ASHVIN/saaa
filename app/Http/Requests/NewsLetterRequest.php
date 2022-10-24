<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class NewsLetterRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'cell' => 'required',
            'g-recaptcha-response'=>'required|recaptcha'
        ];
    }
}
