<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Produit;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        $produits = Produit::all();

        if ($produits->isEmpty()) {
            $this->command->warn('❌ Aucun produit. Lance ProduitSeeder d\'abord.');
            return;
        }

        $total = 0;

        foreach ($produits as $produit) {
            // Image principale
            Image::create([
                'produit_id' => $produit->id,
                'chemin'     => 'https://picsum.photos/seed/prod' . $produit->id . '/600/600',
                'alt'        => $produit->nom,
                'ordre'      => 0,
                'principale' => true,
            ]);

            // 2 images secondaires
            for ($i = 1; $i <= 2; $i++) {
                Image::create([
                    'produit_id' => $produit->id,
                    'chemin'     => 'https://picsum.photos/seed/prod' . $produit->id . '-' . $i . '/600/600',
                    'alt'        => $produit->nom . ' - vue ' . $i,
                    'ordre'      => $i,
                    'principale' => false,
                ]);
            }

            $total += 3;
        }

        $this->command->info("✅ {$total} images créées.");
    }
}