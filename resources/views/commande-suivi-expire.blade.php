@extends('layouts.app')

@section('title', 'Lien expiré — ShopCI')

@section('content')
<div class="container section">
    <div class="suivi-box">
        <div style="font-size: 50px; margin-bottom: 15px;">⏰</div>
        <h1>Lien expiré</h1>
        <p class="suivi-info">
            Ce lien de suivi a expiré le
            <strong>{{ $suivi->expires_at->format('d/m/Y') }}</strong>.
            Contactez-nous pour obtenir un nouveau lien.
        </p>
        <a href="{{ route('accueil') }}" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>
@endsection