@extends('layouts.app')

@section('title', 'Mentions légales — ShopCI')

@section('content')
<div class="container section">

    <div class="breadcrumb">
        <a href="{{ route('accueil') }}">Accueil</a>
        <span>›</span>
        <span>Mentions légales</span>
    </div>

    <div class="page-content-wrapper">
        <h1 class="page-title">Mentions légales</h1>

        <div class="page-content">
            <h2>Éditeur du site</h2>
            <p>
                <strong>ShopCI</strong><br>
                Abidjan, Côte d'Ivoire<br>
                Téléphone : +225 07 00 00 00 00<br>
                Email : contact@shopci.ci
            </p>

            <h2>Responsable de publication</h2>
            <p>
                Le responsable de la publication du site est le gérant de ShopCI.
            </p>

            <h2>Hébergement</h2>
            <p>
                Ce site est hébergé par :<br>
                <strong>[Nom de l'hébergeur]</strong><br>
                [Adresse de l'hébergeur]
            </p>

            <h2>Propriété intellectuelle</h2>
            <p>
                L'ensemble des contenus présents sur le site ShopCI (textes, images,
                logos, vidéos) est la propriété exclusive de ShopCI, sauf mention contraire.
            </p>
            <p>
                Toute reproduction, même partielle, est interdite sans autorisation
                écrite préalable.
            </p>

            <h2>Données personnelles</h2>
            <p>
                Les informations collectées sur le site font l'objet d'un traitement
                informatique destiné à la gestion des commandes.
                Pour plus d'informations, consultez notre
                <a href="{{ route('pages.show', 'politique-confidentialite') }}">
                    Politique de confidentialité
                </a>.
            </p>

            <h2>Cookies</h2>
            <p>
                Le site ShopCI utilise des cookies pour assurer le bon fonctionnement
                du panier et améliorer l'expérience utilisateur.
            </p>

            <h2>Litiges</h2>
            <p>
                Tout litige relatif à l'utilisation du site ShopCI est soumis
                au droit ivoirien et à la compétence des tribunaux d'Abidjan.
            </p>
        </div>
    </div>

</div>
@endsection