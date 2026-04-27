<?php
// app/Http/Resources/PortfolioResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PortfolioResource extends JsonResource
{
    /**
     * Transforme la ressource en tableau.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'display_name' => $this->display_name,
            'bio' => $this->bio,
            'skills' => $this->skills,
            'profile_photo_url' => $this->profile_photo_url,
            'cv_url' => $this->cv_url,
            'vcard_email' => $this->vcard_email,
            'vcard_phone' => $this->vcard_phone,
            'vcard_address' => $this->vcard_address,
            'portfolio_external_url' => $this->portfolio_external_url,
            'theme' => $this->theme,
            'is_active' => $this->is_active,
            'public_url' => $this->public_url,
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
            'social_links' => SocialLinkResource::collection($this->whenLoaded('socialLinks')),
            'custom_links' => CustomLinkResource::collection($this->whenLoaded('customLinks')),
            'visits_count' => $this->when($request->user() && $request->user()->id === $this->user_id, function () {
                // On ajoute les compteurs de visites uniquement pour le propriétaire
                return [
                    'total' => $this->visits()->count(),
                    'direct' => $this->visits()->where('source', 'direct')->count(),
                    'qr' => $this->visits()->where('source', 'qr')->count(),
                    'nfc' => $this->visits()->where('source', 'nfc')->count(),
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
