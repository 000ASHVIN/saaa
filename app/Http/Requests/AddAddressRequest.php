<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddAddressRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user())
            return true;

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rule = [
            'type' => ['required'],
            'line_one' => ['required'],
            'city' => ['required'],
            'area_code' => ['required'],
            'province' => ['required'],
            'country' => ['required']
        ];
        if($this['province'] == 'others'){
            $rule['other_province'] = ['required'];
        }
        return $rule;
    }
}
