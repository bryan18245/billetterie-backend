<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProduitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'nom'             => $this->nom,
            'description'     => $this->description,
            'prix'            => (float) $this->prix,
            'prix_promo'      => $this->prix_promo ? (float) $this->prix_promo : null,
            'prix_actuel'     => (float) $this->prix_actuel,
            'en_promo'        => $this->en_promo,
            'stock'           => $this->stock,
            'sku'             => $this->sku,
            'actif'           => $this->actif,
            'note_moyenne'    => (float) $this->note_moyenne,
            'nb_ventes'       => $this->nb_ventes,
            'categorie'       => new CategorieResource($this->whenLoaded('categorie')),
            'options'         => OptionResource::collection($this->whenLoaded('options')),
            'images'          => ImageResource::collection($this->whenLoaded('images')),
            'image_principale' => $this->imagePrincipale
                ? new ImageResource($this->imagePrincipale)
                : null,
            'a_des_options'   => $this->a_des_options,
            'created_at'      => $this->created_at?->format('d/m/Y H:i'),
        ];
    }
}