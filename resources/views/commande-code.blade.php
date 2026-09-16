@extends('layouts.app')

@section('title', 'Code de suivi — ShopCI')

@section('content')
<div class="container section">
    <div class="suivi-box">

        <div style="font-size: 50px; margin-bottom: 15px;">🔐</div>

        <h1>Accès protégé</h1>

        <p class="suivi-info">
            Saisissez le code à <strong>6 chiffres</strong> reçu par WhatsApp
            pour consulter la commande <strong>{{ $suivi->commande->reference_unique }}</strong>.
        </p>

        @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('commande.suivi.verifier', $token) }}">
            @csrf

            <div class="form-group">
                <input type="text" name="code" class="form-control code-input" maxlength="6" inputmode="numeric"
                    pattern="[0-9]{6}" placeholder="••••••" autocomplete="off" autofocus required>
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block">
                Vérifier le code
            </button>
        </form>

        <form method="POST" action="{{ route('commande.suivi.renvoyer', $token) }}" style="margin-top: 15px;">
            @csrf
            <button type="submit" class="btn-link">
                Je n'ai pas reçu le code
            </button>
        </form>

        <p class="text-small text-muted mt-3">
            Conservez ce code : il vous permet de suivre votre commande
            jusqu'au {{ $suivi->expires_at->format('d/m/Y') }}.
        </p>

    </div>
</div>
@endsection