<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateShippingInformationRequest extends Request
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
            'status_id' => ['required', 'numeric']
        ];
    }
}
