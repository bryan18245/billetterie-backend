@extends('layouts.app')

@section('title', 'Mes commandes — ShopCI')

@section('content')

<div class="container section">

    <h1 class="page-title">Mes commandes</h1>

    @if($commandes->count() > 0)

    <div class="commandes-list">

        @foreach($commandes as $commande)
        <div class="commande-card">

            <div class="commande-header">
                <div>
                    <strong>{{ $commande->reference_unique }}</strong>
                    <p class="text-muted">
                        {{ $commande->date_commande->format('d/m/Y') }}
                    </p>
                </div>

                <div>
                    @php
                    $statuts = [
                    'en_attente' => ['label' => 'En attente', 'class' => 'warning'],
                    'payee' => ['label' => 'Payée', 'class' => 'info'],
                    'en_preparation'=> ['label' => 'En préparation','class' => 'info'],
                    'expediee' => ['label' => 'Expédiée', 'class' => 'primary'],
                    'livree' => ['label' => 'Livrée', 'class' => 'success'],
                    'annulee' => ['label' => 'Annulée', 'class' => 'danger'],
                    ];
                    $statut = $statuts[$commande->statut] ?? ['label' => $commande->statut, 'class' => 'secondary'];
                    @endphp

                    <span class="badge badge-{{ $statut['class'] }}">
                        {{ $statut['label'] }}
                    </span>
                </div>
            </div>

            <div class="commande-body">
                <p>
                    <strong>{{ $commande->lignes->count() }}</strong> article(s) ·
                    <strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong>
                </p>
            </div>

            <div class="commande-footer">
                <a href="{{ route('suivi.show', $commande->reference_unique) }}" class="btn btn-primary btn-small">
                    Voir le détail
                </a>
            </div>

        </div>
        @endforeach

    </div>

    @else

    <div class="empty-state">
        <p>📦 Vous n'avez pas encore de commande.</p>
        <a href="{{ route('catalogue') }}" class="btn btn-primary mt-3">
            Découvrir les produits
        </a>
    </div>

    @endif

</div>

@endsection