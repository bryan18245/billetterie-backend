<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Categorie::where('actif', true)->get();

        if ($categories->isEmpty()) {
            $this->command->warn('❌ Aucune catégorie. Lance CategorieSeeder d\'abord.');
            return;
        }

        // Clé = Str::slug(libelle)
        $catalogue = [
            'mode'         => ['T-shirt coton bio', 'Jean slim brut', 'Robe d\'été fleurie', 'Chemise en lin', 'Sneakers urbaines'],
            'electronique' => ['Smartphone X10 Pro', 'Écouteurs Bluetooth', 'Chargeur rapide 65W', 'Powerbank 20000mAh', 'Casque audio sans fil'],
            'maison'       => ['Coussin décoratif', 'Lampe LED design', 'Plaid polaire doux', 'Vase céramique', 'Miroir mural rond'],
            'beaute'       => ['Crème hydratante visage', 'Parfum femme floral', 'Rouge à lèvres mat', 'Sérum vitamine C', 'Huile capillaire'],
            'alimentation' => ['Riz parfumé 5kg', 'Huile de palme 1L', 'Café moulu 250g', 'Chocolat noir 70%', 'Miel pur de forêt'],
            'sport'        => ['Ballon de football', 'Tapis de yoga', 'Haltères 5kg', 'Corde à sauter pro', 'Gourde isotherme'],
            'animaux'      => ['Croquettes chien 3kg', 'Jouet chat souris', 'Laisse réglable', 'Gamelle inox', 'Panier coussin'],
            'livres'       => ['Roman policier', 'BD aventure', 'Guide de cuisine', 'Livre d\'histoire', 'Recueil de poésie'],
        ];

        $total = 0;

        foreach ($categories as $categorie) {
            $key = Str::slug($categorie->libelle);
            $noms = $catalogue[$key] ?? [
                'Produit ' . $categorie->id . ' A',
                'Produit ' . $categorie->id . ' B',
                'Produit ' . $categorie->id . ' C',
            ];

            foreach ($noms as $nom) {
                $prix      = rand(2000, 80000);
                $enPromo   = rand(0, 100) < 40; // 40% en promo
                $prixPromo = $enPromo ? round($prix * (rand(50, 85) / 100)) : null;

                Produit::create([
                    'categorie_id' => $categorie->id,
                    'nom'          => $nom,
                    'description'  => 'Description de ' . $nom . '. Produit de qualité disponible chez ShopCI, livré partout en Côte d\'Ivoire.',
                    'prix'         => $prix,
                    'prix_promo'   => $prixPromo,
                    'stock'        => rand(5, 100),
                    'sku'          => 'SKU-' . strtoupper(Str::random(6)),
                    'actif'        => true,
                    'note_moyenne' => rand(30, 50) / 10, // 3.0 à 5.0
                    'nb_ventes'    => rand(0, 500),
                ]);

                $total++;
            }
        }

        $this->command->info("✅ {$total} produits créés.");
    }
}