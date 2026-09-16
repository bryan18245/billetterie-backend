@extends('layouts.admin')

@section('title', 'Nouveau produit')
@section('page-title', 'Nouveau produit')

@section('page-actions')
<a href="{{ route('admin.produits.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.produits.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="admin-card">
        <div class="admin-card-body">
            @include('admin.produits._form')
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-check"></i> Créer le produit
        </button>
        <a href="{{ route('admin.produits.index') }}" class="btn btn-outline">Annuler</a>
    </div>
</form>

@endsection