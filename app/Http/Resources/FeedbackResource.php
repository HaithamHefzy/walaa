<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeedbackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'phone_number' => $this->phone_number,
            'food_quality' => $this->food_quality,
            'service_quality' => $this->service_quality,
            'value_for_money' => $this->value_for_money,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
        ];
    }
}
