<?php

namespace App\Http\Requests\Codes;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'discount_code_id' => 'required|numeric|exists:discount_codes,id,deleted_at,NULL',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|numeric',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|numeric',
            //'created_time' => 'required'
        ];
    }
}