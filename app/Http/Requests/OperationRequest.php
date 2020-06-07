<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OperationRequest extends FormRequest
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
            'transaction_id' => 'required|integer|min:1|exists:transactions,id',
            'amount' => 'required|numeric|min:1',
            'currency_id' => 'required|integer|min:1|exists:currencies,id'
        ];
    }
}
