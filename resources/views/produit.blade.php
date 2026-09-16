@extends('layouts.app')

@section('title', $produit->nom . ' — ShopCI')

@section('content')

<div class="container section">

    {{-- Fil d'Ariane --}}
    <div class="breadcrumb">
        <a href="{{ route('accueil') }}">Accueil</a>
        <i class="fa-solid fa-chevron-right breadcrumb-sep"></i>
        <a href="{{ route('catalogue') }}">Catalogue</a>
        @if($produit->categorie)
        <i class="fa-solid fa-chevron-right breadcrumb-sep"></i>
        <a href="{{ route('categorie.show', $produit->categorie->id) }}">
            {{ $produit->categorie->libelle }}
        </a>
        @endif
        <i class="fa-solid fa-chevron-right breadcrumb-sep"></i>
        <span>{{ $produit->nom }}</span>
    </div>

    <div class="produit-detail">

        {{-- Images --}}
        <div class="produit-detail-images">
            @php
            $images = $produit->images;
            $principale = $produit->imagePrincipale ?? $images->first();
            @endphp

            @if($principale)
            {{-- Image principale cliquable --}}
            <img src="{{ $principale->chemin }}" alt="{{ $produit->nom }}" class="produit-image-principale"
                id="image-principale" data-images='@json($produit->images->pluck("chemin"))'
                onclick="ouvrirLightbox(0)">

            {{-- Vignettes --}}
            @if($images->count() > 1)
            <div class="produit-images-gallery" id="vignettes">
                @foreach($images as $i => $image)
                <img src="{{ $image->chemin }}" alt="{{ $produit->nom }}"
                    class="vignette {{ $image->id === $principale->id ? 'active' : '' }}" data-index="{{ $i }}"
                    onclick="changerImage({{ $i }})">
                @endforeach
            </div>
            @endif
            @else
            <div class="placeholder-large">
                <i class="fa-solid fa-image"></i>
            </div>
            @endif
        </div>

        {{-- Détails --}}
        <div class="produit-detail-info">

            <h1>{{ $produit->nom }}</h1>

            @if($produit->categorie)
            <p class="produit-categorie">
                <i class="fa-solid fa-tag"></i>
                {{ $produit->categorie->libelle }}
            </p>
            @endif

            {{-- Prix --}}
            <div class="produit-prix-large">
                @if($produit->en_promo)
                <span class="prix-barre">{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                <span class="prix-promo">{{ number_format($produit->prix_promo, 0, ',', ' ') }} FCFA</span>
                @else
                <span>{{ number_format($produit->prix, 0, ',', ' ') }} FCFA</span>
                @endif
            </div>

            {{-- Description --}}
            @if($produit->description)
            <div class="produit-description">
                <h3>
                    <i class="fa-solid fa-align-left"></i>
                    Description
                </h3>
                <p>{{ $produit->description }}</p>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════
                 FORMULAIRE D'AJOUT AU PANIER
                 ═══════════════════════════════════════════════════ --}}

            @if($produit->stock > 0)

            {{-- Produit en stock : afficher le formulaire --}}
            @if($produit->a_des_options)
            <form method="POST" action="{{ route('panier.ajouter') }}" class="produit-form">
                @csrf
                <input type="hidden" name="produit_id" value="{{ $produit->id }}">

                {{-- Grouper les options par nom --}}
                @php
                $optionsGroupees = $produit->options->groupBy('nom');
                @endphp

                @foreach($optionsGroupees as $nomOption => $options)
                <div class="form-group">
                    <label>
                        <i class="fa-solid fa-list-ul"></i>
                        {{ $nomOption }}
                    </label>
                    <div class="options-list">
                        @foreach($options as $option)
                        <label class="option-item">
                            <input type="radio" name="options[{{ $nomOption }}]" value="{{ $option->valeur }}" required>
                            <span>{{ $option->valeur }}</span>
                            @if($option->prix_supplement > 0)
                            <small>
                                <i class="fa-solid fa-plus"></i>
                                {{ number_format($option->prix_supplement, 0, ',', ' ') }} FCFA
                            </small>
                            @endif
                        </label>
                        @endforeach
                    </div>
                </div>
                @endforeach

                {{-- Quantité --}}
                <div class="form-group">
                    <label for="quantite-avec-options">
                        <i class="fa-solid fa-calculator"></i>
                        Quantité
                    </label>
                    <div class="quantite-control">
                        <button type="button" class="quantite-btn"
                            onclick="changerQuantite(-1, 'quantite-avec-options')">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <input type="number" name="quantite" id="quantite-avec-options" value="1" min="1"
                            max="{{ $produit->stock }}">
                        <button type="button" class="quantite-btn"
                            onclick="changerQuantite(1, 'quantite-avec-options')">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <i class="fa-solid fa-cart-plus"></i>
                    Ajouter au panier
                </button>
            </form>
            @else
            <form method="POST" action="{{ route('panier.ajouter') }}" class="produit-form">
                @csrf
                <input type="hidden" name="produit_id" value="{{ $produit->id }}">

                {{-- Quantité --}}
                <div class="form-group">
                    <label for="quantite-sans-options">
                        <i class="fa-solid fa-calculator"></i>
                        Quantité
                    </label>
                    <div class="quantite-control">
                        <button type="button" class="quantite-btn"
                            onclick="changerQuantite(-1, 'quantite-sans-options')">
                            <i class="fa-solid fa-minus"></i>
                        </button>
                        <input type="number" name="quantite" id="quantite-sans-options" value="1" min="1"
                            max="{{ $produit->stock }}">
                        <button type="button" class="quantite-btn"
                            onclick="changerQuantite(1, 'quantite-sans-options')">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    <i class="fa-solid fa-cart-plus"></i>
                    Ajouter au panier
                </button>
            </form>
            @endif

            @else
            {{-- Produit épuisé : bouton désactivé --}}
            <div class="produit-form">
                <button type="button" class="btn btn-disabled btn-lg btn-block" disabled>
                    <i class="fa-solid fa-circle-xmark"></i>
                    Produit épuisé
                </button>
                <p class="text-center text-muted text-small mt-2">
                    Ce produit sera bientôt réapprovisionné.
                </p>
            </div>
            @endif

            {{-- Stock --}}
            @if($produit->stock <= 0) <p class="stock-epuise">
                <i class="fa-solid fa-circle-xmark"></i>
                Épuisé
                </p>
                @elseif($produit->stock <= 5) <p class="stock-limite">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Plus que {{ $produit->stock }} en stock
                    </p>
                    @else
                    <p class="stock-disponible">
                        <i class="fa-solid fa-circle-check"></i>
                        En stock ({{ $produit->stock }} disponibles)
                    </p>
                    @endif

        </div>

    </div>

    {{-- Produits similaires --}}
    @if($similaires->count() > 0)
    <section class="section">
        <h2 class="section-title">
            <i class="fa-solid fa-layer-group"></i>
            Produits similaires
        </h2>
        <div class="produits-grid">
            @foreach($similaires as $produit)
            @include('partials.produit-card', ['produit' => $produit])
            @endforeach
        </div>
    </section>
    @endif

</div>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox" onclick="fermerLightbox(event)">
    <button class="lightbox-close" onclick="fermerLightbox(event, true)" title="Fermer">
        <i class="fa-solid fa-xmark"></i>
    </button>

    <button class="lightbox-nav lightbox-prev" onclick="event.stopPropagation(); slideLightbox(-1)" title="Précédent">
        <i class="fa-solid fa-chevron-left"></i>
    </button>

    <img src="" alt="" class="lightbox-image" id="lightbox-image" onclick="event.stopPropagation()">

    <button class="lightbox-nav lightbox-next" onclick="event.stopPropagation(); slideLightbox(1)" title="Suivant">
        <i class="fa-solid fa-chevron-right"></i>
    </button>

    <div class="lightbox-counter" id="lightbox-counter"></div>
</div>

@push('scripts')
{{-- prettier-ignore --}}
<script>
const imgPrincipale = document.getElementById('image-principale');
const images = imgPrincipale ? JSON.parse(imgPrincipale.dataset.images) : [];
let currentImageIndex = 0;

// Changer l'image principale au clic sur une vignette
function changerImage(index) {
    currentImageIndex = index;

    const imagePrincipale = document.getElementById('image-principale');
    if (imagePrincipale) {
        imagePrincipale.src = images[index];
    }

    document.querySelectorAll('#vignettes .vignette').forEach((v, i) => {
        v.classList.toggle('active', i === index);
    });
}

// Lightbox
function ouvrirLightbox(index) {
    currentImageIndex = index;
    const lb = document.getElementById('lightbox');
    const img = document.getElementById('lightbox-image');

    img.src = images[index];
    lb.classList.add('active');
    document.body.style.overflow = 'hidden';

    majCompteur();
}

function fermerLightbox(event, force = false) {
    if (!force && event.target.id !== 'lightbox') return;

    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = '';
}

function slideLightbox(direction) {
    currentImageIndex = (currentImageIndex + direction + images.length) % images.length;

    document.getElementById('lightbox-image').src = images[currentImageIndex];
    majCompteur();
}

function majCompteur() {
    document.getElementById('lightbox-counter').textContent =
        (currentImageIndex + 1) + ' / ' + images.length;
}

document.addEventListener('keydown', (e) => {
    const lb = document.getElementById('lightbox');
    if (!lb.classList.contains('active')) return;

    if (e.key === 'Escape') fermerLightbox({
        target: {
            id: 'lightbox'
        }
    }, true);
    if (e.key === 'ArrowLeft') slideLightbox(-1);
    if (e.key === 'ArrowRight') slideLightbox(1);
});
</script>
@endpush

@endsection