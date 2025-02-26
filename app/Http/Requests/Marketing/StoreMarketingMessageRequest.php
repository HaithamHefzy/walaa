<?php

namespace App\Http\Requests\Marketing;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarketingMessageRequest extends FormRequest
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
            'message_body' => 'required|string',
            'attachment_path' => 'required',
            'send_to' => 'required|in:all,custom',
            'client_ids' => 'required_if:send_to,custom',
            'schedule_time' => 'required|date_format:Y-m-d H:i:s',
            'sent_now' => 'required|numeric',
            'delivery_method' => 'required|in:whatsapp,sms,email'
        ];
    }
}