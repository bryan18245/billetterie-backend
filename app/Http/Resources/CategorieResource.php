<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategorieResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'libelle'     => $this->libelle,
            'description' => $this->description,
            'image'       => $this->image ? asset('storage/' . $this->image) : null,
            'parent_id'   => $this->parent_id,
            'ordre'       => $this->ordre,
            'actif'       => $this->actif,
            'produits_count' => $this->when(
                $this->relationLoaded('produits'),
                fn() => $this->produits->count()
            ),
            'created_at'  => $this->created_at?->format('d/m/Y H:i'),
        ];
    }
}