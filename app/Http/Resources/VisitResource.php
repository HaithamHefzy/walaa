<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\CallButtonSettingService;

/**
 * VisitResource
 * Dynamically determines the classification (A, B, C) based on number_of_people
 * and shows the waiting_number next to the classification in display_label.
 */
class VisitResource extends JsonResource
{
    public function toArray($request): array
    {
        // Inject the CallButtonSettingService to find a suitable button
        $callButtonService = app(CallButtonSettingService::class);

        // Determine classification by number_of_people
        $classification = null;
        if ($this->number_of_people) {
            $button = $callButtonService->findSuitableButton($this->number_of_people);
            if ($button) {
                $classification = $button->button_type; // e.g. 'A', 'B', or 'C'
            }
        }

        // Calculate time since creation (e.g., "16 minutes ago")
        $timeSince = $this->created_at ? $this->created_at->diffForHumans() : null;

        // Build display label with classification, waiting_number, number_of_people, and timeSince
        $displayLabel = '';
        if ($classification) {
            $displayLabel .= $classification . ' ';
        }
        if ($this->waiting_number) {
            $displayLabel .= $this->waiting_number . ' ';
        }
        $displayLabel .= '(' . $this->number_of_people . ' أشخاص) - ' . ($timeSince ?? '');

        return [
            'id'               => $this->id,
            'client_id'        => $this->client_id,
            'number_of_people' => $this->number_of_people,
            'waiting_number'   => $this->waiting_number,  // The daily reset number
            'status'           => $this->status,
            'source'           => $this->source,
            'call_button_type' => $classification,         // dynamic classification
            'time_since'       => $timeSince,              // e.g. "16 minutes ago"
            'display_label'    => $displayLabel            // e.g. "A 1 (3 أشخاص) - 15 دقيقة"
        ];
    }
}
