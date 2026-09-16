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

@php
$verrouille = isset($categorie)
&& $categorie->actif
&& $categorie->estDansCommandeActive();
@endphp

@if($verrouille)
<div class="alert alert-error" style="margin-bottom: 20px;">
    <i class="fa-solid fa-lock"></i>
    <div>
        <strong>Catégorie verrouillée</strong>
        <p>
            Cette catégorie contient des produits présents dans des commandes en cours.
            Elle ne peut pas être modifiée ni désactivée tant que ces commandes ne sont pas livrées ou annulées.
        </p>
    </div>
</div>
@endif

<div class="form-grid">
    <div class="form-group" style="grid-column: span 2;">
        <label for="libelle">Libellé *</label>
        <input type="text" name="libelle" id="libelle" class="form-control"
            value="{{ old('libelle', $categorie->libelle ?? '') }}" placeholder="Ex: Électronique"
            {{ $verrouille ? 'readonly' : '' }} required>
    </div>

    <div class="form-group">
        <label for="parent_id">Catégorie parente</label>
        <select name="parent_id" id="parent_id" class="form-control" {{ $verrouille ? 'disabled' : '' }}>
            <option value="">-- Aucune (catégorie racine) --</option>
            @foreach($parents as $parent)
            <option value="{{ $parent->id }}"
                {{ old('parent_id', $categorie->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                {{ $parent->libelle }}
            </option>
            @endforeach
        </select>
        @if($verrouille)
        <input type="hidden" name="parent_id" value="{{ $categorie->parent_id }}">
        @endif
        <small class="form-hint">Laissez vide pour une catégorie principale</small>
    </div>

    <div class="form-group">
        <label for="ordre">Ordre d'affichage</label>
        <input type="number" name="ordre" id="ordre" class="form-control"
            value="{{ old('ordre', $categorie->ordre ?? 0) }}" min="0" {{ $verrouille ? 'readonly' : '' }}>
        <small class="form-hint">Plus petit = affiché en premier</small>
    </div>

    <div class="form-group" style="grid-column: span 2;">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control" rows="4"
            placeholder="Description de la catégorie (optionnel)"
            {{ $verrouille ? 'readonly' : '' }}>{{ old('description', $categorie->description ?? '') }}</textarea>
    </div>

    <div class="form-group" style="grid-column: span 2;">
        <label class="checkbox-label {{ $verrouille ? 'is-locked' : '' }}">
            <input type="hidden" name="actif" value="0">
            <input type="checkbox" name="actif" value="1" {{ old('actif', $categorie->actif ?? true) ? 'checked' : '' }}
                {{ $verrouille ? 'disabled' : '' }}>
            <span>Catégorie active (visible sur le site)</span>
        </label>

        @if($verrouille)
        <input type="hidden" name="actif" value="1">
        <small class="form-hint text-danger" style="margin-top: 8px;">
            <i class="fa-solid fa-circle-info"></i>
            Désactivation bloquée par les commandes en cours.
        </small>
        @endif
    </div>
</div>