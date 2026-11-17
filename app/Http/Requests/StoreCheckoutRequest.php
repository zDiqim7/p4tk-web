<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_type' => ['required', Rule::in(['instant_pickup', 'instant_delivery', 'expedited_shipping'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'delivery_address' => [
                Rule::requiredIf($this->input('order_type') !== 'instant_pickup'),
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }
}
