@extends('layouts.app')

@section('title', 'Reçu de commande — ShopCI')

@section('content')
<div class="container section">

    {{-- Boutons d'action (masqués à l'impression) --}}
    <div class="recu-actions no-print">
        <a href="{{ route('catalogue') }}" class="btn btn-outline">
            <i class="fa-solid fa-arrow-left"></i>
            Continuer mes achats
        </a>
        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="fa-solid fa-print"></i>
            Imprimer le reçu
        </button>
    </div>

    {{-- Reçu --}}
    <div class="recu" id="recu">

        {{-- En-tête --}}
        <div class="recu-header">
            <div class="recu-brand">
                <div class="recu-logo">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div>
                    <h1>ShopCI</h1>
                    <p>Votre boutique en ligne en Côte d'Ivoire</p>
                </div>
            </div>

            <div class="recu-meta">
                <div class="recu-type">REÇU DE COMMANDE</div>
                <div class="recu-numero">{{ $commande->reference_unique }}</div>
                <div class="recu-date">
                    {{ $commande->date_commande->format('d/m/Y à H:i') }}
                </div>
            </div>
        </div>

        {{-- Statuts --}}
        <div class="recu-statuts">
            @php
            $badgeCommande = match($commande->statut) {
            'en_attente' => 'warning',
            'confirmee' => 'info',
            'en_preparation' => 'info',
            'expediee' => 'info',
            'livree' => 'success',
            'annulee' => 'danger',
            default => 'info',
            };
            $badgePaiement = $commande->statut_paiement === 'paye' ? 'success' : 'warning';
            @endphp

            <div class="recu-statut">
                <span class="recu-statut-label">Statut commande</span>
                <span class="badge badge-{{ $badgeCommande }}">
                    {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
                </span>
            </div>

            <div class="recu-statut">
                <span class="recu-statut-label">Statut paiement</span>
                <span class="badge badge-{{ $badgePaiement }}">
                    {{ ucfirst(str_replace('_', ' ', $commande->statut_paiement ?? 'en_attente')) }}
                </span>
            </div>

            <div class="recu-statut">
                <span class="recu-statut-label">Mode de paiement</span>
                <strong>{{ $commande->mode_paiement_label ?? 'Paiement à la livraison' }}</strong>
            </div>
        </div>

        {{-- Client + Livraison --}}
        <div class="recu-parties">
            <div class="recu-partie">
                <h3>Client</h3>
                <p><strong>{{ $commande->client->nom }}</strong></p>
                @if($commande->client->telephone)
                <p><i class="fa-solid fa-phone"></i> {{ $commande->client->telephone }}</p>
                @endif
                @if($commande->client->email)
                <p><i class="fa-solid fa-envelope"></i> {{ $commande->client->email }}</p>
                @endif
            </div>

            <div class="recu-partie">
                <h3>Adresse de livraison</h3>
                <p><i class="fa-solid fa-location-dot"></i> {{ $commande->adresse->adresse }}</p>
                <p><i class="fa-solid fa-city"></i> {{ $commande->adresse->ville }}</p>
                @if($commande->adresse->telephone)
                <p><i class="fa-solid fa-phone"></i> {{ $commande->adresse->telephone }}</p>
                @endif
            </div>
        </div>

        {{-- Articles --}}
        <div class="recu-articles">
            <table class="recu-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Image</th>
                        <th>Produit</th>
                        <th class="text-center">Qté</th>
                        <th class="text-right">Prix unitaire</th>
                        <th class="text-right">Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commande->lignes as $ligne)
                    <tr>
                        {{-- Image --}}
                        <td data-label="Image">
                            <div class="recu-product-image">
                                @if($ligne->produit && $ligne->produit->imagePrincipale)
                                <img src="{{ $ligne->produit->imagePrincipale->chemin }}"
                                    alt="{{ $ligne->nom_produit }}">
                                @else
                                <div class="recu-product-image-placeholder">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                                @endif
                            </div>
                        </td>

                        {{-- Nom + options --}}
                        <td data-label="Produit">
                            <strong class="recu-product-name">{{ $ligne->nom_produit }}</strong>

                            @if(!empty($ligne->options_choisies))
                            <div class="recu-product-options">
                                @foreach($ligne->options_choisies as $nom => $valeur)
                                <span class="recu-option">{{ $nom }} : {{ $valeur }}</span>
                                @endforeach
                            </div>
                            @endif
                        </td>

                        {{-- Quantité --}}
                        <td data-label="Qté" class="text-center">
                            <strong>× {{ $ligne->quantite }}</strong>
                        </td>

                        {{-- Prix unitaire --}}
                        <td data-label="Prix unitaire" class="text-right">
                            {{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA
                        </td>

                        {{-- Sous-total --}}
                        <td data-label="Sous-total" class="text-right">
                            <strong>{{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA</strong>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totaux --}}
        <div class="recu-totaux">
            <div class="recu-totaux-content">
                <div class="recu-total-line">
                    <span>Sous-total</span>
                    <strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong>
                </div>

                @if($commande->montant_reduction > 0)
                <div class="recu-total-line">
                    <span>Réduction</span>
                    <strong style="color: var(--danger);">
                        − {{ number_format($commande->montant_reduction, 0, ',', ' ') }} FCFA
                    </strong>
                </div>
                @endif

                <div class="recu-total-line">
                    <span>Livraison</span>
                    <strong>À calculer</strong>
                </div>

                <div class="recu-total-final">
                    <span>TOTAL</span>
                    <strong>{{ number_format($commande->montant_total - $commande->montant_reduction, 0, ',', ' ') }}
                        FCFA</strong>
                </div>
            </div>
        </div>

        {{-- Info paiement --}}
        <div class="recu-info-paiement">
            @if($commande->statut_paiement === 'paye')
            <div class="recu-paiement-box recu-paiement-paye">
                <i class="fa-solid fa-circle-check"></i>
                <div>
                    <strong>Commande payée</strong>
                    @if($commande->date_paiement)
                    <p>Le {{ $commande->date_paiement->format('d/m/Y à H:i') }}</p>
                    @endif
                </div>
            </div>
            @else
            <div class="recu-paiement-box recu-paiement-attente">
                <i class="fa-solid fa-hourglass-half"></i>
                <div>
                    <strong>Paiement à la livraison</strong>
                    <p>Vous paierez en espèces au moment de la réception.</p>
                </div>
            </div>
            @endif
        </div>

        {{-- Contact --}}
        <div class="recu-contact">
            <p>
                <i class="fa-solid fa-phone"></i> <strong>+225 07 00 00 00 00</strong>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <i class="fa-solid fa-envelope"></i> <strong>contact@shopci.ci</strong>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <i class="fa-solid fa-location-dot"></i> <strong>Abidjan, Côte d'Ivoire</strong>
            </p>
        </div>

        {{-- Pied de page --}}
        <div class="recu-footer">
            <p>Merci pour votre confiance </p>
            <p>Conservez ce reçu. Il vous sera demandé pour tout suivi ou réclamation.</p>
            <p class="recu-footer-small">
                ShopCI — Boutique en ligne en Côte d'Ivoire
            </p>
        </div>

    </div>

</div>
@endsection