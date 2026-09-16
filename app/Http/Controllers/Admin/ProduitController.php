<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Image;
use App\Models\LigneCommande;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    /**
     * Liste des produits.
     */
    public function index(Request $request)
    {
        $query = Produit::with(['categorie', 'imagePrincipale']);

        // Recherche
        if ($request->filled('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        // Filtre catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        // Filtre statut
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif === '1');
        }

        // Filtre stock faible
        if ($request->filled('stock') && $request->stock === 'faible') {
            $query->where('stock', '<=', 5);
        }

        $produits = $query->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $categories = Categorie::where('actif', true)->orderBy('libelle')->get();

        return view('admin.produits.index', compact('produits', 'categories'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $categories = Categorie::where('actif', true)->orderBy('libelle')->get();

        return view('admin.produits.create', compact('categories'));
    }

    /**
     * Enregistrer un produit.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'          => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'prix'         => ['required', 'numeric', 'min:0'],
            'prix_promo'   => ['nullable', 'numeric', 'min:0', 'lt:prix'],
            'stock'        => ['required', 'integer', 'min:0'],
            'sku'          => ['nullable', 'string', 'max:50'],
            'actif'        => ['boolean'],
            'images.*'     => ['nullable', 'image', 'max:2048'],
        ], [
            'prix_promo.lt' => 'Le prix promo doit être inférieur au prix normal.',
            'images.*.image' => 'Chaque fichier doit être une image.',
            'images.*.max'   => 'Chaque image ne doit pas dépasser 2 Mo.',
        ]);

        $data['actif'] = $request->boolean('actif');

        if (empty($data['sku'])) {
            $data['sku'] = 'SKU-' . strtoupper(Str::random(6));
        }

        $produit = Produit::create($data);

        // Upload des images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('produits', 'public');

                Image::create([
                    'produit_id' => $produit->id,
                    'chemin'     => '/storage/' . $path,
                    'alt'        => $produit->nom,
                    'ordre'      => $index,
                    'principale' => $index === 0,
                ]);
            }
        }

        return redirect()
            ->route('admin.produits.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Détail d'un produit.
     */
    public function show($id)
    {
        $produit = Produit::with(['categorie', 'images', 'options'])
            ->findOrFail($id);

        return view('admin.produits.show', compact('produit'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit($id)
    {
        $produit = Produit::with('images')->findOrFail($id);
        $categories = Categorie::where('actif', true)->orderBy('libelle')->get();

        return view('admin.produits.edit', compact('produit', 'categories'));
    }

    /**
     * Mettre à jour un produit.
     */
    public function update(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);

        // Blocage si le produit est dans une commande active
        if ($produit->estDansCommandeActive()) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Impossible de modifier ce produit : il est présent dans une ou plusieurs commandes en cours.'
                );
        }

        $data = $request->validate([
            'nom'          => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'prix'         => ['required', 'numeric', 'min:0'],
            'prix_promo'   => ['nullable', 'numeric', 'min:0', 'lt:prix'],
            'stock'        => ['required', 'integer', 'min:0'],
            'sku'          => ['nullable', 'string', 'max:50'],
            'actif'        => ['boolean'],
            'images.*'     => ['nullable', 'image', 'max:2048'],
        ]);

        $data['actif'] = $request->boolean('actif');

        $produit->update($data);

        // Nouvelles images
        if ($request->hasFile('images')) {
            $ordreMax = $produit->images()->max('ordre') ?? -1;

            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('produits', 'public');

                Image::create([
                    'produit_id' => $produit->id,
                    'chemin'     => '/storage/' . $path,
                    'alt'        => $produit->nom,
                    'ordre'      => $ordreMax + $index + 1,
                    'principale' => false,
                ]);
            }
        }

        return redirect()
            ->route('admin.produits.index')
            ->with('success', 'Produit mis à jour.');
    }

    /**
     * Supprimer un produit.
     */
    public function destroy($id)
    {
        $produit = Produit::with('images')->findOrFail($id);

        // Vérifier si le produit est dans une commande active
        $enCommande = LigneCommande::where('produit_id', $produit->id)
            ->whereHas('commande', function ($q) {
                $q->whereNotIn('statut', ['livree', 'annulee']);
            })
            ->exists();

        if ($enCommande) {
            return back()->with(
                'error',
                'Impossible de supprimer ce produit : il est présent dans une ou plusieurs commandes en cours.'
            );
        }

        // Supprimer les images (fichiers + BDD)
        foreach ($produit->images as $image) {
            $path = str_replace('/storage/', '', $image->chemin);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $image->delete();
        }

        $produit->delete();

        return redirect()
            ->route('admin.produits.index')
            ->with('success', 'Produit supprimé.');
    }

    /**
     * Supprimer une image individuelle.
     */
    public function supprimerImage($produitId, $imageId)
    {
        $image = Image::where('produit_id', $produitId)->findOrFail($imageId);

        $path = str_replace('/storage/', '', $image->chemin);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        $image->delete();

        return back()->with('success', 'Image supprimée.');
    }

    /**
     * Définir une image comme principale.
     */
    public function definirPrincipale($produitId, $imageId)
    {
        Image::where('produit_id', $produitId)->update(['principale' => false]);

        Image::where('produit_id', $produitId)
            ->where('id', $imageId)
            ->update(['principale' => true]);

        return back()->with('success', 'Image principale modifiée.');
    }
}