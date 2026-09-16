@extends('layouts.app')

@section('title', 'FAQ — ShopCI')

@section('content')
<div class="container section">

    <div class="breadcrumb">
        <a href="{{ route('accueil') }}">Accueil</a>
        <span>›</span>
        <span>FAQ</span>
    </div>

    <div class="page-content-wrapper">
        <h1 class="page-title">Questions fréquentes</h1>

        <div class="page-content">

            <h2>🛒 Comment passer une commande ?</h2>
            <p>
                Ajoutez les produits de votre choix au panier, puis cliquez sur
                <strong>« Passer la commande »</strong>. Remplissez le formulaire
                avec vos coordonnées et validez. Vous recevrez un message WhatsApp
                de confirmation avec un code de suivi.
            </p>

            <h2>💳 Quels sont les modes de paiement disponibles ?</h2>
            <p>
                Le <strong>paiement à la livraison</strong> est disponible partout
                en Côte d'Ivoire. Vous payez en espèces au moment où vous recevez
                votre commande.
            </p>
            <p>
                D'autres modes (Wave, Orange Money, MTN MoMo) seront disponibles
                prochainement.
            </p>

            <h2>🚚 Quels sont les délais de livraison ?</h2>
            <p>
                Entre <strong>2 et 5 jours ouvrés</strong> selon votre localisation.
                Abidjan est généralement livré sous 48h.
            </p>

            <h2>📦 Comment suivre ma commande ?</h2>
            <p>
                Après validation, vous recevez un message WhatsApp contenant :
            </p>
            <ul>
                <li>Un <strong>lien de suivi</strong></li>
                <li>Un <strong>code à 6 chiffres</strong></li>
            </ul>
            <p>
                Cliquez sur le lien, saisissez votre code, et vous verrez le
                détail de votre commande.
            </p>

            <h2>↩️ Puis-je retourner un produit ?</h2>
            <p>
                Oui, sous <strong>7 jours</strong> après réception, à condition que
                le produit soit dans son état d'origine et non utilisé.
                Contactez-nous pour organiser le retour.
            </p>

            <h2>📞 Comment vous contacter ?</h2>
            <p>
                <strong>Téléphone</strong> : +225 07 00 00 00 00<br>
                <strong>WhatsApp</strong> : +225 07 00 00 00 00<br>
                <strong>Email</strong> : contact@shopci.ci
            </p>

        </div>
    </div>

</div>
@endsection