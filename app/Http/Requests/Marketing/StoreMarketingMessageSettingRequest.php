<?php

namespace App\Http\Requests\Marketing;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarketingMessageSettingRequest extends FormRequest
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
            'message_type' => 'required|string|max:255',
            'message_text' => 'required|string',
            'attachment_path' => 'required',
            'send_to_all' => 'required|numeric|in:0,1',
            'send_after_hours' => 'required|numeric',
            'send_to_category' => 'required|string|max:255',
            'sending_method' => 'required|string|max:255',
            'send_on_birthday' => 'required|numeric|in:0,1',
            'send_after_purchase' => 'required|numeric|in:0,1',
            'send_after_payment' => 'required|numeric|in:0,1',
            'send_on_special_event' => 'required|numeric|in:0,1',
            'special_event_type' => 'required_if:send_on_special_event,1|string|max:255',
            'special_event_days' => 'required_if:send_on_special_event,1|numeric'
        ];
    }
}