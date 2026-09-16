<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'chemin'     => $this->chemin,
            'url'        => $this->url,
            'alt'        => $this->alt,
            'ordre'      => $this->ordre,
            'principale' => $this->principale,
        ];
    }
}