<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MarketingMessageResource extends JsonResource
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
            'message_body' => $this->message_body,
            'attachment_path' => $this->attachment_path ? Storage::disk('public')->url($this->attachment_path) : NULL,
            'send_to' => $this->send_to,
            'client_ids' => $this->send_to == 'all' ? NULL : json_decode($this->client_ids),
            'schedule_time' => $this->schedule_time,
            'sent_now' => $this->sent_now,
            'delivery_method' => $this->delivery_method,
            'status' => $this->status ?? 'pending'
        ];
    }
}