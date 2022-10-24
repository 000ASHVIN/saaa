<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProfileUpdateRequest extends Request
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
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'body_id' => 'required',
            'body_id' => 'required',
            // 'cell' => 'required|numeric',
            'cell' => 'required',
            'specified_body' => 'required_if:body,other'
        ];
    }
}
