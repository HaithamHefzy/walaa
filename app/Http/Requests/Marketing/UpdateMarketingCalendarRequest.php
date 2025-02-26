<?php

namespace App\Http\Requests\Marketing;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMarketingCalendarRequest extends FormRequest
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
            'type' => 'required|in:public,local,private',
            'event_date' => 'required|date',
            'offer_type' => 'required|in:discount,free_offer',
            'discount_percentage' => 'required_if:offer_type,discount|numeric',
            'free_offer_details' => 'required_if:offer_type,free_offer|string',
            'message_send_before_days' => 'required|numeric',
            'customer_category' => 'required|string|max:255',
            'message_content' => 'required|string'
        ];
    }
}