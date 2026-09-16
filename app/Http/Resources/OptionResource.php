<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'nom'             => $this->nom,
            'valeur'          => $this->valeur,
            'prix_supplement' => (float) $this->prix_supplement,
            'stock'           => $this->stock,
            'ordre'           => $this->ordre,
        ];
    }
}