@extends('layouts.app')

@section('title', 'Politique de confidentialité — ShopCI')

@section('content')
<div class="container section">

    <div class="breadcrumb">
        <a href="{{ route('accueil') }}">Accueil</a>
        <span>›</span>
        <span>Politique de confidentialité</span>
    </div>

    <div class="page-content-wrapper">
        <h1 class="page-title">Politique de confidentialité</h1>

        <div class="page-content">
            <h2>1. Données collectées</h2>
            <p>
                Nous collectons uniquement les données nécessaires au traitement
                de vos commandes :
            </p>
            <ul>
                <li>Nom et prénom</li>
                <li>Numéro de téléphone</li>
                <li>Adresse de livraison</li>
                <li>Adresse email (optionnelle)</li>
            </ul>

            <h2>2. Utilisation des données</h2>
            <p>
                Vos données sont utilisées exclusivement pour :
            </p>
            <ul>
                <li>Traiter et livrer vos commandes</li>
                <li>Vous contacter concernant votre commande</li>
                <li>Améliorer nos services</li>
            </ul>

            <h2>3. Partage des données</h2>
            <p>
                Vos données ne sont <strong>jamais vendues</strong> à des tiers.
                Elles peuvent être partagées uniquement avec :
            </p>
            <ul>
                <li>Notre livreur pour assurer la livraison</li>
                <li>Les autorités si la loi l'exige</li>
            </ul>

            <h2>4. Conservation des données</h2>
            <p>
                Vos données sont conservées pendant la durée nécessaire au traitement
                de votre commande et pour une durée maximale de 3 ans.
            </p>

            <h2>5. Vos droits</h2>
            <p>
                Conformément à la réglementation ivoirienne sur la protection des
                données personnelles, vous disposez des droits suivants :
            </p>
            <ul>
                <li>Droit d'accès à vos données</li>
                <li>Droit de rectification</li>
                <li>Droit à l'effacement</li>
                <li>Droit d'opposition au traitement</li>
            </ul>
            <p>
                Pour exercer ces droits, contactez-nous à
                <strong>contact@shopci.ci</strong>.
            </p>

            <h2>6. Cookies</h2>
            <p>
                Notre site utilise des cookies techniques indispensables au
                fonctionnement du panier et à la session utilisateur.
            </p>

            <h2>7. Sécurité</h2>
            <p>
                Nous mettons en œuvre les mesures techniques et organisationnelles
                appropriées pour protéger vos données contre tout accès non autorisé.
            </p>

            <p style="margin-top: 30px;">
                <strong>Dernière mise à jour :</strong> {{ now()->format('d/m/Y') }}
            </p>
        </div>
    </div>

</div>
@endsection