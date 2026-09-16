@extends('layouts.admin')

@section('title', 'Nouvel utilisateur')
@section('page-title', 'Nouvel utilisateur')

@section('page-actions')
<a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.utilisateurs.store') }}">
    @csrf

    <div class="admin-card">
        <div class="admin-card-body">
            @include('admin.utilisateurs._form')
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-check"></i> Créer l'utilisateur
        </button>
        <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline">Annuler</a>
    </div>
</form>

@endsection