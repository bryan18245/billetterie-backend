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

<div class="form-grid">
    <div class="form-group">
        <label for="name">Prénom *</label>
        <input type="text" name="name" id="name" class="form-control"
            value="{{ old('name', $utilisateur->name ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="surname">Nom *</label>
        <input type="text" name="surname" id="surname" class="form-control"
            value="{{ old('surname', $utilisateur->surname ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="email">Email *</label>
        <input type="email" name="email" id="email" class="form-control"
            value="{{ old('email', $utilisateur->email ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="phone">Téléphone</label>
        <input type="text" name="phone" id="phone" class="form-control"
            value="{{ old('phone', $utilisateur->phone ?? '') }}" placeholder="+225 07 00 00 00 00">
    </div>

    <div class="form-group">
        <label for="password">
            Mot de passe
            @if(isset($utilisateur))
            <small class="text-muted">(laisser vide pour ne pas changer)</small>
            @else
            *
            @endif
        </label>
        <input type="password" name="password" id="password" class="form-control"
            {{ isset($utilisateur) ? '' : 'required' }}>
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
            {{ isset($utilisateur) ? '' : 'required' }}>
    </div>

    <div class="form-group">
        <label for="role_id">Rôle *</label>
        <select name="role_id" id="role_id" class="form-control" required>
            <option value="">-- Choisir --</option>
            @foreach($roles as $role)
            <option value="{{ $role->id }}"
                {{ old('role_id', $utilisateur->role_id ?? '') == $role->id ? 'selected' : '' }}>
                {{ $role->libelle }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="statut">Statut *</label>
        <select name="statut" id="statut" class="form-control" required>
            <option value="actif" {{ old('statut', $utilisateur->statut ?? 'actif') === 'actif' ? 'selected' : '' }}>
                Actif</option>
            <option value="inactif" {{ old('statut', $utilisateur->statut ?? '') === 'inactif' ? 'selected' : '' }}>
                Inactif</option>
            <option value="suspendu" {{ old('statut', $utilisateur->statut ?? '') === 'suspendu' ? 'selected' : '' }}>
                Suspendu</option>
        </select>
    </div>

    <div class="form-group">
        <label for="langue">Langue *</label>
        <select name="langue" id="langue" class="form-control" required>
            <option value="fr" {{ old('langue', $utilisateur->langue ?? 'fr') === 'fr' ? 'selected' : '' }}>Français
            </option>
            <option value="en" {{ old('langue', $utilisateur->langue ?? '') === 'en' ? 'selected' : '' }}>English
            </option>
        </select>
    </div>

    <div class="form-group">
        <label for="theme">Thème *</label>
        <select name="theme" id="theme" class="form-control" required>
            <option value="light" {{ old('theme', $utilisateur->theme ?? 'light') === 'light' ? 'selected' : '' }}>Clair
            </option>
            <option value="dark" {{ old('theme', $utilisateur->theme ?? '') === 'dark' ? 'selected' : '' }}>Sombre
            </option>
            <option value="auto" {{ old('theme', $utilisateur->theme ?? '') === 'auto' ? 'selected' : '' }}>Automatique
            </option>
        </select>
    </div>
</div>