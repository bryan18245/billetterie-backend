@extends('layouts.app')

@section('title', 'Catalogue — ShopCI')

@section('content')

<div class="container section">

    <h1 class="page-title">Catalogue</h1>

    <div class="catalogue-layout">

        {{-- Filtres --}}
        <aside class="filtres">
            <form method="GET" action="{{ route('catalogue') }}">

                <h3>Filtres</h3>

                {{-- Recherche --}}
                <div class="form-group">
                    <label for="search">Recherche</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Nom du produit...">
                </div>

                {{-- Catégorie --}}
                <div class="form-group">
                    <label for="categorie">Catégorie</label>
                    <select name="categorie" id="categorie" class="form-control">
                        <option value="">Toutes</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('categorie') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->libelle }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Prix --}}
                <div class="form-group">
                    <label>Prix (FCFA)</label>
                    <div class="form-row">
                        <input type="number" name="prix_min" class="form-control" value="{{ request('prix_min') }}"
                            placeholder="Min">
                        <input type="number" name="prix_max" class="form-control" value="{{ request('prix_max') }}"
                            placeholder="Max">
                    </div>
                </div>

                {{-- Tri --}}
                <div class="form-group">
                    <label for="tri">Trier par</label>
                    <select name="tri" id="tri" class="form-control">
                        <option value="recent" {{ request('tri') == 'recent' ? 'selected' : '' }}>
                            Plus récents
                        </option>
                        <option value="prix_asc" {{ request('tri') == 'prix_asc' ? 'selected' : '' }}>
                            Prix croissant
                        </option>
                        <option value="prix_desc" {{ request('tri') == 'prix_desc' ? 'selected' : '' }}>
                            Prix décroissant
                        </option>
                        <option value="populaire" {{ request('tri') == 'populaire' ? 'selected' : '' }}>
                            Plus populaires
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    Appliquer les filtres
                </button>

                <a href="{{ route('catalogue') }}" class="btn btn-outline btn-block mt-2">
                    Réinitialiser
                </a>

            </form>
        </aside>

        {{-- Produits --}}
        <div class="produits-container">

            {{-- Résultat --}}
            <div class="resultats-info">
                <p>{{ $produits->total() }} produit(s) trouvé(s)</p>
            </div>

            @if($produits->count() > 0)
            <div class="produits-grid">
                @foreach($produits as $produit)
                @include('partials.produit-card', ['produit' => $produit])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pagination-wrapper">
                {{ $produits->links('vendor.pagination.custom') }}
            </div>
            @else
            <div class="empty-state">
                <p>😕 Aucun produit ne correspond à vos critères.</p>
                <a href="{{ route('catalogue') }}" class="btn btn-primary mt-2">
                    Voir tous les produits
                </a>
            </div>
            @endif

        </div>

    </div>
</div>

@endsection