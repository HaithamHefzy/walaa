<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

/**
 * Transforms the Table model into a JSON response.
 */
class TableResource extends JsonResource
{
    public function toArray($request): array
    {
        // Initialize "occupied_for" as null by default
        $occupiedFor = null;

        // If the table status is 'unavailable', calculate how long it has been in that state
        if ($this->status === 'unavailable') {
            // Calculate the difference in minutes between now and updated_at
            $diffInMinutes = Carbon::now()->diffInMinutes($this->updated_at);

            // Convert that difference to hours and minutes
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;

            // Build a string in Arabic, e.g., "2 ساعة و 30 دقيقة."
            $occupiedFor = $hours . ' ساعة و ' . $minutes . ' دقيقة';
        }

        return [
            'id'              => $this->id,
            'room_number'     => $this->room_number,
            'table_capacity'  => $this->table_capacity,
            'table_number'    => $this->table_number,
            'status'          => $this->status,
            'created_at'      => $this->created_at,
            'unavailable_for'    => $occupiedFor,
        ];
    }
}
