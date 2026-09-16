@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Tableau de bord')

@section('content')

{{-- ═══ Alertes ═══ --}}
@if($produitsRupture->count() > 0 || $commandesNonPayees->count() > 0)
<div class="dashboard-alerts">
    @if($produitsRupture->count() > 0)
    <div class="alert alert-warning">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
            <strong>{{ $produitsRupture->count() }} produit(s) en stock faible</strong>
            <p>Certains produits ont un stock inférieur ou égal à 5.</p>
        </div>
        <a href="{{ route('admin.produits.index', ['stock' => 'faible']) }}" class="alert-link">
            Voir →
        </a>
    </div>
    @endif

    @if($commandesNonPayees->count() > 0)
    <div class="alert alert-error">
        <i class="fa-solid fa-clock"></i>
        <div>
            <strong>{{ $commandesNonPayees->count() }} commande(s) non payée(s) depuis +3 jours</strong>
            <p>Ces commandes risquent d'être abandonnées.</p>
        </div>
        <a href="{{ route('admin.commandes.index') }}" class="alert-link">
            Voir →
        </a>
    </div>
    @endif
</div>
@endif

{{-- ═══ Stats principales ═══ --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-box"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Commandes aujourd'hui</span>
            <strong class="stat-value">{{ $stats['commandes_jour'] }}</strong>
            <small class="stat-hint">{{ $stats['commandes_total'] }} au total</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fa-solid fa-coins"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">CA aujourd'hui</span>
            <strong class="stat-value">{{ number_format($stats['ca_jour'], 0, ',', ' ') }} <small>FCFA</small></strong>
            <small class="stat-hint">{{ number_format($stats['ca_mois'], 0, ',', ' ') }} ce mois</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">En attente</span>
            <strong class="stat-value">{{ $stats['commandes_attente'] }}</strong>
            <small class="stat-hint">à traiter</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fa-solid fa-tags"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Produits actifs</span>
            <strong class="stat-value">{{ $stats['produits_actifs'] }}</strong>
            <small class="stat-hint">sur {{ $stats['produits_total'] }}</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Clients</span>
            <strong class="stat-value">{{ $stats['clients_total'] }}</strong>
        </div>
    </div>
</div>

{{-- ═══ Graphique CA ═══ --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-chart-line"></i>
            Chiffre d'affaires — 30 derniers jours
        </h2>
    </div>
    <div class="admin-card-body">
        <canvas id="chartCA" height="80" data-labels='@json($chartData["labels"])'
            data-totaux='@json($chartData["totaux"])'></canvas>
    </div>
</div>

{{-- ═══ Top produits + Top clients ═══ --}}
<div class="dashboard-two-cols">

    {{-- Top produits --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2>
                <i class="fa-solid fa-trophy"></i>
                Top 5 produits vendus
            </h2>
        </div>
        <div class="admin-card-body">
            @forelse($topProduits as $index => $item)
            <div class="top-item">
                <div class="top-rank top-rank-{{ $index + 1 }}">
                    {{ $index + 1 }}
                </div>

                <div class="top-image">
                    @if($item->image)
                    <img src="{{ $item->image }}" alt="{{ $item->nom_produit }}">
                    @else
                    <i class="fa-solid fa-image"></i>
                    @endif
                </div>

                <div class="top-info">
                    <strong>{{ $item->nom_produit }}</strong>
                    <small>{{ $item->total_vendus }} vendu(s)</small>
                </div>

                <div class="top-value">
                    {{ number_format($item->ca_genere, 0, ',', ' ') }} <small>FCFA</small>
                </div>
            </div>
            @empty
            <p class="text-center text-muted">Aucune vente pour le moment.</p>
            @endforelse
        </div>
    </div>

    {{-- Top clients --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2>
                <i class="fa-solid fa-crown"></i>
                Top 5 clients
            </h2>
        </div>
        <div class="admin-card-body">
            @forelse($topClients as $index => $client)
            <div class="top-item">
                <div class="top-rank top-rank-{{ $index + 1 }}">
                    {{ $index + 1 }}
                </div>

                <div class="client-avatar">
                    {{ strtoupper(substr($client->nom, 0, 1)) }}
                </div>

                <div class="top-info">
                    <strong>{{ $client->nom }}</strong>
                    <small>{{ $client->commandes_count }} commande(s)</small>
                </div>

                <div class="top-value">
                    {{ number_format($client->ca_total ?? 0, 0, ',', ' ') }} <small>FCFA</small>
                </div>
            </div>
            @empty
            <p class="text-center text-muted">Aucun client pour le moment.</p>
            @endforelse
        </div>
    </div>

</div>

{{-- ═══ Commandes en attente ═══ --}}
@if($commandesEnAttente->count() > 0)
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-hourglass-half"></i>
            Commandes à traiter ({{ $commandesEnAttente->count() }})
        </h2>
        <a href="{{ route('admin.commandes.index', ['statut' => 'en_attente']) }}" class="btn btn-outline btn-small">
            Voir tout <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commandesEnAttente as $commande)
                <tr>
                    <td data-label="Référence"><strong>{{ $commande->reference_unique }}</strong></td>
                    <td data-label="Client">{{ $commande->client->nom }}</td>
                    <td data-label="Montant">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</td>
                    <td data-label="Date">{{ $commande->date_commande->diffForHumans() }}</td>
                    <td data-label="Actions">
                        <a href="{{ route('admin.commandes.show', $commande->id) }}" class="btn-icon" title="Voir">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ═══ Dernières commandes ═══ --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-clock-rotate-left"></i>
            Dernières commandes
        </h2>
        <a href="{{ route('admin.commandes.index') }}" class="btn btn-outline btn-small">
            Voir tout <i class="fa-solid fa-arrow-right"></i>
        </a>
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
                </tr>
            </thead>
            <tbody>
                @forelse($dernieresCommandes as $commande)
                <tr>
                    <td data-label="Référence">
                        <strong>{{ $commande->reference_unique }}</strong>
                    </td>
                    <td data-label="Client">{{ $commande->client->nom }}</td>
                    <td data-label="Montant">{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</td>
                    <td data-label="Statut">
                        @php
                        $badgeClass = match($commande->statut) {
                        'en_attente' => 'warning',
                        'confirmee' => 'info',
                        'en_preparation' => 'info',
                        'expediee' => 'info',
                        'livree' => 'success',
                        'annulee' => 'danger',
                        default => 'secondary',
                        };
                        @endphp
                        <span class="badge badge-{{ $badgeClass }}">
                            {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                        </span>
                    </td>
                    <td data-label="Date">{{ $commande->date_commande->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Aucune commande</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ═══ Stock faible ═══ --}}
@if($produitsRupture->count() > 0)
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-triangle-exclamation"></i>
            Stock faible ({{ $produitsRupture->count() }})
        </h2>
        <a href="{{ route('admin.produits.index', ['stock' => 'faible']) }}" class="btn btn-outline btn-small">
            Voir tout <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Catégorie</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produitsRupture as $produit)
                <tr>
                    <td data-label="Produit"><strong>{{ $produit->nom }}</strong></td>
                    <td data-label="Catégorie">{{ $produit->categorie?->libelle ?? '—' }}</td>
                    <td data-label="Stock">
                        <span class="badge badge-{{ $produit->stock == 0 ? 'danger' : 'warning' }}">
                            {{ $produit->stock }} restant(s)
                        </span>
                    </td>
                    <td data-label="Actions">
                        <a href="{{ route('admin.produits.edit', $produit->id) }}" class="btn-icon"
                            title="Réapprovisionner">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
(function() {
    'use strict';

    // === Chart.js ===
    const ctx = document.getElementById('chartCA');
    if (ctx) {
        const labels = JSON.parse(ctx.dataset.labels || '[]');
        const totaux = JSON.parse(ctx.dataset.totaux || '[]');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'CA (FCFA)',
                    data: totaux,
                    borderColor: '#32B394',
                    backgroundColor: 'rgba(50, 179, 148, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#32B394',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1a1f2e',
                        padding: 12,
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                const value = context.parsed.y;
                                return ' ' + new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                                if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                                return value;
                            },
                            font: {
                                size: 11
                            },
                            color: '#666'
                        },
                        grid: {
                            color: '#f0f0f0'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#666',
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 10
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
})();
</script>
@endpush