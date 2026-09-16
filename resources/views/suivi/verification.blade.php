@extends('layouts.app')

@section('title', 'Suivi de commande — ShopCI')

@section('content')

<div class="container section">

    <div class="suivi-box">

        <h1>Suivi de commande</h1>

        <p class="suivi-ref">
            Commande : <strong>{{ $commande->reference_unique }}</strong>
        </p>

        <p class="suivi-info">
            Un code de vérification à 6 chiffres vous a été envoyé par SMS au
            <strong>{{ $commande->client->telephone }}</strong>.
        </p>

        <form method="POST" action="{{ route('suivi.verifier', $commande->reference_unique) }}">
            @csrf

            <div class="form-group">
                <label for="code">Code de vérification</label>
                <input type="text" name="code" id="code" class="form-control code-input" maxlength="6"
                    pattern="[0-9]{6}" placeholder="482916" autofocus required>
                @error('code')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-lg btn-block">
                Vérifier
            </button>

        </form>

        <hr>

        <form method="POST" action="{{ route('suivi.renvoyer', $commande->reference_unique) }}">
            @csrf
            <button type="submit" class="btn-link">
                Je n'ai pas reçu le code
            </button>
        </form>

    </div>

</div>

@endsection