@extends('layouts.admin')

@section('title', 'Clients')
@section('page-title', 'Clients')

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Total clients</span>
            <strong class="stat-value">{{ $stats['total'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Avec compte</span>
            <strong class="stat-value">{{ $stats['comptes'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fa-solid fa-user-secret"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Invités</span>
            <strong class="stat-value">{{ $stats['guests'] }}</strong>
            <small class="stat-hint">sans compte</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fa-solid fa-user-plus"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Nouveaux (7j)</span>
            <strong class="stat-value">{{ $stats['nouveaux'] }}</strong>
        </div>
    </div>
</div>

{{-- Filtres --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fa-solid fa-filter"></i> Filtres</h2>
    </div>

    <form method="GET" action="{{ route('admin.clients.index') }}" class="admin-filters">
        <div class="filter-row">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Nom, téléphone, email...">
            </div>

            <div class="filter-group">
                <label>Type</label>
                <select name="type" class="form-control">
                    <option value="">Tous</option>
                    <option value="compte" {{ request('type') === 'compte' ? 'selected' : '' }}>Avec compte</option>
                    <option value="guest" {{ request('type') === 'guest' ? 'selected' : '' }}>Invités</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Trier par</label>
                <select name="tri" class="form-control">
                    <option value="recent" {{ request('tri') === 'recent' ? 'selected' : '' }}>Plus récents</option>
                    <option value="nom" {{ request('tri') === 'nom' ? 'selected' : '' }}>Nom (A-Z)</option>
                    <option value="commandes" {{ request('tri') === 'commandes' ? 'selected' : '' }}>Nb de commandes
                    </option>
                    <option value="ca" {{ request('tri') === 'ca' ? 'selected' : '' }}>Chiffre d'affaires</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-outline">
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
            {{ $clients->total() }} client(s)
        </h2>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Contact</th>
                    <th>Type</th>
                    <th>Commandes</th>
                    <th>CA total</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                <tr>
                    <td data-label="Client">
                        <div class="client-cell">
                            <div class="client-avatar">
                                {{ strtoupper(substr($client->nom, 0, 1)) }}
                            </div>
                            <strong>{{ $client->nom }}</strong>
                        </div>
                    </td>
                    <td data-label="Contact">
                        <div class="contact-info">
                            <a href="tel:{{ $client->telephone }}">
                                <i class="fa-solid fa-phone"></i> {{ $client->telephone }}
                            </a>
                            @if($client->email)
                            <a href="mailto:{{ $client->email }}">
                                <i class="fa-solid fa-envelope"></i> {{ $client->email }}
                            </a>
                            @endif
                        </div>
                    </td>
                    <td data-label="Type">
                        @if($client->est_guest)
                        <span class="badge badge-warning">
                            <i class="fa-solid fa-user-secret"></i> Invité
                        </span>
                        @else
                        <span class="badge badge-info">
                            <i class="fa-solid fa-user"></i> Compte
                        </span>
                        @endif
                    </td>
                    <td data-label="Commandes">
                        <span class="badge badge-{{ $client->commandes_count > 0 ? 'primary' : 'secondary' }}">
                            {{ $client->commandes_count }}
                        </span>
                    </td>
                    <td data-label="CA total">
                        <strong>{{ number_format($client->commandes_sum_montant_total ?? 0, 0, ',', ' ') }}
                            FCFA</strong>
                    </td>
                    <td data-label="Inscrit le">
                        {{ $client->created_at->format('d/m/Y') }}
                    </td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <a href="{{ route('admin.clients.show', $client->id) }}" class="btn-icon" title="Voir">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $client->telephone) }}" target="_blank"
                                class="btn-icon btn-icon-whatsapp" title="WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>

                            @if($client->commandes_count === 0)
                            <form method="POST" action="{{ route('admin.clients.destroy', $client->id) }}"
                                data-confirm="Voulez-vous vraiment supprimer ce client ?"
                                data-confirm-title="Supprimer le client" style="display: inline;">
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
                    <td colspan="7" class="text-center text-muted">Aucun client trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clients->hasPages())
    <div class="admin-pagination">
        {{ $clients->links() }}
    </div>
    @endif
</div>

@endsection