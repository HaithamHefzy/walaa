<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketingCalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'event_date' => $this->event_date,
            'offer_type' => $this->offer_type,
            'discount_percentage' => $this->discount_percentage,
            'free_offer_details' => $this->free_offer_details,
            'message_send_before_days' => $this->message_send_before_days,
            'customer_category' => $this->customer_category,
            'message_content' => $this->message_content,
            'is_expired' => $this->event_date < now() ? 1 : 0
        ];
    }
}