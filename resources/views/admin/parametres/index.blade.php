@extends('layouts.admin')

@section('title', 'Paramètres')
@section('page-title', 'Paramètres')

@section('content')

@php
$activeTab = session('active_tab', $parametres->keys()->first() ?? 'general');
@endphp

{{-- ═══════════════════════════════════════════════════════
     ONGLETS
     ═══════════════════════════════════════════════════════ --}}
<div class="settings-tabs">
    @foreach($parametres as $groupe => $items)
    @php
    $icone = match($groupe) {
    'general' => 'fa-sliders',
    'contact' => 'fa-address-book',
    'reseaux' => 'fa-share-nodes',
    'livraison' => 'fa-truck-fast',
    'seo' => 'fa-magnifying-glass',
    default => 'fa-gear',
    };
    $libelle = $categoriesLibelles[$groupe] ?? ucfirst($groupe);
    @endphp
    <button type="button" class="settings-tab {{ $activeTab === $groupe ? 'active' : '' }}" data-group="{{ $groupe }}">
        <i class="fa-solid {{ $icone }}"></i>
        <span>{{ $libelle }}</span>
    </button>
    @endforeach

    <button type="button" class="settings-tab {{ $activeTab === 'compte' ? 'active' : '' }}" data-group="compte">
        <i class="fa-solid fa-user-circle"></i>
        <span>Mon compte</span>
    </button>
</div>

{{-- ═══════════════════════════════════════════════════════
     FORMULAIRE PARAMÈTRES GLOBAUX
     ═══════════════════════════════════════════════════════ --}}
<form method="POST" action="{{ route('admin.parametres.update') }}" id="formParametres">
    @csrf

    @foreach($parametres as $groupe => $items)
    <div class="settings-panel {{ $activeTab === $groupe ? 'active' : '' }}" data-group="{{ $groupe }}">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2>
                    <i class="fa-solid fa-gear"></i>
                    {{ $categoriesLibelles[$groupe] ?? ucfirst($groupe) }}
                </h2>
            </div>

            <div class="admin-card-body">
                <div class="form-grid">
                    @foreach($items as $parametre)
                    <div class="form-group {{ $parametre->type === 'textarea' ? 'form-group-full' : '' }}">
                        <label for="param_{{ $parametre->cle }}">
                            {{ $parametre->libelle }}
                        </label>

                        @if($parametre->type === 'textarea')
                        <textarea name="parametres[{{ $parametre->cle }}]" id="param_{{ $parametre->cle }}"
                            class="form-control"
                            rows="4">{{ old("parametres.{$parametre->cle}", $parametre->valeur) }}</textarea>

                        @elseif($parametre->type === 'boolean')
                        <label class="checkbox-label">
                            <input type="hidden" name="parametres[{{ $parametre->cle }}]" value="0">
                            <input type="checkbox" name="parametres[{{ $parametre->cle }}]" value="1"
                                {{ old("parametres.{$parametre->cle}", $parametre->valeur) == '1' ? 'checked' : '' }}>
                            <span>Activé</span>
                        </label>

                        @else
                        @php
                        $inputType = match($parametre->type) {
                        'email' => 'email',
                        'url' => 'url',
                        'number' => 'number',
                        default => 'text',
                        };
                        @endphp
                        <input type="{{ $inputType }}" name="parametres[{{ $parametre->cle }}]"
                            id="param_{{ $parametre->cle }}" class="form-control"
                            value="{{ old("parametres.{$parametre->cle}", $parametre->valeur) }}">
                        @endif

                        @if($parametre->description)
                        <small class="form-hint">{{ $parametre->description }}</small>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="form-actions" id="actionsParametres" style="{{ $activeTab === 'compte' ? 'display: none;' : '' }}">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-check"></i> Enregistrer les paramètres
        </button>
    </div>
</form>

{{-- ═══════════════════════════════════════════════════════
     FORMULAIRE MON COMPTE
     ═══════════════════════════════════════════════════════ --}}
