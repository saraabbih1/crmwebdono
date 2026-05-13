<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client' => new ClientResource($this->whenLoaded('client')),
            'subscription' => new SubscriptionResource($this->whenLoaded('subscription')),
            'message' => $this->message,
            'type' => $this->type,
            'status' => $this->status,
            'reminder_date' => $this->reminder_date?->toDateString(),
            'sent_at' => $this->sent_at?->toISOString(),
        ];
    }
}
