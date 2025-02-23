<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the data for creating a client and visit in one request.
 */
class StoreClientVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Adjust authorization logic as needed.
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
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'number_of_people' => 'nullable|integer|min:1',
            'source'           => 'required|in:direct,waiting',
            'status'           => 'required|in:waiting,called,done',
            'table_id'         => 'nullable|exists:tables,id',
        ];
    }
}
