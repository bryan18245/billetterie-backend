@extends('layouts.admin')

@section('title', 'Commandes')
@section('page-title', 'Commandes')

@section('content')

{{-- Stats rapides --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-box"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Total</span>
            <strong class="stat-value">{{ $stats['total'] }}</strong>
            <small class="stat-hint">commandes</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">En attente</span>
            <strong class="stat-value">{{ $stats['en_attente'] }}</strong>
            <small class="stat-hint">à traiter</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fa-solid fa-truck-fast"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">En cours</span>
            <strong class="stat-value">{{ $stats['en_cours'] }}</strong>
            <small class="stat-hint">en préparation / expédiées</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Livrées</span>
            <strong class="stat-value">{{ $stats['livree'] }}</strong>
            <small class="stat-hint">terminées</small>
        </div>
    </div>
</div>

{{-- Filtres --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-filter"></i>
            Filtres
        </h2>
    </div>

    <form method="GET" action="{{ route('admin.commandes.index') }}" class="admin-filters">
        <div class="filter-row">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Référence, nom, téléphone...">
            </div>

            <div class="filter-group">
                <label>Statut</label>
                <select name="statut" class="form-control">
                    <option value="">Tous les statuts</option>
                    <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente
                    </option>
                    <option value="confirmee" {{ request('statut') === 'confirmee' ? 'selected' : '' }}>Confirmée
                    </option>
                    <option value="en_preparation" {{ request('statut') === 'en_preparation' ? 'selected' : '' }}>En
                        préparation</option>
                    <option value="expediee" {{ request('statut') === 'expediee' ? 'selected' : '' }}>Expédiée</option>
                    <option value="livree" {{ request('statut') === 'livree' ? 'selected' : '' }}>Livrée</option>
                    <option value="annulee" {{ request('statut') === 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Date début</label>
                <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
            </div>

            <div class="filter-group">
                <label>Date fin</label>
                <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i>
                    Filtrer
                </button>
                <a href="{{ route('admin.commandes.index') }}" class="btn btn-outline">
                    <i class="fa-solid fa-rotate-left"></i>
                    Réinitialiser
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
            {{ $commandes->total() }} commande(s)
        </h2>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($commandes as $commande)
                <tr>
                    <td data-label="Référence">
                        <strong>{{ $commande->reference_unique }}</strong>
                    </td>
                    <td data-label="Client">
                        {{ $commande->client->nom }}
                        <br>
                        <small class="text-muted">{{ $commande->client->telephone }}</small>
                    </td>
                    <td data-label="Montant">
                        <strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong>
                    </td>
                    <td data-label="Statut">
                        @php
                        $badgeClass = match($commande->statut) {
                        'en_attente' => 'warning',
                        'confirmee' => 'info',
                        'en_preparation' => 'info',
                        'expediee' => 'info',
                        'livree' => 'success',
                        'annulee' => 'danger',
                        default => 'info',
                        };
                        @endphp
                        <span class="badge badge-{{ $badgeClass }}">
                            {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                        </span>
                    </td>
                    <td data-label="Date">
                        {{ $commande->date_commande->format('d/m/Y H:i') }}
                    </td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <a href="{{ route('admin.commandes.show', $commande->id) }}" class="btn-icon" title="Voir">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">
                        Aucune commande trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($commandes->hasPages())
    <div class="admin-pagination">
        {{ $commandes->links() }}
    </div>
    @endif
</div>

@endsection