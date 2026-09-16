@extends('layouts.app')

@section('title', 'Livraison — ShopCI')

@section('content')
<div class="container section">

    <div class="breadcrumb">
        <a href="{{ route('accueil') }}">Accueil</a>
        <span>›</span>
        <span>Livraison</span>
    </div>

    <div class="page-content-wrapper">
        <h1 class="page-title">Livraison</h1>

        <div class="page-content">
            <h2>Zones de livraison</h2>
            <p>
                Nous livrons <strong>partout en Côte d'Ivoire</strong> :
                Abidjan, Yamoussoukro, Bouaké, Daloa, San-Pédro, Korhogo,
                Man, Gagnoa et toutes les autres villes du pays.
            </p>

            <h2>Délais de livraison</h2>
            <ul>
                <li><strong>Abidjan et banlieue</strong> : 24 à 48 heures</li>
                <li><strong>Grandes villes</strong> : 2 à 3 jours ouvrés</li>
                <li><strong>Zones rurales</strong> : 3 à 5 jours ouvrés</li>
            </ul>

            <h2>Frais de livraison</h2>
            <p>
                Les frais de livraison sont calculés selon votre ville et
                communiqués avant la validation de la commande.
            </p>

            <h2>Suivi de commande</h2>
            <p>
                Dès la validation de votre commande, vous recevez un message
                WhatsApp avec un <strong>lien de suivi</strong> et un
                <strong>code à 6 chiffres</strong> pour consulter l'état
                de votre livraison à tout moment.
            </p>

            <h2>Réception du colis</h2>
            <p>
                Lors de la réception, vérifiez que le colis est bien fermé et
                conforme à votre commande avant de payer le livreur.
            </p>
        </div>
    </div>

</div>
@endsection