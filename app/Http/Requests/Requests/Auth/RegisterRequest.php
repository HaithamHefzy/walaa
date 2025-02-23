<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => ['required','email',Rule::unique('users')->whereNull('deleted_at')],
            'phone' => ['required','numeric',Rule::unique('users')->whereNull('deleted_at')],
            'username' => 'required|string|max:100',
            'password' => 'required|min:8|confirmed'
        ];
    }
}
