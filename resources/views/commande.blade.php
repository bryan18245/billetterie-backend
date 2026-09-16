@extends('layouts.app')

@section('title', 'Commande — ShopCI')

@section('content')

<div class="container section">

    <h1 class="page-title">Finaliser ma commande</h1>

    <form method="POST" action="{{ route('commande.store') }}" id="commandeForm">
        @csrf

        <div class="commande-layout">

            {{-- Informations client --}}
            <div class="commande-form">

                <h3>Informations de livraison</h3>

                <div class="form-group">
                    <label for="nom">Nom complet *</label>
                    <input type="text" name="nom" id="nom" class="form-control"
                        value="{{ old('nom', auth()->user()->name ?? '') }}" required>
                    @error('nom') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone *</label>
                    <input type="tel" name="telephone" id="telephone" class="form-control"
                        value="{{ old('telephone', auth()->user()->phone ?? '') }}" placeholder="+225 07 00 00 00 00"
                        pattern="^(\+225)?[0-9]{10}$" title="Entrez 10 chiffres, avec ou sans +225" maxlength="15"
                        inputmode="numeric" autocomplete="tel" required>
                    @error('telephone') <span class="error">{{ $message }}</span> @enderror
                    <small class="form-hint" id="telephoneHint">
                        Format : 07 00 00 00 00 ou +225 07 00 00 00 00 (10 chiffres)
                    </small>
                </div>

                <div class="form-group">
                    <label for="email">Email (optionnel)</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', auth()->user()->email ?? '') }}">
                    @error('email') <span class="error">{{ $message }}</span> @enderror
                </div>

                {{-- Ville (avant adresse) --}}
                <div class="form-group ville-autocomplete">
                    <label for="ville">Ville *</label>
                    <input type="text" name="ville" id="ville" class="form-control" value="{{ old('ville') }}"
                        placeholder="Commencez à taper le nom de votre ville..." autocomplete="off" required>

                    <div class="autocomplete-results" id="villeResults" style="display: none;"></div>

                    @error('ville') <span class="error">{{ $message }}</span> @enderror
                    <small class="form-hint">Sélectionnez dans la liste ou tapez le nom exact</small>
                </div>

                <div class="form-group">
                    <label for="adresse">Adresse de livraison *</label>
                    <textarea name="adresse" id="adresse" class="form-control" rows="3"
                        placeholder="Quartier, rue, repère..." required>{{ old('adresse') }}</textarea>
                    @error('adresse') <span class="error">{{ $message }}</span> @enderror
                </div>

                {{-- Mode de paiement --}}
                <div class="form-group">
                    <label>Mode de paiement *</label>

                    <div class="payment-options">
                        <label class="payment-option">
                            <input type="radio" name="mode_paiement" value="livraison"
                                {{ old('mode_paiement', 'livraison') === 'livraison' ? 'checked' : '' }} required>
                            <div class="payment-option-content">
                                <i class="fa-solid fa-money-bill-wave"></i>
                                <div>
                                    <strong>Paiement à la livraison</strong>
                                    <small>Payez en espèces quand vous recevez votre commande</small>
                                </div>
                            </div>
                        </label>
                    </div>

                    @error('mode_paiement') <span class="error">{{ $message }}</span> @enderror
                </div>

            </div>

            {{-- Récapitulatif --}}
            <div class="commande-recap">
                <h3>Récapitulatif</h3>

                @foreach($panier as $item)
                <div class="recap-item">
                    <span>{{ $item['nom'] }} × {{ $item['quantite'] }}</span>
                    <span>{{ number_format($item['prix'] * $item['quantite'], 0, ',', ' ') }} FCFA</span>
                </div>
                @endforeach

                <hr>

                <div class="recap-line recap-total">
                    <span>Total</span>
                    <span>{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                </div>

                <button type="submit" id="submitBtn" class="btn btn-primary btn-lg btn-block mt-3" disabled>
                    <i class="fa-solid fa-lock"></i>
                    <span id="submitBtnText">Remplissez tous les champs</span>
                </button>

                <p class="text-small text-muted mt-2">
                    En confirmant, vous acceptez nos conditions générales de vente.
                </p>
            </div>

        </div>

    </form>

</div>

@endsection

@push('scripts')
<script>
(function() {
    'use strict';

    const form = document.getElementById('commandeForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const telephoneField = document.getElementById('telephone');
    const telephoneHint = document.getElementById('telephoneHint');

    if (!form || !submitBtn) return;

    // ─── Champs texte obligatoires ───
    const requiredFields = ['nom', 'telephone', 'ville', 'adresse'];

    // ─── Regex téléphone ivoirien ───
    const regexTelephone = /^(\+225)?[0-9]{10}$/;

    // ─── Nettoie un numéro (retire espaces, tirets, points) ───
    function nettoyerTelephone(valeur) {
        return valeur.replace(/[\s\-\.]/g, '');
    }

    // ─── Vérifie si le téléphone est valide ───
    function telephoneValide() {
        if (!telephoneField) return false;
        const valeur = nettoyerTelephone(telephoneField.value);
        return regexTelephone.test(valeur);
    }

    // ─── Affiche le feedback visuel du téléphone ───
    function afficherFeedbackTelephone() {
        if (!telephoneField || !telephoneHint) return;

        const valeur = nettoyerTelephone(telephoneField.value);

        // Champ vide
        if (valeur === '') {
            telephoneField.classList.remove('is-valid', 'is-invalid');
            telephoneHint.textContent = 'Format : 07 00 00 00 00 ou +225 07 00 00 00 00 (10 chiffres)';
            telephoneHint.className = 'form-hint';
            return;
        }

        // Validation en cours (moins de 10 chiffres)
        const chiffres = valeur.replace(/^\+225/, '');
        if (chiffres.length < 10) {
            telephoneField.classList.remove('is-valid');
            telephoneField.classList.add('is-invalid');
            telephoneHint.textContent = `Encore ${10 - chiffres.length} chiffre(s) à saisir`;
            telephoneHint.className = 'form-hint text-warning';
            return;
        }

        // Validation finale
        if (regexTelephone.test(valeur)) {
            telephoneField.classList.remove('is-invalid');
            telephoneField.classList.add('is-valid');
            telephoneHint.textContent = '✓ Numéro valide';
            telephoneHint.className = 'form-hint text-success';
        } else {
            telephoneField.classList.remove('is-valid');
            telephoneField.classList.add('is-invalid');
            telephoneHint.textContent = 'Numéro invalide : 10 chiffres requis';
            telephoneHint.className = 'form-hint text-danger';
        }
    }

    // ─── Vérifie si TOUT le formulaire est valide ───
    function checkFormValidity() {
        let allValid = true;

        // 1. Vérifier les champs texte obligatoires
        requiredFields.forEach(id => {
            const field = document.getElementById(id);
            if (!field) return;

            if (field.value.trim() === '') {
                allValid = false;
            }
        });

        // 2. Vérifier le téléphone (format CI + 10 chiffres exacts)
        if (!telephoneValide()) {
            allValid = false;
        }

        // 3. Vérifier le mode de paiement
        const modePaiement = form.querySelector('input[name="mode_paiement"]:checked');
        if (!modePaiement) {
            allValid = false;
        }

        // 4. Mettre à jour le bouton
        if (allValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-disabled');
            submitBtn.classList.add('btn-primary');
            submitBtnText.textContent = 'Confirmer la commande';
            submitBtn.querySelector('i').className = 'fa-solid fa-check';
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-disabled');
            submitBtn.classList.remove('btn-primary');
            submitBtnText.textContent = 'Remplissez tous les champs';
            submitBtn.querySelector('i').className = 'fa-solid fa-lock';
        }
    }

    // ─── Écouteurs ───

    // Champs obligatoires
    requiredFields.forEach(id => {
        const field = document.getElementById(id);
        if (field) {
            field.addEventListener('input', () => {
                if (id === 'telephone') afficherFeedbackTelephone();
                checkFormValidity();
            });
        }
    });

    // Radios
    form.querySelectorAll('input[name="mode_paiement"]').forEach(radio => {
        radio.addEventListener('change', checkFormValidity);
    });

    // Vérification initiale
    checkFormValidity();
    afficherFeedbackTelephone();

})();
</script>
@endpush