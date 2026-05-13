<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client' => new ClientResource($this->whenLoaded('client')),
            'client_id' => $this->client_id,
            'service_type' => $this->service_type,
            'duration_months' => $this->duration_months,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'reminder_date' => $this->reminder_date?->toDateString(),
            'status' => $this->status,
            'price' => $this->price,
            'payment_status' => $this->payment_status,
            'message_reminder' => $this->message_reminder,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
