<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MarketingMessageSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->message_type,
            'message_type' => $this->message_type,
            'message_text' => $this->message_text,
            'attachment_path' => $this->attachment_path ? Storage::disk('public')->url($this->attachment_path) : NULL,
            'send_to_all' => $this->send_to_all ?? 0,
            'send_after_hours' => $this->send_after_hours,
            'send_to_category' => $this->send_to_category,
            'sending_method' => $this->sending_method,
            'send_on_birthday' => $this->send_on_birthday,
            'send_after_purchase' => $this->send_after_purchase,
            'send_after_payment' => $this->send_after_payment,
            'send_on_special_event' => $this->send_on_special_event,
            'special_event_type' => $this->special_event_type,
            'special_event_days' => $this->special_event_days,
            'is_active' => $this->is_active ?? 1
        ];
    }
}