<?php
// app/Http/Resources/NfcReadResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NfcReadResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'card_id' => $this->card_id,
            'card' => new NfcCardResource($this->whenLoaded('card')),
            'read_at' => $this->read_at,
        ];
    }
}