<div class="settings-panel {{ $activeTab === 'compte' ? 'active' : '' }}" data-group="compte">
    <form method="POST" action="{{ route('admin.parametres.compte.update') }}" id="formCompte">
        @csrf
        @method('PUT')

        @if($errors->any())
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <div>
                @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Informations personnelles --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fa-solid fa-user"></i> Informations personnelles</h2>
            </div>

            <div class="admin-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Prénom *</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name', $user->name) }}" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ' \-]{2,100}$"
                            title="Lettres, espaces, tirets et apostrophes (2 à 100 caractères)" minlength="2"
                            maxlength="100" autocomplete="given-name" required>
                        <small class="form-hint" id="nameHint">Lettres et espaces uniquement</small>
                    </div>

                    <div class="form-group">
                        <label for="surname">Nom *</label>
                        <input type="text" name="surname" id="surname" class="form-control"
                            value="{{ old('surname', $user->surname) }}" pattern="^[A-Za-zÀ-ÖØ-öø-ÿ' \-]{2,100}$"
                            title="Lettres, espaces, tirets et apostrophes (2 à 100 caractères)" minlength="2"
                            maxlength="100" autocomplete="family-name" required>
                        <small class="form-hint" id="surnameHint">Lettres et espaces uniquement</small>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ old('email', $user->email) }}"
                            pattern="^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$"
                            title="Entrez une adresse email valide" maxlength="255" autocomplete="email" required>
                        <small class="form-hint" id="emailHint">Exemple : nom@domaine.com</small>
                    </div>

                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="tel" name="phone" id="phone" class="form-control"
                            value="{{ old('phone', $user->phone) }}" placeholder="+225 07 00 00 00 00"
                            pattern="^(\+225)?[0-9]{10}$" title="10 chiffres avec ou sans +225" maxlength="15"
                            inputmode="numeric" autocomplete="tel">
                        <small class="form-hint" id="phoneHint">Format : 07 00 00 00 00 ou +225 07 00 00 00 00</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mot de passe --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fa-solid fa-lock"></i> Mot de passe</h2>
            </div>

            <div class="admin-card-body">
                <div class="alert alert-info" style="margin-bottom: 20px;">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Laissez vide si vous ne souhaitez pas changer de mot de passe.</span>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="8 caractères minimum" minlength="8">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" placeholder="Retapez le mot de passe" minlength="8">
                    </div>
                </div>
            </div>
        </div>

        {{-- Préférences --}}
        <div class="admin-card">
            <div class="admin-card-header">
                <h2><i class="fa-solid fa-palette"></i> Préférences</h2>
            </div>

            <div class="admin-card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="langue">Langue</label>
                        <select name="langue" id="langue" class="form-control">
                            <option value="fr" {{ old('langue', $user->langue) === 'fr' ? 'selected' : '' }}>Français
                            </option>
                            <option value="en" {{ old('langue', $user->langue) === 'en' ? 'selected' : '' }}>English
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="theme">Thème</label>
                        <select name="theme" id="theme" class="form-control">
                            <option value="light" {{ old('theme', $user->theme) === 'light' ? 'selected' : '' }}>Clair
                            </option>
                            <option value="dark" {{ old('theme', $user->theme) === 'dark'  ? 'selected' : '' }}>Sombre
                            </option>
                            <option value="auto" {{ old('theme', $user->theme) === 'auto'  ? 'selected' : '' }}>
                                Automatique</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-check"></i> Enregistrer mon profil
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
// ═══════════════════════════════════════════════════════════
// ONGLETS
// ═══════════════════════════════════════════════════════════
document.querySelectorAll('.settings-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const group = tab.dataset.group;

        document.querySelectorAll('.settings-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        document.querySelectorAll('.settings-panel').forEach(p => p.classList.remove('active'));
        document.querySelector(`.settings-panel[data-group="${group}"]`).classList.add('active');

        const actions = document.getElementById('actionsParametres');
        if (actions) {
            actions.style.display = (group === 'compte') ? 'none' : 'flex';
        }
    });
});

