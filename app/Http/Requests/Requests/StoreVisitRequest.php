<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates data when creating a new visit.
 */
class StoreVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id'        => 'required|exists:clients,id',
            'number_of_people' => 'nullable|integer|min:1',
            'source'           => 'required|in:direct,waiting',
            'status'           => 'required|in:waiting,called,done',
            'table_id'         => 'nullable|exists:tables,id',
        ];
    }
}
