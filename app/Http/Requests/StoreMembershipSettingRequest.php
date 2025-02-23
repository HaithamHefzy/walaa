<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platinum_visits' => 'required|integer|min:1',
            'gold_visits'     => 'required|integer|min:1',
            'silver_visits'   => 'required|integer|min:1',
        ];
    }
}
