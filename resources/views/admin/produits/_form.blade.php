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
$verrouille = isset($produit) && $produit->estDansCommandeActive();
@endphp

@if($verrouille)
<div class="alert alert-error" style="margin-bottom: 20px;">
    <i class="fa-solid fa-lock"></i>
    <div>
        <strong>Produit verrouillé</strong>
        <p>
            Ce produit est présent dans une ou plusieurs commandes en cours.
            Il ne peut pas être modifié ni supprimé tant que ces commandes ne sont pas livrées ou annulées.
        </p>
    </div>
</div>
@endif

<div class="form-grid">
    <div class="form-group" style="grid-column: span 2;">
        <label for="nom">Nom du produit *</label>
        <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $produit->nom ?? '') }}"
            {{ $verrouille ? 'readonly' : '' }} required>
    </div>

    <div class="form-group">
        <label for="categorie_id">Catégorie *</label>
        <select name="categorie_id" id="categorie_id" class="form-control" {{ $verrouille ? 'disabled' : '' }} required>
            <option value="">-- Choisir --</option>
            @foreach($categories as $categorie)
            <option value="{{ $categorie->id }}"
                {{ old('categorie_id', $produit->categorie_id ?? '') == $categorie->id ? 'selected' : '' }}>
                {{ $categorie->libelle }}
            </option>
            @endforeach
        </select>
        @if($verrouille)
        <input type="hidden" name="categorie_id" value="{{ $produit->categorie_id }}">
        @endif
    </div>

    <div class="form-group">
        <label for="sku">SKU (optionnel)</label>
        <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $produit->sku ?? '') }}"
            placeholder="Laissez vide pour auto-générer" {{ $verrouille ? 'readonly' : '' }}>
    </div>

    <div class="form-group">
        <label for="prix">Prix normal (FCFA) *</label>
        <input type="number" name="prix" id="prix" class="form-control" value="{{ old('prix', $produit->prix ?? '') }}"
            min="0" step="1" {{ $verrouille ? 'readonly' : '' }} required>
    </div>

    <div class="form-group">
        <label for="prix_promo">Prix promo (FCFA)</label>
        <input type="number" name="prix_promo" id="prix_promo" class="form-control"
            value="{{ old('prix_promo', $produit->prix_promo ?? '') }}" min="0" step="1"
            placeholder="Laisser vide si pas de promo" {{ $verrouille ? 'readonly' : '' }}>
    </div>

    <div class="form-group">
        <label for="stock">Stock *</label>
        <input type="number" name="stock" id="stock" class="form-control"
            value="{{ old('stock', $produit->stock ?? 0) }}" min="0" {{ $verrouille ? 'readonly' : '' }} required>
    </div>

    <div class="form-group">
        <label>&nbsp;</label>
        <label class="checkbox-label {{ $verrouille ? 'is-locked' : '' }}">
            <input type="hidden" name="actif" value="0">
            <input type="checkbox" name="actif" value="1" {{ old('actif', $produit->actif ?? true) ? 'checked' : '' }}
                {{ $verrouille ? 'disabled' : '' }}>
            <span>Produit actif (visible sur le site)</span>
        </label>
        @if($verrouille)
        <input type="hidden" name="actif" value="1">
        @endif
    </div>

    <div class="form-group" style="grid-column: span 2;">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control" rows="5" placeholder="Décrivez le produit..."
            {{ $verrouille ? 'readonly' : '' }}>{{ old('description', $produit->description ?? '') }}</textarea>
    </div>

    @if(!$verrouille)
    <div class="form-group" style="grid-column: span 2;">
        <label for="images">Ajouter des images</label>
        <input type="file" name="images[]" id="images" class="form-control" accept="image/*" multiple>
        <small class="form-hint">Plusieurs images possibles. Max 2 Mo par image.</small>
    </div>
    @endif
</div>