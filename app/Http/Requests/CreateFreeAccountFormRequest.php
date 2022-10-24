<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Validator;

class CreateFreeAccountFormRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'cell' => 'required',
            'alternative_cell' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'id_number' => 'required|unique:users',
            'password' => 'required|confirmed|min:6',
            'terms' => ['required', 'accepted'],
            'address_line_one' => 'required',
            'address_line_two' => 'required',
            'province' => 'required',
            'city' => 'required',
            'area_code' => 'required',
            'verified' => 'required_unless:nocitizen,on'
        ];

        $messages = [
            'required' => 'The :attribute field is required.',
            'required_unless' => 'Please check your ID Number and try again'
        ];

        $validator = Validator::make($rules, $messages);
        return $validator;
    }
}
