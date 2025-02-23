<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'food_quality' => 'required|integer|between:1,10',
            'service_quality' => 'required|integer|between:1,10',
            'value_for_money' => 'required|integer|between:1,10',
            'notes' => 'nullable|string',
        ];
    }
}
