@extends('layouts.admin')

@section('title', $utilisateur->name . ' ' . $utilisateur->surname)
@section('page-title', 'Détail utilisateur')

@section('page-actions')
<a href="{{ route('admin.utilisateurs.edit', $utilisateur->id) }}" class="btn btn-primary btn-small">
    <i class="fa-solid fa-pen"></i> Modifier
</a>
<a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')

<div class="commande-grid">

    <div class="admin-card">
        <div class="admin-card-header">
            <h2><i class="fa-solid fa-user"></i> Informations</h2>
        </div>

        <div class="info-list">
            <div class="info-line">
                <span>Nom complet</span>
                <strong>{{ $utilisateur->name }} {{ $utilisateur->surname }}</strong>
            </div>
            <div class="info-line">
                <span>Email</span>
                <strong>{{ $utilisateur->email }}</strong>
            </div>
            @if($utilisateur->phone)
            <div class="info-line">
                <span>Téléphone</span>
                <strong>{{ $utilisateur->phone }}</strong>
            </div>
            @endif
            <div class="info-line">
                <span>Rôle</span>
                <strong><span class="badge badge-primary">{{ $utilisateur->role?->libelle }}</span></strong>
            </div>
            <div class="info-line">
                <span>Statut</span>
                <strong>
                    <span class="badge badge-{{ $utilisateur->statut === 'actif' ? 'success' : 'danger' }}">
                        {{ ucfirst($utilisateur->statut ?? 'actif') }}
                    </span>
                </strong>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h2><i class="fa-solid fa-palette"></i> Préférences</h2>
        </div>

        <div class="info-list">
            <div class="info-line">
                <span>Langue</span>
                <strong>{{ $utilisateur->langue === 'fr' ? 'Français' : 'English' }}</strong>
            </div>
            <div class="info-line">
                <span>Thème</span>
                <strong>{{ ucfirst($utilisateur->theme) }}</strong>
            </div>
            <div class="info-line">
                <span>Inscrit le</span>
                <strong>{{ $utilisateur->created_at->format('d/m/Y à H:i') }}</strong>
            </div>
            <div class="info-line">
                <span>Dernière connexion</span>
                <strong>
                    {{ $utilisateur->date_derniere_connexion?->format('d/m/Y à H:i') ?? 'Jamais' }}
                </strong>
            </div>
        </div>
    </div>

</div>

@endsection