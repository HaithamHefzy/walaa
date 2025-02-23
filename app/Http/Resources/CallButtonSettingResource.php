<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms the CallButtonSetting model into JSON response.
 */
class CallButtonSettingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'button_type' => $this->button_type,
            'max_people'  => $this->max_people,
            'created_at'  => $this->created_at,
        ];
    }
}
