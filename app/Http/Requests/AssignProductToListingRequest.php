<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AssignProductToListingRequest extends Request
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
            'listing_id' => ['required', 'numeric']
        ];
    }
}
