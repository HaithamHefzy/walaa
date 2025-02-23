<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms the Visit model into JSON response.
 */
class VisitResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'client_id'        => $this->client_id,
            'number_of_people' => $this->number_of_people,
            'source'           => $this->source,
            'status'           => $this->status,
            'table_id'         => $this->table_id,
            'created_at'       => $this->created_at,
        ];
    }
}
