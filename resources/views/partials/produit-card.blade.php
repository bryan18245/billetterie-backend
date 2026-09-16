<div class="produit-card">

    {{-- Image --}}
    <a href="{{ route('produit.show', $produit->id) }}" class="produit-image-link">
        <div class="produit-image">
            @if($produit->imagePrincipale)
            <img src="{{ $produit->imagePrincipale->chemin }}" alt="{{ $produit->nom }}">
            @else
            <div class="placeholder">📦</div>
            @endif

            @if($produit->en_promo)
            <span class="badge-promo">PROMO</span>
            @endif
        </div>
    </a>

    {{-- Contenu --}}
    <div class="produit-content">

        {{-- Catégorie --}}
        @if($produit->categorie)
        <p class="produit-categorie">{{ $produit->categorie->libelle }}</p>
        @endif

        {{-- Nom --}}
        <h3 class="produit-nom">
            <a href="{{ route('produit.show', $produit->id) }}">
                {{ $produit->nom }}
            </a>
        </h3>

        {{-- Prix --}}
        <div class="produit-prix">
            @if($produit->en_promo)
            <span class="prix-barre">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
            <span class="prix-promo">{{ number_format($produit->prix_promo, 0, ',', ' ') }} FCFA</span>
            @else
            <span>{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
            @endif
        </div>

        {{-- Stock --}}
        @if($produit->stock <= 0) <p class="stock-epuise">Épuisé</p>
            @elseif($produit->stock <= 5) <p class="stock-limite">Plus que {{ $produit->stock }} en stock</p>
                @endif

                {{-- Bouton ajouter au panier --}}
                @if($produit->stock > 0)
                <form method="POST" action="{{ route('panier.ajouter') }}">
                    @csrf
                    <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                    <input type="hidden" name="quantite" value="1">
                    <button type="submit" class="btn btn-primary btn-block">
                        Ajouter au panier
                    </button>
                </form>
                @else
                <button class="btn btn-disabled btn-block" disabled>
                    Indisponible
                </button>
                @endif

    </div>
</div>