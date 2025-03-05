<?php

namespace App\Http\Requests\Number;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NumberRequest extends FormRequest
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
            'title' => ['required','string','max:255',Rule::unique('number_lists')->whereNull('deleted_at')],
            'type' => 'required|in:text,file',
            'numbers' => 'required_if:type,text',
            'file' => 'required_if:type,file|mimes:xlsx,xls'
        ];
    }
}