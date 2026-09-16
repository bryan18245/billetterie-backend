@extends('layouts.admin')

@section('title', 'Utilisateurs')
@section('page-title', 'Utilisateurs')

@section('page-actions')
<a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-primary btn-small">
    <i class="fa-solid fa-plus"></i> Nouvel utilisateur
</a>
@endsection

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Total</span>
            <strong class="stat-value">{{ $stats['total'] }}</strong>
            <small class="stat-hint">utilisateurs</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fa-solid fa-user-shield"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Admins</span>
            <strong class="stat-value">{{ $stats['admins'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Clients</span>
            <strong class="stat-value">{{ $stats['clients'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Actifs</span>
            <strong class="stat-value">{{ $stats['actifs'] }}</strong>
        </div>
    </div>
</div>

{{-- Filtres --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fa-solid fa-filter"></i> Filtres</h2>
    </div>

    <form method="GET" action="{{ route('admin.utilisateurs.index') }}" class="admin-filters">
        <div class="filter-row">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Nom, email, téléphone...">
            </div>

            <div class="filter-group">
                <label>Rôle</label>
                <select name="role" class="form-control">
                    <option value="">Tous</option>
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                        {{ $role->libelle }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Statut</label>
                <select name="statut" class="form-control">
                    <option value="">Tous</option>
                    <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="suspendu" {{ request('statut') === 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-outline">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Liste --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-list"></i>
            {{ $utilisateurs->total() }} utilisateur(s)
        </h2>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Contact</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Dernière connexion</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($utilisateurs as $user)
                <tr>
                    <td data-label="Utilisateur">
                        <div class="client-cell">
                            <div class="client-avatar">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <strong>{{ $user->name }} {{ $user->surname }}</strong>
                                <br>
                                <small class="text-muted">Inscrit le {{ $user->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    </td>
                    <td data-label="Contact">
                        <div class="contact-info">
                            <a href="mailto:{{ $user->email }}">
                                <i class="fa-solid fa-envelope"></i> {{ $user->email }}
                            </a>
                            @if($user->phone)
                            <a href="tel:{{ $user->phone }}">
                                <i class="fa-solid fa-phone"></i> {{ $user->phone }}
                            </a>
                            @endif
                        </div>
                    </td>
                    <td data-label="Rôle">
                        @php
                        $roleBadge = match($user->role?->libelle) {
                        'super_admin' => 'danger',
                        'admin' => 'primary',
                        'client' => 'info',
                        default => 'secondary',
                        };
                        @endphp
                        <span class="badge badge-{{ $roleBadge }}">
                            {{ $user->role?->libelle ?? '—' }}
                        </span>
                    </td>
                    <td data-label="Statut">
                        <span
                            class="badge badge-{{ $user->statut === 'actif' ? 'success' : ($user->statut === 'suspendu' ? 'danger' : 'warning') }}">
                            {{ ucfirst($user->statut ?? 'actif') }}
                        </span>
                    </td>
                    <td data-label="Dernière connexion">
                        {{ $user->date_derniere_connexion?->format('d/m/Y H:i') ?? 'Jamais' }}
                    </td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <a href="{{ route('admin.utilisateurs.show', $user->id) }}" class="btn-icon" title="Voir">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            <a href="{{ route('admin.utilisateurs.edit', $user->id) }}" class="btn-icon"
                                title="Modifier">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.utilisateurs.toggle', $user->id) }}"
                                style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-icon"
                                    title="{{ $user->statut === 'actif' ? 'Désactiver' : 'Activer' }}">
                                    <i
                                        class="fa-solid fa-{{ $user->statut === 'actif' ? 'circle-pause' : 'circle-play' }}"></i>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.utilisateurs.destroy', $user->id) }}"
                                data-confirm="Voulez-vous vraiment supprimer cet utilisateur ?"
                                data-confirm-title="Supprimer l'utilisateur" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-danger" title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Aucun utilisateur trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($utilisateurs->hasPages())
    <div class="admin-pagination">
        {{ $utilisateurs->links() }}
    </div>
    @endif
</div>

@endsection