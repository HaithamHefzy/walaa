<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'client_name' => $this->client_name,
            'client_phone' => $this->client_phone,
            'recipient_name' => $this->recipient_name,
            'recipient_phone' => $this->recipient_phone,
            'discount_code' => [
                'id' => $this->discountCode?->id,
                'code' => $this->discountCode?->code,
                'discount_type' => $this->discountCode?->discount_type,
                'discount_value' => $this->discountCode?->discount_value,
                'validity_days' => $this->discountCode?->validity_days,
                'validity_after_hours' => $this->discountCode?->validity_after_hours
            ],
            'usage_status' => $this->usage_status ?? 'not_used',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}