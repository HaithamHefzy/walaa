<?php

namespace App\Http\Requests\Gift;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGiftCodeRequest extends FormRequest
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
            'code' => ['required',Rule::unique('gift_codes')->whereNull('deleted_at')],
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'validity_days' => 'required|numeric',
            'validity_after_hours' => 'required|numeric'
        ];
    }
}