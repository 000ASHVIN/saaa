<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateUpdateListingRequest extends Request
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
            'title' => ['required'],
            'category_id' => ['required', 'numeric'],
            'from_price' => ['required', 'numeric', 'min:1'],
        ];
    }
}
