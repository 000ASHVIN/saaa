<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EventRegistrationRequest extends Request
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
            'event' => 'required',
            'venue' => 'required',
            'dates' => 'required',
            'terms' => 'required|accepted'
        ];
    }

    public function messages()
    {
        return [
            'terms.required' => 'You must accept the Terms & Conditions to proceed.',
            'terms.accepted' => 'You must accept the Terms & Conditions to proceed.'
        ];
    }
}
