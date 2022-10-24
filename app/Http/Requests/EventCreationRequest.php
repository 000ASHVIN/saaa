<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EventCreationRequest extends Request
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
            'name' => 'required',
            'type' => 'required',
            'short_description' => 'required',
            'description' => 'required',
            'featured_image' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'next_date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'redirect_url' => ['required_if:is_redirect,1'],
        ];
    }
}
