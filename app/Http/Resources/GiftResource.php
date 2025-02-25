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
            'gift_code_id' => $this->gift_code_id,
            'client_name' => $this->client_name,
            'client_phone' => $this->client_phone,
            'friend_name' => $this->friend_name,
            'friend_phone' => $this->friend_phone,
            'message' => $this->message,
            'is_redeemed' => $this->is_redeemed ?? 0
        ];
    }
}