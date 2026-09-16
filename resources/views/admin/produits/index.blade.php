@extends('layouts.admin')

@section('title', 'Produits')
@section('page-title', 'Produits')

@section('page-actions')
<a href="{{ route('admin.produits.create') }}" class="btn btn-primary btn-small">
    <i class="fa-solid fa-plus"></i>
    Nouveau produit
</a>
@endsection

@section('content')

{{-- Filtres --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fa-solid fa-filter"></i> Filtres</h2>
    </div>

    <form method="GET" action="{{ route('admin.produits.index') }}" class="admin-filters">
        <div class="filter-row">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Nom du produit...">
            </div>

            <div class="filter-group">
                <label>Catégorie</label>
                <select name="categorie" class="form-control">
                    <option value="">Toutes</option>
                    @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->libelle }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Statut</label>
                <select name="actif" class="form-control">
                    <option value="">Tous</option>
                    <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                    <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Stock</label>
                <select name="stock" class="form-control">
                    <option value="">Tous</option>
                    <option value="faible" {{ request('stock') === 'faible' ? 'selected' : '' }}>Stock faible (≤5)
                    </option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.produits.index') }}" class="btn btn-outline">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Liste --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-list"></i>
            {{ $produits->total() }} produit(s)
        </h2>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produits as $produit)
                <tr>
                    <td data-label="Image">
                        <div class="product-thumb">
                            @if($produit->imagePrincipale)
                            <img src="{{ $produit->imagePrincipale->chemin }}" alt="{{ $produit->nom }}">
                            @else
                            <div class="thumb-placeholder">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td data-label="Nom">
                        <strong>{{ $produit->nom }}</strong>
                        @if($produit->sku)
                        <br><small class="text-muted">{{ $produit->sku }}</small>
                        @endif
                    </td>
                    <td data-label="Catégorie">
                        <span class="badge badge-info">{{ $produit->categorie?->libelle ?? '—' }}</span>
                    </td>
                    <td data-label="Prix">
                        @if($produit->en_promo)
                        <span class="text-muted" style="text-decoration: line-through; font-size: 12px;">
                            {{ number_format($produit->prix, 0, ',', ' ') }}
                        </span>
                        <br>
                        <strong style="color: var(--danger);">
                            {{ number_format($produit->prix_promo, 0, ',', ' ') }} FCFA
                        </strong>
                        @else
                        <strong>{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</strong>
                        @endif
                    </td>
                    <td data-label="Stock">
                        <span
                            class="badge badge-{{ $produit->stock <= 0 ? 'danger' : ($produit->stock <= 5 ? 'warning' : 'success') }}">
                            {{ $produit->stock }}
                        </span>
                    </td>
                    <td data-label="Statut">
                        @if($produit->actif)
                        <span class="badge badge-success">Actif</span>
                        @else
                        <span class="badge badge-danger">Inactif</span>
                        @endif
                    </td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <a href="{{ route('admin.produits.show', $produit->id) }}" class="btn-icon" title="Voir">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.produits.edit', $produit->id) }}" class="btn-icon"
                                title="Modifier">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.produits.destroy', $produit->id) }}"
                                data-confirm="Voulez-vous vraiment supprimer le produit « {{ $produit->nom }} » ? Cette action est irréversible."
                                data-confirm-title="Supprimer le produit" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-danger" title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Aucun produit trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($produits->hasPages())
    <div class="admin-pagination">
        {{ $produits->links() }}
    </div>
    @endif
</div>

@endsection