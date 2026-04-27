<?php
// app/Http/Resources/UserResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'location' => $this->location,
            'locale' => $this->locale,
            // On peut ajouter d'autres champs si nécessaire
        ];
    }
}