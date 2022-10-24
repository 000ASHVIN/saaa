<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateContactDetailsRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !auth()->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cell' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'cell.required' => 'Contact number is required'
        ];
    }
}
