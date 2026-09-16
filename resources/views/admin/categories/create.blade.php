@extends('layouts.admin')

@section('title', 'Nouvelle catégorie')
@section('page-title', 'Nouvelle catégorie')

@section('page-actions')
<a href="{{ route('admin.categories.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.categories.store') }}">
    @csrf

    <div class="admin-card">
        <div class="admin-card-body">
            @include('admin.categories._form')
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-check"></i> Créer la catégorie
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Annuler</a>
    </div>
</form>

@endsection