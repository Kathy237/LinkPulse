<?php
// app/Http/Resources/NfcCardResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NfcCardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uid' => $this->uid,
            'portfolio_id' => $this->portfolio_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'portfolio' => new PortfolioResource($this->whenLoaded('portfolio')),
        ];
    }
}
