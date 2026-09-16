@extends('layouts.admin')

@section('title', 'Client ' . $client->nom)
@section('page-title', 'Détail du client')

@section('page-actions')
<a href="https://wa.me/{{ preg_replace('/\D/', '', $client->telephone) }}" target="_blank"
    class="btn btn-primary btn-small">
    <i class="fa-brands fa-whatsapp"></i> WhatsApp
</a>
<a href="{{ route('admin.clients.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-box"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Commandes totales</span>
            <strong class="stat-value">{{ $stats['commandes_total'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Commandes livrées</span>
            <strong class="stat-value">{{ $stats['commandes_livrees'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fa-solid fa-coins"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">CA total</span>
            <strong class="stat-value">{{ number_format($stats['ca_total'], 0, ',', ' ') }} <small>FCFA</small></strong>
        </div>
    </div>

    @if($stats['derniere_commande'])
    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Dernière commande</span>
            <strong class="stat-value" style="font-size: 16px;">
                {{ $stats['derniere_commande']->format('d/m/Y') }}
            </strong>
            <small class="stat-hint">{{ $stats['derniere_commande']->format('H:i') }}</small>
        </div>
    </div>
    @endif
</div>

<div class="commande-grid">

    {{-- Infos client --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2><i class="fa-solid fa-user"></i> Informations</h2>
        </div>

        <div class="info-list">
            <div class="info-line">
                <span>Nom</span>
                <strong>{{ $client->nom }}</strong>
            </div>
            <div class="info-line">
                <span>Téléphone</span>
                <strong>
                    <a href="tel:{{ $client->telephone }}">
                        {{ $client->telephone }}
                    </a>
                </strong>
            </div>
            @if($client->email)
            <div class="info-line">
                <span>Email</span>
                <strong>
                    <a href="mailto:{{ $client->email }}">{{ $client->email }}</a>
                </strong>
            </div>
            @endif
            <div class="info-line">
                <span>Type</span>
                <strong>
                    @if($client->est_guest)
                    <span class="badge badge-warning">Invité</span>
                    @else
                    <span class="badge badge-info">Compte</span>
                    @endif
                </strong>
            </div>
            <div class="info-line">
                <span>Inscrit le</span>
                <strong>{{ $client->created_at->format('d/m/Y à H:i') }}</strong>
            </div>
        </div>
    </div>

    {{-- Adresses --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2><i class="fa-solid fa-location-dot"></i> Adresses ({{ $client->adresses->count() }})</h2>
        </div>

        <div class="info-list">
            @forelse($client->adresses as $adresse)
            <div class="address-block">
                <strong>{{ $adresse->libelle ?? 'Livraison' }}</strong>
                <p>{{ $adresse->adresse }}</p>
                <p>{{ $adresse->ville }}</p>
                @if($adresse->telephone)
                <p><i class="fa-solid fa-phone"></i> {{ $adresse->telephone }}</p>
                @endif
                @if($adresse->principale)
                <span class="badge badge-success">Principale</span>
                @endif
            </div>
            @empty
            <p class="text-muted text-center">Aucune adresse enregistrée.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- Commandes --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-receipt"></i>
            Historique des commandes ({{ $client->commandes->count() }})
        </h2>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Date</th>
                    <th>Articles</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($client->commandes as $commande)
                <tr>
                    <td data-label="Référence"><strong>{{ $commande->reference_unique }}</strong></td>
                    <td data-label="Date">{{ $commande->date_commande->format('d/m/Y H:i') }}</td>
                    <td data-label="Articles">{{ $commande->lignes->count() }} article(s)</td>
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
                    <td data-label="Actions">
                        <a href="{{ route('admin.commandes.show', $commande->id) }}" class="btn-icon"
                            title="Voir la commande">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Aucune commande.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection