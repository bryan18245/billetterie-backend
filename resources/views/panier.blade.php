@extends('layouts.app')

@section('title', 'Panier — ShopCI')

@section('content')

<div class="container section">

    <h1 class="page-title">Mon panier</h1>

    @if(count($panier) > 0)

    <div class="panier-layout">

        {{-- Liste des produits --}}
        <div class="panier-items">

            @foreach($panier as $index => $item)
            <div class="panier-item">

                {{-- Image --}}
                <div class="panier-item-image">
                    @if($item['image'])
                    <img src="{{ $item['image'] }}" alt="{{ $item['nom'] }}">
                    @else
                    <div class="placeholder">
                        <i class="fa-solid fa-image"></i>
                    </div>
                    @endif
                </div>

                {{-- Infos --}}
                <div class="panier-item-info">
                    <h3>{{ $item['nom'] }}</h3>

                    @if(!empty($item['options']))
                    <p class="panier-item-options">
                        @foreach($item['options'] as $nom => $valeur)
                        <span>
                            <i class="fa-solid fa-tag"></i>
                            {{ $nom }} : {{ $valeur }}
                        </span>
                        @endforeach
                    </p>
                    @endif

                    <p class="panier-item-prix">
                        <i class="fa-solid fa-coins"></i>
                        {{ number_format($item['prix'], 0, ',', ' ') }} FCFA
                    </p>

                    {{-- Stock faible --}}
                    @if(isset($item['stock_max']) && $item['stock_max'] > 0 && $item['stock_max'] <= 5) <p
                        class="stock-limite" style="font-size: 12px; margin-top: 4px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Plus que {{ $item['stock_max'] }} en stock
                        </p>
                        @endif

                        {{-- Stock épuisé --}}
                        @if(isset($item['stock_max']) && $item['stock_max'] <= 0) <p class="stock-epuise"
                            style="font-size: 12px; margin-top: 4px;">
                            <i class="fa-solid fa-circle-xmark"></i>
                            Épuisé
                            </p>
                            @endif
                </div>

                {{-- Quantité --}}
                <div class="panier-item-quantite">
                    <form method="POST" action="{{ route('panier.modifier', $index) }}">
                        @csrf
                        @method('PUT')
                        <div class="quantite-control">
                            <button type="button" class="quantite-btn" onclick="changerQuantite(-1, this, this.form)"
                                title="Diminuer">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input type="number" name="quantite" value="{{ $item['quantite'] }}" min="1"
                                max="{{ $item['stock_max'] ?? 99 }}">
                            <button type="button" class="quantite-btn" onclick="changerQuantite(1, this, this.form)"
                                title="Augmenter">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Sous-total --}}
                <div class="panier-item-sous-total">
                    {{ number_format($item['prix'] * $item['quantite'], 0, ',', ' ') }} FCFA
                </div>

                {{-- Supprimer --}}
                <div class="panier-item-actions">
                    <form method="POST" action="{{ route('panier.supprimer', $index) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-icon-danger" title="Supprimer">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>

            </div>
            @endforeach

            {{-- Vider le panier --}}
            <div class="panier-actions">
                <form method="POST" action="{{ route('panier.vider') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline">
                        <i class="fa-solid fa-trash-can"></i>
                        Vider le panier
                    </button>
                </form>
            </div>

        </div>

        {{-- Récapitulatif --}}
        <div class="panier-recap">
            <h3>
                <i class="fa-solid fa-receipt"></i>
                Récapitulatif
            </h3>

            <div class="recap-line">
                <span>
                    <i class="fa-solid fa-cart-shopping"></i>
                    Sous-total
                </span>
                <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
            </div>

            <div class="recap-line">
                <span>
                    <i class="fa-solid fa-truck-fast"></i>
                    Livraison
                </span>
                <span>À calculer</span>
            </div>

            <hr>

            <div class="recap-line recap-total">
                <span>Total</span>
                <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
            </div>

            <a href="{{ route('commande.index') }}" class="btn btn-primary btn-lg btn-block mt-3">
                <i class="fa-solid fa-credit-card"></i>
                Passer la commande
            </a>

            <a href="{{ route('catalogue') }}" class="btn btn-outline btn-block mt-2">
                <i class="fa-solid fa-arrow-left"></i>
                Continuer mes achats
            </a>
        </div>

    </div>

    @else

    {{-- Panier vide --}}
    <div class="empty-state">
        <div class="empty-state-icon">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <p>Votre panier est vide.</p>
        <a href="{{ route('catalogue') }}" class="btn btn-primary mt-3">
            <i class="fa-solid fa-bag-shopping"></i>
            Découvrir les produits
        </a>
    </div>

    @endif

</div>

@endsection