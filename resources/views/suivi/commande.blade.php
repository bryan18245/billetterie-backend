@extends('layouts.app')

@section('title', 'Commande ' . $commande->reference_unique . ' — ShopCI')

@section('content')

<div class="container section">

    <h1 class="page-title">Commande {{ $commande->reference_unique }}</h1>

    {{-- Statut --}}
    <div class="card">
        <div class="card-body">
            <h3>Statut actuel</h3>

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

            <p class="mt-2">
                Date : {{ $commande->date_commande->format('d/m/Y à H:i') }}
            </p>
        </div>
    </div>

    {{-- Détails --}}
    <div class="card mt-4">
        <div class="card-body">
            <h3>Détails de la commande</h3>

            <table class="table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commande->lignes as $ligne)
                    <tr>
                        <td>
                            {{ $ligne->nom_produit }}
                            @if($ligne->options_choisies)
                            <br>
                            <small class="text-muted">
                                @foreach($ligne->options_choisies as $nom => $valeur)
                                {{ $nom }} : {{ $valeur }}
                                @if(!$loop->last) · @endif
                                @endforeach
                            </small>
                            @endif
                        </td>
                        <td>{{ $ligne->quantite }}</td>
                        <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong>Total</strong></td>
                        <td><strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Livraison --}}
    @if($commande->adresse)
    <div class="card mt-4">
        <div class="card-body">
            <h3>Adresse de livraison</h3>
            <p>{{ $commande->adresse->adresse }}</p>
            <p>{{ $commande->adresse->ville }}</p>
            <p>{{ $commande->adresse->telephone }}</p>
        </div>
    </div>
    @endif

    {{-- Actions --}}
    <div class="actions mt-4">
        <a href="{{ route('accueil') }}" class="btn btn-outline">
            Retour à l'accueil
        </a>
    </div>

</div>

@endsection