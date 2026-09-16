@extends('layouts.admin')

@section('title', 'Modifier ' . $produit->nom)
@section('page-title', 'Modifier le produit')

@section('page-actions')
<a href="{{ route('admin.produits.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')

@php
$verrouille = $produit->estDansCommandeActive();
@endphp

<form method="POST" action="{{ route('admin.produits.update', $produit->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="admin-card">
        <div class="admin-card-body">
            @include('admin.produits._form', ['produit' => $produit])
        </div>
    </div>

    <div class="form-actions">
        @if($verrouille)
        <button type="button" class="btn btn-disabled" disabled title="Produit verrouillé par des commandes en cours">
            <i class="fa-solid fa-lock"></i> Enregistrement bloqué
        </button>
        <span class="form-lock-hint">
            Livrez ou annulez les commandes en cours pour débloquer.
        </span>
        @else
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-check"></i> Enregistrer
        </button>
        @endif

        <a href="{{ route('admin.produits.index') }}" class="btn btn-outline">Annuler</a>
    </div>
</form>

{{-- Images : HORS du formulaire principal --}}
@if($produit->images->count() > 0)
<div class="admin-card" style="margin-top: 20px;">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-images"></i>
            Images actuelles ({{ $produit->images->count() }})
            @if($verrouille)
            <span class="badge badge-warning" style="margin-left: 8px;">
                <i class="fa-solid fa-lock"></i> Verrouillé
            </span>
            @endif
        </h2>
    </div>

    <div class="admin-card-body">
        <div class="images-grid">
            @foreach($produit->images as $image)
            <div class="image-item {{ $image->principale ? 'is-principale' : '' }}">
                <img src="{{ $image->chemin }}" alt="{{ $image->alt }}">

                @if($image->principale)
                <span class="image-badge">
                    <i class="fa-solid fa-star"></i> Principale
                </span>
                @endif

                @if(!$verrouille)
                <div class="image-actions">
                    @if(!$image->principale)
                    <form method="POST"
                        action="{{ route('admin.produits.images.principale', [$produit->id, $image->id]) }}"
                        style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-icon btn-icon-small" title="Définir comme principale">
                            <i class="fa-solid fa-star"></i>
                        </button>
                    </form>
                    @endif

                    <form method="POST"
                        action="{{ route('admin.produits.images.destroy', [$produit->id, $image->id]) }}"
                        data-confirm="Voulez-vous vraiment supprimer cette image ?"
                        data-confirm-title="Supprimer l'image" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-icon-small btn-icon-danger" title="Supprimer">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection