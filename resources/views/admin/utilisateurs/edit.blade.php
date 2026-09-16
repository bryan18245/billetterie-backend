@extends('layouts.admin')

@section('title', 'Modifier ' . $utilisateur->name)
@section('page-title', 'Modifier l\'utilisateur')

@section('page-actions')
<a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')

<form method="POST" action="{{ route('admin.utilisateurs.update', $utilisateur->id) }}">
    @csrf
    @method('PUT')

    <div class="admin-card">
        <div class="admin-card-body">
            @include('admin.utilisateurs._form', ['utilisateur' => $utilisateur])
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-check"></i> Enregistrer
        </button>
        <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline">Annuler</a>
    </div>
</form>

@endsection