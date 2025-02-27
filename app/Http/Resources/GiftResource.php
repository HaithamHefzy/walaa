<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GiftResource extends JsonResource
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
            'gift_code' => [
                'id' => $this->giftCode?->id,
                'code' => $this->giftCode?->code,
                'discount_type' => $this->giftCode?->discount_type,
                'discount_value' => $this->giftCode?->discount_value,
                'validity_days' => $this->giftCode?->validity_days,
                'validity_after_hours' => $this->giftCode?->validity_after_hours
            ],
            'client_name' => $this->client_name,
            'client_phone' => $this->client_phone,
            'friend_name' => $this->friend_name,
            'friend_phone' => $this->friend_phone,
            'message' => $this->message,
            'is_redeemed' => $this->is_redeemed ?? 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}