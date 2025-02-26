<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ActivityResource extends JsonResource
{
    public function toArray($request)
    {
        // Determine causer name (who caused the event)
        $causerName = null;
        if ($this->causer) {
            if ($this->causer instanceof \App\Models\User) {
                $causerName = 'مستخدم: ' . $this->causer->name;
            } else {
                // If you have other models as causer
                $causerName = class_basename($this->causer) . ' #' . $this->causer->id;
            }
        }

        // Determine subject name (the model that was affected)
        $subjectName = null;
        if ($this->subject) {
            if ($this->subject instanceof \App\Models\Visit) {
                // If the subject is a Visit, we may want the client's name
                // Make sure we have loaded the client relationship (see step 2)
                $clientName = $this->subject->client ? $this->subject->client->name : 'بدون عميل';
                $subjectName = 'استدعاء للعميل: ' . $clientName;
            }
            elseif ($this->subject instanceof \App\Models\Client) {
                $subjectName = 'عميل: ' . $this->subject->name;
            }
            elseif ($this->subject instanceof \App\Models\User) {
                $subjectName = 'مستخدم: ' . $this->subject->name;
            }
            else {
                // Default fallback if subject is another model
                $subjectName = class_basename($this->subject) . ' #' . $this->subject->id;
            }
        }

        return [
            'id'           => $this->id,
            'description'  => $this->description,
            'created_at'   => $this->created_at,
            'properties'   => $this->properties,
            'causer_id'    => $this->causer_id,
            'causer_name'  => $causerName,
            'subject_id'   => $this->subject_id,
            'subject_name' => $subjectName,
        ];
    }
}
