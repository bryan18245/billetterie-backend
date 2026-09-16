@extends('layouts.app')

@section('title', 'ShopCI — Accueil')

@section('content')

{{-- Hero --}}
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1>Bienvenue sur ShopCI</h1>
            <p>Découvrez notre sélection de produits de qualité, livrés partout en Côte d'Ivoire.</p>
            <div class="hero-actions">
                <a href="{{ route('catalogue') }}" class="btn btn-primary">
                    <i class="fa-solid fa-bag-shopping"></i>
                    Voir tous les produits
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Catégories --}}
<section class="container section">
    <h2 class="section-title">Nos catégories</h2>

    <div class="categories-slider-wrapper">
        <button class="slider-btn slider-prev" onclick="slideCategories(-1)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="categories-slider" id="categoriesSlider">
            @forelse($categories as $categorie)
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
            $icone = $icones[$categorie->libelle] ?? 'fa-tag';
            @endphp

            <a href="{{ route('categorie.show', $categorie->id) }}" class="categorie-card">
                <div class="categorie-icon">
                    <i class="fa-solid {{ $icone }}"></i>
                </div>
                <div class="categorie-nom">{{ $categorie->libelle }}</div>
            </a>
            @empty
            <p>Aucune catégorie disponible.</p>
            @endforelse
        </div>

        <button class="slider-btn slider-next" onclick="slideCategories(1)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>
</section>

{{-- Produits populaires --}}
@if($populaires->count() > 0)
<section class="container section">
    <div class="section-header">
        <h2 class="section-title">Produits populaires</h2>
        <a href="{{ route('catalogue', ['tri' => 'populaire']) }}" class="link-more">
            Voir plus →
        </a>
    </div>

    <div class="produits-grid">
        @foreach($populaires as $produit)
        @include('partials.produit-card', ['produit' => $produit])
        @endforeach
    </div>
</section>
@endif

{{-- Nouveautés --}}
@if($nouveaux->count() > 0)
<section class="container section">
    <div class="section-header">
        <h2 class="section-title">Nouveautés</h2>
        <a href="{{ route('catalogue', ['tri' => 'recent']) }}" class="link-more">
            Voir plus →
        </a>
    </div>

    <div class="produits-grid">
        @foreach($nouveaux as $produit)
        @include('partials.produit-card', ['produit' => $produit])
        @endforeach
    </div>
</section>
@endif

{{-- Promotions --}}
@if($promotions->count() > 0)
<section class="container section">
    <div class="section-header">
        <h2 class="section-title">Promotions</h2>
    </div>

    <div class="produits-grid">
        @foreach($promotions as $produit)
        @include('partials.produit-card', ['produit' => $produit])
        @endforeach
    </div>
</section>
@endif

@endsection