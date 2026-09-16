@extends('layouts.app')

@section('title', 'À propos — ShopCI')

@section('content')
<div class="container section">

    <div class="breadcrumb">
        <a href="{{ route('accueil') }}">Accueil</a>
        <span>›</span>
        <span>À propos</span>
    </div>

    <div class="page-content-wrapper">
        <h1 class="page-title">À propos de ShopCI</h1>

        <div class="page-content">
            <h2>Qui sommes-nous ?</h2>
            <p>
                <strong>ShopCI</strong> est une boutique en ligne ivoirienne spécialisée dans la vente
                de produits de qualité, livrés partout en Côte d'Ivoire.
            </p>

            <h2>Notre mission</h2>
            <p>
                Offrir à tous les Ivoiriens un accès facile à des produits de qualité,
                avec un service client irréprochable et une livraison rapide.
            </p>

            <h2>Nos valeurs</h2>
            <ul>
                <li><strong>Qualité</strong> — Nous sélectionnons rigoureusement chaque produit</li>
                <li><strong>Confiance</strong> — Paiement à la livraison pour vous rassurer</li>
                <li><strong>Proximité</strong> — Un service client à votre écoute</li>
                <li><strong>Rapidité</strong> — Livraison en 2 à 5 jours ouvrés</li>
            </ul>

            <h2>Nous contacter</h2>
            <p>
                📞 Téléphone : <strong>+225 07 00 00 00 00</strong><br>
                ✉️ Email : <strong>contact@shopci.ci</strong><br>
                📍 Adresse : Abidjan, Côte d'Ivoire
            </p>
        </div>
    </div>

</div>
@endsection