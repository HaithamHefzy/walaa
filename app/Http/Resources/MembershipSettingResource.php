<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms the MembershipSetting model into JSON response.
 */
class MembershipSettingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'platinum_visits'  => $this->platinum_visits,
            'gold_visits'      => $this->gold_visits,
            'silver_visits'    => $this->silver_visits,
            'created_at'       => $this->created_at,
        ];
    }
}
