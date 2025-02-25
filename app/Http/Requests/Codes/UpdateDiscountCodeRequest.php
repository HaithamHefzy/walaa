<?php

namespace App\Http\Requests\Codes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class UpdateDiscountCodeRequest extends FormRequest
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
        $id = Request::segment('3');
        return [
            'code' => "required|unique:discount_codes,code,{$id},id,deleted_at,NULL",
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'validity_days' => 'required|numeric',
            'validity_after_hours' => 'required|numeric'
        ];
    }
}