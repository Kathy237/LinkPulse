<?php
// app/Http/Resources/ProjectResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'github_url' => $this->github_url,
            'demo_url' => $this->demo_url,
            'image_url' => $this->image_url,
            'sort_order' => $this->sort_order,
        ];
    }
}