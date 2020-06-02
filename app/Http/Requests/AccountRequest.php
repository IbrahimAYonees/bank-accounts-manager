<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountRequest extends FormRequest
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
            'bank_id' => 'required|integer|min:1|exists:banks,id',
            'currency_id' => 'required|integer|min:1|exists:currencies,id',
            'branch' => 'required|string|max:255',
            'type' => [
                'required',
                'string',
                Rule::in(['Current', 'Saving','Credit','Joint']),
            ]
        ];
    }
}
