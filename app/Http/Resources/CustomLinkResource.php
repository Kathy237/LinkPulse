<?php
// app/Http/Resources/CustomLinkResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomLinkResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'url' => $this->url,
            'icon' => $this->icon,
            'sort_order' => $this->sort_order,
        ];
    }
}