// ═══════════════════════════════════════════════════════════
// VALIDATION DU FORMULAIRE "MON COMPTE"
// ═══════════════════════════════════════════════════════════
(function() {
    'use strict';

    const regexNom = /^[A-Za-zÀ-ÖØ-öø-ÿ' \-]{2,100}$/;
    const regexEmail = /^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/;
    const regexTelephone = /^(\+225)?[0-9]{10}$/;

    // ─── Feedback visuel générique ───
    function afficherFeedback(id, valide, messageOk, messageKo) {
        const champ = document.getElementById(id);
        const hint = document.getElementById(id + 'Hint');
        if (!champ) return;

        const valeur = champ.value.trim();

        if (valeur === '') {
            champ.classList.remove('is-valid', 'is-invalid');
            if (hint) hint.className = 'form-hint';
            return;
        }

        if (valide) {
            champ.classList.remove('is-invalid');
            champ.classList.add('is-valid');
            if (hint) {
                hint.textContent = messageOk;
                hint.className = 'form-hint text-success';
            }
        } else {
            champ.classList.remove('is-valid');
            champ.classList.add('is-invalid');
            if (hint) {
                hint.textContent = messageKo;
                hint.className = 'form-hint text-danger';
            }
        }
    }

    // ─── Prénom ───
    document.getElementById('name')?.addEventListener('input', (e) => {
        const valide = regexNom.test(e.target.value.trim());
        afficherFeedback('name', valide, '✓ Prénom valide', 'Lettres, espaces, tirets uniquement');
    });

    // ─── Nom ───
    document.getElementById('surname')?.addEventListener('input', (e) => {
        const valide = regexNom.test(e.target.value.trim());
        afficherFeedback('surname', valide, '✓ Nom valide', 'Lettres, espaces, tirets uniquement');
    });

    // ─── Email ───
    document.getElementById('email')?.addEventListener('input', (e) => {
        const valide = regexEmail.test(e.target.value.trim());
        afficherFeedback('email', valide, '✓ Email valide', 'Format email invalide');
    });

    // ═══════════════════════════════════════════════════════════
    // TÉLÉPHONE — Bloque les lettres à la frappe + au collage
    // ═══════════════════════════════════════════════════════════
    const phoneInput = document.getElementById('phone');
    const phoneHint = document.getElementById('phoneHint');

    // Bloque les touches non autorisées (sauf contrôle et raccourcis)
    phoneInput?.addEventListener('keydown', (e) => {
        const touchesControle = [
            'Backspace', 'Delete', 'ArrowLeft', 'ArrowRight',
            'Tab', 'Enter', 'Home', 'End', 'Escape'
        ];
        if (touchesControle.includes(e.key)) return;
        if (e.ctrlKey || e.metaKey) return;

        // Autorise seulement chiffres, +, espaces, tirets
        if (!/^[0-9+\s\-]$/.test(e.key)) {
            e.preventDefault();
        }
    });

    // Bloque le collage de texte invalide
    phoneInput?.addEventListener('paste', (e) => {
        e.preventDefault();

        const texte = (e.clipboardData || window.clipboardData).getData('text');
        const texteFiltre = texte.replace(/[^0-9+\s\-]/g, '');

        const posCurseur = phoneInput.selectionStart;
        const avant = phoneInput.value.slice(0, posCurseur);
        const apres = phoneInput.value.slice(phoneInput.selectionEnd);

        phoneInput.value = avant + texteFiltre + apres;

        phoneInput.dispatchEvent(new Event('input'));
    });

    // Validation en temps réel
    phoneInput?.addEventListener('input', (e) => {
        // Filtre de sécurité (au cas où)
        const valeur = e.target.value;
        const valeurFiltree = valeur.replace(/[^0-9+\s\-]/g, '');

        if (valeur !== valeurFiltree) {
            const posCurseur = e.target.selectionStart - (valeur.length - valeurFiltree.length);
            e.target.value = valeurFiltree;
            e.target.setSelectionRange(posCurseur, posCurseur);
        }

        // Validation
        const valeurNettoyee = e.target.value.replace(/[\s\-\.]/g, '');

        if (valeurNettoyee === '') {
            e.target.classList.remove('is-valid', 'is-invalid');
            if (phoneHint) {
                phoneHint.textContent = 'Format : 07 00 00 00 00 ou +225 07 00 00 00 00';
                phoneHint.className = 'form-hint';
            }
            return;
        }

        const chiffres = valeurNettoyee.replace(/^\+225/, '');

        if (chiffres.length < 10) {
            e.target.classList.remove('is-valid');
            e.target.classList.add('is-invalid');
            if (phoneHint) {
                phoneHint.textContent = `Encore ${10 - chiffres.length} chiffre(s) à saisir`;
                phoneHint.className = 'form-hint text-warning';
            }
            return;
        }

        if (regexTelephone.test(valeurNettoyee)) {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
            if (phoneHint) {
                phoneHint.textContent = '✓ Numéro valide';
                phoneHint.className = 'form-hint text-success';
            }
        } else {
            e.target.classList.remove('is-valid');
            e.target.classList.add('is-invalid');
            if (phoneHint) {
                phoneHint.textContent = 'Numéro invalide : 10 chiffres requis';
                phoneHint.className = 'form-hint text-danger';
            }
        }
    });

})();
</script>
@endpush