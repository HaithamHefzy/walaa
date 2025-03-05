<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NumberListResource extends JsonResource
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
            'title' => $this->title,
            'numbers' => $this->numbers?->map(function ($number) {
                return [
                    'id' => $number->id,
                    'number' => $number->number
                ];
            }),
        ];
    }
}