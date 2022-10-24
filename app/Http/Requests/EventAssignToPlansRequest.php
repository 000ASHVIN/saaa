<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EventAssignToPlansRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->guest())
            return false;

        return auth()->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'event_id' => ['required', 'numeric'],
            'venue_id' => ['required', 'numeric'],
            'date_id' => ['required', 'numeric'],
            'plans_ids' => ['required']
        ];
    }
}
