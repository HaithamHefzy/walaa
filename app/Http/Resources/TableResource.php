<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms the Table model into JSON response.
 */
class TableResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'room_number'   => $this->room_number,
            'table_capacity' => $this->table_capacity,
            'table_number'  => $this->table_number,
            'status'        => $this->status,
            'created_at'    => $this->created_at,
        ];
    }
}
