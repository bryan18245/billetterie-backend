@extends('layouts.app')

@section('title', 'Conditions Générales de Vente — ShopCI')

@section('content')
<div class="container section">

    <div class="breadcrumb">
        <a href="{{ route('accueil') }}">Accueil</a>
        <span>›</span>
        <span>Conditions Générales de Vente</span>
    </div>

    <div class="page-content-wrapper">
        <h1 class="page-title">Conditions Générales de Vente</h1>

        <div class="page-content">
            <h2>Article 1 — Objet</h2>
            <p>
                Les présentes Conditions Générales de Vente (CGV) régissent les relations
                contractuelles entre <strong>ShopCI</strong> et ses clients, dans le cadre
                de la vente de produits en ligne.
            </p>

            <h2>Article 2 — Commandes</h2>
            <p>
                Toute commande passée sur le site implique l'acceptation sans réserve
                des présentes Conditions Générales de Vente.
            </p>
            <p>
                Le client s'engage à fournir des informations exactes et à jour
                (nom, téléphone, adresse) pour la bonne exécution de la livraison.
            </p>

            <h2>Article 3 — Prix</h2>
            <p>
                Les prix sont indiqués en francs CFA (FCFA), toutes taxes comprises.
                ShopCI se réserve le droit de modifier ses prix à tout moment, étant
                entendu que le prix applicable est celui en vigueur au moment de la commande.
            </p>

            <h2>Article 4 — Paiement</h2>
            <p>
                Le paiement s'effectue <strong>à la livraison</strong>, en espèces,
                au moment de la réception de la commande par le client.
            </p>
            <p>
                D'autres modes de paiement (Wave, Orange Money, MTN MoMo) seront
                disponibles prochainement.
            </p>

            <h2>Article 5 — Livraison</h2>
            <p>
                Les livraisons sont effectuées partout en Côte d'Ivoire, dans un
                délai de <strong>2 à 5 jours ouvrés</strong> selon la localisation.
            </p>
            <p>
                ShopCI ne saurait être tenu responsable des retards de livraison
                causés par des circonstances indépendantes de sa volonté.
            </p>

            <h2>Article 6 — Retours et remboursements</h2>
            <p>
                Le client dispose de <strong>7 jours</strong> à compter de la réception
                pour signaler tout problème et demander un retour, sous réserve que le
                produit soit dans son état d'origine.
            </p>

            <h2>Article 7 — Responsabilité</h2>
            <p>
                ShopCI ne saurait être tenu responsable des dommages indirects résultant
                de l'utilisation du site ou des produits vendus.
            </p>

            <h2>Article 8 — Droit applicable</h2>
            <p>
                Les présentes CGV sont soumises au droit ivoirien. Tout litige sera
                soumis à la compétence exclusive des tribunaux d'Abidjan.
            </p>

            <p style="margin-top: 30px;">
                <strong>Dernière mise à jour :</strong> {{ now()->format('d/m/Y') }}
            </p>
        </div>
    </div>

</div>
@endsection