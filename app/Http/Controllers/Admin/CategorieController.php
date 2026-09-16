<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Models\Produit;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Liste des catégories.
     */
    public function index(Request $request)
    {
        $query = Categorie::with('parent')
            ->withCount('produits');

        // Recherche
        if ($request->filled('search')) {
            $query->where('libelle', 'like', '%' . $request->search . '%');
        }

        // Filtre statut
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif === '1');
        }

        $categories = $query->orderBy('ordre')
            ->orderBy('libelle')
            ->paginate(20)
            ->withQueryString();

        // Stats rapides
        $stats = [
            'total'   => Categorie::count(),
            'actives' => Categorie::where('actif', true)->count(),
            'racines' => Categorie::whereNull('parent_id')->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $parents = Categorie::where('actif', true)
            ->whereNull('parent_id')
            ->orderBy('libelle')
            ->get();

        return view('admin.categories.create', compact('parents'));
    }

    /**
     * Enregistrer une catégorie.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle'     => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'parent_id'   => ['nullable', 'exists:categories,id'],
            'ordre'       => ['nullable', 'integer', 'min:0'],
            'actif'       => ['boolean'],
        ]);

        $data['actif'] = $request->boolean('actif');
        $data['ordre'] = $data['ordre'] ?? 0;

        Categorie::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Formulaire d'édition.
     */
    public function edit($id)
    {
        $categorie = Categorie::findOrFail($id);

        $parents = Categorie::where('actif', true)
            ->whereNull('parent_id')
            ->where('id', '!=', $categorie->id)   // ne pas se choisir soi-même
            ->orderBy('libelle')
            ->get();

        return view('admin.categories.edit', compact('categorie', 'parents'));
    }

    /**
     * Mettre à jour une catégorie.
     */
    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);

        $data = $request->validate([
            'libelle'     => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'parent_id'   => ['nullable', 'exists:categories,id', 'not_in:' . $id],
            'ordre'       => ['nullable', 'integer', 'min:0'],
            'actif'       => ['boolean'],
        ], [
            'parent_id.not_in' => 'Une catégorie ne peut pas être son propre parent.',
        ]);

        $nouveauActif = $request->boolean('actif');
        $data['actif'] = $nouveauActif;
        $data['ordre'] = $data['ordre'] ?? 0;

        // Bloquer la désactivation si la catégorie est dans une commande active
        if ($categorie->actif === true && $nouveauActif === false) {
            if ($categorie->estDansCommandeActive()) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Impossible de désactiver cette catégorie : elle contient des produits présents dans des commandes en cours.'
                    );
            }
        }

        $categorie->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie mise à jour.');
    }

    /**
     * Supprimer une catégorie.
     */
    public function destroy($id)
    {
        $categorie = Categorie::findOrFail($id);

        // 1. Vérifier produits associés
        $nombreProduits = Produit::where('categorie_id', $categorie->id)->count();
        if ($nombreProduits > 0) {
            return back()->with(
                'error',
                "Impossible de supprimer cette catégorie : elle contient {$nombreProduits} produit(s)."
            );
        }

        // 2. Vérifier sous-catégories
        $nombreEnfants = Categorie::where('parent_id', $categorie->id)->count();
        if ($nombreEnfants > 0) {
            return back()->with(
                'error',
                "Impossible de supprimer cette catégorie : elle a {$nombreEnfants} sous-catégorie(s)."
            );
        }

        // 3. Vérifier commande active
        if ($categorie->estDansCommandeActive()) {
            return back()->with(
                'error',
                'Impossible de supprimer cette catégorie : elle contient des produits présents dans des commandes en cours.'
            );
        }

        $categorie->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Catégorie supprimée.');
    }
}