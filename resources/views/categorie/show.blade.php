@extends('layouts.app')

@section('title', $categorie->libelle . ' — ShopCI')

@section('content')

{{-- ═══════════════════════════════════════════════════════
     SLIDER DE CATÉGORIES (sticky)
     ═══════════════════════════════════════════════════════ --}}
<section class="categories-section">
    <div class="categories-slider-wrapper">
        <div class="container categories-slider-inner">
            <button class="slider-btn slider-prev" onclick="slideCategories(-1)">
                <i class="fa-solid fa-chevron-left"></i>
            </button>

            <div class="categories-slider" id="categoriesSlider">
                @forelse($categories as $cat)
                @php
                $icones = [
                'Mode' => 'fa-shirt',
                'Électronique' => 'fa-mobile-screen',
                'Maison' => 'fa-couch',
                'Beauté' => 'fa-spa',
                'Alimentation' => 'fa-utensils',
                'Sport' => 'fa-futbol',
                'Animaux' => 'fa-paw',
                'Livres' => 'fa-book',
                ];
                $icone = $icones[$cat->libelle] ?? 'fa-tag';
                $active = $cat->id === $categorie->id;
                @endphp

                <a href="{{ route('categorie.show', $cat->id) }}" class="categorie-card {{ $active ? 'active' : '' }}">
                    <div class="categorie-icon">
                        <i class="fa-solid {{ $icone }}"></i>
                    </div>
                    <div class="categorie-nom">{{ $cat->libelle }}</div>
                </a>
                @empty
                <p>Aucune catégorie disponible.</p>
                @endforelse
            </div>

            <button class="slider-btn slider-next" onclick="slideCategories(1)">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     CONTENU DE LA CATÉGORIE
     ═══════════════════════════════════════════════════════ --}}
<section class="container section">

    <div class="categorie-header">
        <h1 class="page-title">{{ $categorie->libelle }}</h1>

        @if($categorie->description)
        <p class="text-muted">{{ $categorie->description }}</p>
        @endif
    </div>

    <div class="resultats-info">
        <i class="fa-solid fa-box"></i>
        <span>{{ $produits->total() }} produit(s) trouvé(s)</span>
    </div>

    @if($produits->count() > 0)
    <div class="produits-grid">
        @foreach($produits as $produit)
        @include('partials.produit-card', ['produit' => $produit])
        @endforeach
    </div>

    <div class="pagination-wrapper">
        {{ $produits->links('vendor.pagination.custom') }}
    </div>
    @else
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <p>Aucun produit dans cette catégorie pour le moment.</p>
        <a href="{{ route('catalogue') }}" class="btn btn-primary">
            <i class="fa-solid fa-bag-shopping"></i>
            Voir tout le catalogue
        </a>
    </div>
    @endif

</section>

@endsection