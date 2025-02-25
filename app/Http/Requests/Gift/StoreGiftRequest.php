<?php

namespace App\Http\Requests\Gift;

use Illuminate\Foundation\Http\FormRequest;

class StoreGiftRequest extends FormRequest
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
            'gift_code_id' => 'required|numeric|exists:gift_codes,id,deleted_at,NULL',
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|numeric',
            'friend_name' => 'required|string|max:255',
            'friend_phone' => 'required|numeric',
            'message' => 'required|string'
        ];
    }
}