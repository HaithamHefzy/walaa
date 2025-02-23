<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates creation of a table record.
 */
class StoreTableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_number'   => 'required|integer|unique:tables,room_number',
            'room_capacity' => 'required|integer|min:1',
            'table_number'  => 'required|integer',
            'status'        => 'required|in:available,unavailable',
        ];
    }
}
