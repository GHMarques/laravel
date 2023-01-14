<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
            'quantity' => ['required', 'integer', 'min:0'],
            'type' => [
                'required',
                Rule::in([Transaction::TYPE_BUY, Transaction::TYPE_SELL]),
            ],
            'pokemon_id' => ['required', 'exists:pokemons,id'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'quantity.required' => trans('validation.validation_required'),
            'quantity.integer' => trans('validation.validation_integer'),
            'quantity.min' => trans('validation.validation_min_number'),
            'type.required' => trans('validation.validation_required'),
            'type.in' => trans('validation.validation_in'),
            'pokemon_id.required' => trans('validation.validation_required'),
            'pokemon_id.exists' => trans('validation.validation_exists'),
        ];
    }
}
