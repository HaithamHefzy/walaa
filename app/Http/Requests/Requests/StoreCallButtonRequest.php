<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCallButtonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'button_type' => 'required|string|max:5',
            'max_people'  => 'required|integer|min:1',
        ];
    }
}
