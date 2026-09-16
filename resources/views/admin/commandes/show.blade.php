@extends('layouts.admin')

@section('title', 'Commande ' . $commande->reference_unique)
@section('page-title', 'Détail de la commande')

@section('page-actions')
<a href="{{ route('admin.commandes.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i>
    Retour
</a>
@endsection

@section('content')

{{-- En-tête commande --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-receipt"></i>
            {{ $commande->reference_unique }}
        </h2>
        <div>
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
            <span class="badge badge-{{ $badgeClass }}" style="font-size: 13px; padding: 6px 14px;">
                {{ ucfirst(str_replace('_', ' ', $commande->statut)) }}
            </span>
        </div>
    </div>

    <div class="commande-meta">
        <div class="meta-item">
            <i class="fa-solid fa-calendar"></i>
            <div>
                <small>Date</small>
                <strong>{{ $commande->date_commande->format('d/m/Y à H:i') }}</strong>
            </div>
        </div>

        <div class="meta-item">
            <i class="fa-solid fa-coins"></i>
            <div>
                <small>Montant total</small>
                <strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong>
            </div>
        </div>

        <div class="meta-item">
            <i class="fa-solid fa-credit-card"></i>
            <div>
                <small>Mode de paiement</small>
                <strong>{{ $commande->mode_paiement_label }}</strong>
            </div>
        </div>

        <div class="meta-item">
            <i class="fa-solid fa-{{ $commande->statut_paiement === 'paye' ? 'circle-check' : 'hourglass-half' }}"></i>
            <div>
                <small>Statut paiement</small>
                <strong>
                    <span class="badge badge-{{ $commande->statut_paiement === 'paye' ? 'success' : 'warning' }}">
                        {{ ucfirst(str_replace('_', ' ', $commande->statut_paiement ?? 'en_attente')) }}
                    </span>
                </strong>
            </div>
        </div>

        @if($commande->date_paiement)
        <div class="meta-item">
            <i class="fa-solid fa-calendar-check"></i>
            <div>
                <small>Payée le</small>
                <strong>{{ $commande->date_paiement->format('d/m/Y à H:i') }}</strong>
            </div>
        </div>
        @endif

        <div class="meta-item">
            <i class="fa-solid fa-user-tag"></i>
            <div>
                <small>Mode</small>
                <strong>{{ $commande->mode === 'guest' ? 'Invité' : 'Compte' }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="commande-grid">

    {{-- Client --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2>
                <i class="fa-solid fa-user"></i>
                Client
            </h2>
        </div>

        <div class="info-list">
            <div class="info-line">
                <span>Nom</span>
                <strong>{{ $commande->client->nom }}</strong>
            </div>
            <div class="info-line">
                <span>Téléphone</span>
                <strong>
                    <a href="tel:{{ $commande->client->telephone }}">
                        {{ $commande->client->telephone }}
                    </a>
                </strong>
            </div>
            @if($commande->client->email)
            <div class="info-line">
                <span>Email</span>
                <strong>{{ $commande->client->email }}</strong>
            </div>
            @endif
        </div>

        @if($commande->adresse)
        <div class="admin-card-header" style="border-top: 1px solid var(--border); border-bottom: none;">
            <h2>
                <i class="fa-solid fa-location-dot"></i>
                Livraison
            </h2>
        </div>

        <div class="info-list">
            <div class="info-line">
                <span>Adresse</span>
                <strong>{{ $commande->adresse->adresse }}</strong>
            </div>
            <div class="info-line">
                <span>Ville</span>
                <strong>{{ $commande->adresse->ville }}</strong>
            </div>
        </div>
        @endif
    </div>

    {{-- Changer le statut --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h2>
                <i class="fa-solid fa-pen-to-square"></i>
                Actions
            </h2>
        </div>

        <div class="admin-card-body">
            {{-- Bouton marquer comme payé (si pas encore payé) --}}
            @if($commande->statut_paiement !== 'paye')
            <form method="POST" action="{{ route('admin.commandes.marquer-paye', $commande->id) }}"
                data-confirm="Confirmer que le paiement a bien été reçu ?" data-confirm-title="Marquer comme payé"
                style="margin-bottom: 20px;">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa-solid fa-money-bill-wave"></i>
                    Marquer comme payé
                </button>
            </form>
            @endif

            {{-- Changement de statut --}}
            <form method="POST" action="{{ route('admin.commandes.statut', $commande->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="statut">Changer le statut</label>
                    <select name="statut" id="statut" class="form-control">
                        <option value="en_attente" {{ $commande->statut === 'en_attente' ? 'selected' : '' }}>En attente
                        </option>
                        <option value="confirmee" {{ $commande->statut === 'confirmee' ? 'selected' : '' }}>Confirmée
                        </option>
                        <option value="en_preparation" {{ $commande->statut === 'en_preparation' ? 'selected' : '' }}>En
                            préparation</option>
                        <option value="expediee" {{ $commande->statut === 'expediee' ? 'selected' : '' }}>Expédiée
                        </option>
                        <option value="livree" {{ $commande->statut === 'livree' ? 'selected' : '' }}>Livrée</option>
                        <option value="annulee" {{ $commande->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa-solid fa-check"></i>
                    Enregistrer le statut
                </button>
            </form>

            <hr style="margin: 20px 0; border: none; border-top: 1px solid var(--border);">

            <form method="POST" action="{{ route('admin.commandes.destroy', $commande->id) }}"
                data-confirm="Voulez-vous vraiment supprimer la commande {{ $commande->reference_unique }} ? Cette action est irréversible."
                data-confirm-title="Supprimer la commande">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-block">
                    <i class="fa-solid fa-trash"></i> Supprimer la commande
                </button>
            </form>
        </div>
    </div>

</div>

{{-- Articles --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-boxes-stacked"></i>
            Articles commandés ({{ $commande->lignes->count() }})
        </h2>
    </div>

    <div class="articles-list">
        @foreach($commande->lignes as $ligne)
        <div class="article-item">

            <div class="article-image">
                @if($ligne->produit && $ligne->produit->imagePrincipale)
                <img src="{{ $ligne->produit->imagePrincipale->chemin }}" alt="{{ $ligne->nom_produit }}">
                @else
                <div class="article-image-placeholder">
                    <i class="fa-solid fa-image"></i>
                </div>
                @endif
            </div>

            <div class="article-info">
                <strong class="article-name">{{ $ligne->nom_produit }}</strong>

                @if(!empty($ligne->options_choisies))
                <div class="article-options">
                    @foreach($ligne->options_choisies as $nom => $valeur)
                    <span class="badge badge-info">
                        {{ $nom }} : {{ $valeur }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="article-price">
                <small>Prix unitaire</small>
                <strong>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</strong>
            </div>

            <div class="article-qty">
                <small>Qté</small>
                <strong>× {{ $ligne->quantite }}</strong>
            </div>

            <div class="article-subtotal">
                <small>Sous-total</small>
                <strong>{{ number_format($ligne->sous_total, 0, ',', ' ') }} FCFA</strong>
            </div>

        </div>
        @endforeach
    </div>

    <div class="articles-total">
        <span>Total de la commande</span>
        <strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong>
    </div>
</div>

{{-- Suivi / liens --}}
@if($commande->suivis->count() > 0)
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-link"></i>
            Liens de suivi envoyés
        </h2>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Canal</th>
                    <th>Expire le</th>
                    <th>Tentatives</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->suivis as $suivi)
                <tr>
                    <td data-label="Code"><strong>{{ $suivi->code }}</strong></td>
                    <td data-label="Canal">
                        <span class="badge badge-info">{{ ucfirst($suivi->canal) }}</span>
                    </td>
                    <td data-label="Expire le">{{ $suivi->expires_at->format('d/m/Y H:i') }}</td>
                    <td data-label="Tentatives">{{ $suivi->tentatives }} / 5</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection