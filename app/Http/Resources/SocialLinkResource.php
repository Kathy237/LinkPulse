<?php
// app/Http/Resources/SocialLinkResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SocialLinkResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'platform' => $this->platform,
            'url' => $this->url,
            'sort_order' => $this->sort_order,
        ];
    }
}