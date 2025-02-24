<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\ClientService;

/**
 * Transforms the Client model into JSON response.
 * Includes membership type, last visit date, and visits count.
 */
class ClientResource extends JsonResource
{
    public function toArray($request): array
    {
        // Inject the ClientService to handle membership logic
        $clientService = app(ClientService::class);

        // Calculate membership type for the client
        $membershipType = $clientService->getMembershipType($this->id);

        // Get last visit date for the client
        $lastVisitDate  = $clientService->getLastVisitDate($this->id);

        // Count total visits for the client
        $visitsCount    = $this->visits()->count();

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'phone'           => $this->phone,
            'membership_type' => $membershipType,
            'last_visit'      => $lastVisitDate,
            'visits_count'    => $visitsCount,
            'created_at'      => $this->created_at,
        ];
    }
}
