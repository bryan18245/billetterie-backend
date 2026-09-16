@extends('layouts.app')

@section('title', 'Retours et remboursements — ShopCI')

@section('content')
<div class="container section">

    <div class="breadcrumb">
        <a href="{{ route('accueil') }}">Accueil</a>
        <span>›</span>
        <span>Retours</span>
    </div>

    <div class="page-content-wrapper">
        <h1 class="page-title">Retours et remboursements</h1>

        <div class="page-content">
            <h2>Conditions de retour</h2>
            <p>
                Vous disposez de <strong>7 jours</strong> à compter de la réception
                de votre commande pour demander un retour, dans les cas suivants :
            </p>
            <ul>
                <li>Produit non conforme à la commande</li>
                <li>Produit défectueux</li>
                <li>Produit endommagé pendant le transport</li>
            </ul>
            <p>
                Le produit doit être dans son <strong>état d'origine</strong>,
                non utilisé et dans son emballage d'origine.
            </p>

            <h2>Procédure de retour</h2>
            <ol>
                <li>
                    Contactez-nous au <strong>+225 07 00 00 00 00</strong>
                    (appel ou WhatsApp) dans les 7 jours suivant la réception.
                </li>
                <li>
                    Indiquez votre <strong>référence de commande</strong> et
                    la raison du retour.
                </li>
                <li>
                    Nous organiserons la récupération du produit à votre adresse.
                </li>
            </ol>

            <h2>Remboursement</h2>
            <p>
                Une fois le retour reçu et vérifié, le remboursement est
                effectué dans un délai de <strong>5 à 10 jours ouvrés</strong>.
            </p>
            <p>
                Le remboursement s'effectue par le même mode que le paiement initial
                (espèces ou Mobile Money).
            </p>

            <h2>Produits non remboursables</h2>
            <p>
                Certains produits ne peuvent pas être remboursés :
            </p>
            <ul>
                <li>Produits alimentaires ouverts</li>
                <li>Produits d'hygiène et cosmétiques utilisés</li>
                <li>Produits personnalisés</li>
            </ul>

            <p style="margin-top: 30px;">
                <strong>Dernière mise à jour :</strong> {{ now()->format('d/m/Y') }}
            </p>
        </div>
    </div>

</div>
@endsection