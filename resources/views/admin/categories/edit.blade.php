@extends('layouts.admin')

@section('title', 'Modifier ' . $categorie->libelle)
@section('page-title', 'Modifier la catégorie')

@section('page-actions')
<a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')

@php
$verrouille = $categorie->actif && $categorie->estDansCommandeActive();
@endphp

<form method="POST" action="{{ route('admin.categories.update', $categorie->id) }}">
    @csrf
    @method('PUT')

    <div class="admin-card">
        <div class="admin-card-body">
            @include('admin.categories._form', ['categorie' => $categorie])
        </div>
    </div>

    <div class="form-actions">
        @if($verrouille)
        <button type="button" class="btn btn-disabled" disabled
            title="Catégorie verrouillée par des commandes en cours">
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

        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Annuler</a>
    </div>
</form>

@endsection