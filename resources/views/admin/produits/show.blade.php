@extends('layouts.admin')

@section('title', $produit->nom)
@section('page-title', $produit->nom)

@section('page-actions')
<a href="{{ route('admin.produits.edit', $produit->id) }}" class="btn btn-primary btn-small">
    <i class="fa-solid fa-pen"></i> Modifier
</a>
<a href="{{ route('admin.produits.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')

<div class="product-detail-grid">

    {{-- Images --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2><i class="fa-solid fa-images"></i> Images</h2>
        </div>
        <div class="admin-card-body">
            @if($produit->images->count() > 0)
            <div class="product-images-gallery">
                @foreach($produit->images as $image)
                <img src="{{ $image->chemin }}" alt="{{ $image->alt }}">
                @endforeach
            </div>
            @else
            <p class="text-muted text-center">Aucune image</p>
            @endif
        </div>
    </div>

    {{-- Infos --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2><i class="fa-solid fa-info-circle"></i> Informations</h2>
        </div>

        <div class="info-list">
            <div class="info-line">
                <span>Catégorie</span>
                <strong>{{ $produit->categorie?->libelle ?? '—' }}</strong>
            </div>
            <div class="info-line">
                <span>SKU</span>
                <strong>{{ $produit->sku }}</strong>
            </div>
            <div class="info-line">
                <span>Prix normal</span>
                <strong>{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</strong>
            </div>
            @if($produit->prix_promo)
            <div class="info-line">
                <span>Prix promo</span>
                <strong style="color: var(--danger);">{{ number_format($produit->prix_promo, 0, ',', ' ') }}
                    FCFA</strong>
            </div>
            @endif
            <div class="info-line">
                <span>Stock</span>
                <strong>{{ $produit->stock }}</strong>
            </div>
            <div class="info-line">
                <span>Ventes</span>
                <strong>{{ $produit->nb_ventes }}</strong>
            </div>
            <div class="info-line">
                <span>Note moyenne</span>
                <strong>{{ $produit->note_moyenne }} / 5</strong>
            </div>
            <div class="info-line">
                <span>Statut</span>
                <strong>
                    @if($produit->actif)
                    <span class="badge badge-success">Actif</span>
                    @else
                    <span class="badge badge-danger">Inactif</span>
                    @endif
                </strong>
            </div>
        </div>
    </div>

</div>

{{-- Description --}}
@if($produit->description)
<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fa-solid fa-align-left"></i> Description</h2>
    </div>
    <div class="admin-card-body">
        <p>{{ $produit->description }}</p>
    </div>
</div>
@endif

@endsection