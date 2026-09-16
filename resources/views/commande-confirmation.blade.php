@extends('layouts.app')

@section('title', 'Commande confirmée — ShopCI')

@section('content')
<div class="container section">
    <div class="confirmation-box">
        <div class="confirmation-icon">✓</div>

        <h1>Merci pour votre commande !</h1>

        <p class="confirmation-message">
            Votre commande a bien été enregistrée. Vous recevrez un message WhatsApp
            avec votre code de suivi et le lien.
        </p>

        <div class="confirmation-details">
            <div class="detail-line">
                <span>Référence</span>
                <strong>{{ $commande->reference_unique }}</strong>
            </div>
            <div class="detail-line">
                <span>Total</span>
                <strong>{{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA</strong>
            </div>

            @if($dernierSuivi)
            <div class="detail-line">
                <span>Code de suivi</span>
                <strong style="font-family: monospace; font-size: 20px; letter-spacing: 4px; color: #32B394;">
                    {{ $dernierSuivi->code }}
                </strong>
            </div>
            @endif
        </div>

        @if($dernierSuivi)
        <div class="confirmation-info">
            <p>⚠️ <strong>Conservez ce code.</strong> Il vous sera demandé pour suivre votre commande.</p>
        </div>
        @endif

        <div class="confirmation-actions">
            @if($dernierSuivi)
            <a href="{{ route('commande.suivi.token', $dernierSuivi->token) }}" class="btn btn-primary">
                Suivre ma commande
            </a>
            @endif
            <a href="{{ route('accueil') }}" class="btn btn-outline">
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection