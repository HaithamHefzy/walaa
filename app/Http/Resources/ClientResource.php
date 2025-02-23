<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\ClientService;

/**
 * Transforms the Client model into JSON response,
 * including membership type and last visit date.
 */
class ClientResource extends JsonResource
{
    public function toArray($request): array
    {
        $clientService = app(ClientService::class);

        $membershipType = $clientService->getMembershipType($this->id);
        $lastVisitDate  = $clientService->getLastVisitDate($this->id);

        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'phone'           => $this->phone,
            'membership_type' => $membershipType,
            'last_visit'      => $lastVisitDate,
            'created_at'      => $this->created_at,
        ];
    }
}
