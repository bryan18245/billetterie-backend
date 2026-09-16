<footer class="footer">
    <div class="container">
        <div class="footer-content">

            {{-- Colonne 1 — À propos --}}
            <div class="footer-col">
                <h4>ShopCI</h4>
                <p>Votre boutique en ligne en Côte d'Ivoire.</p>
                <p>Produits de qualité, livraison rapide.</p>

                <div class="footer-socials">
                    <a href="#" title="Facebook" aria-label="Facebook" class="a">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" title="Instagram" aria-label="Instagram" class="a">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#" title="TikTok" aria-label="TikTok" class="a">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>
                    <a href="#" title="WhatsApp" aria-label="WhatsApp" class="a">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            {{-- Colonne 2 — Liens utiles --}}
            <div class="footer-col">
                <h4>Liens utiles</h4>
                <a href="{{ route('accueil') }}">
                    <i class="fa-solid fa-house"></i> Accueil
                </a>
                <a href="{{ route('catalogue') }}">
                    <i class="fa-solid fa-tags"></i> Produits
                </a>
                <a href="{{ route('pages.show', 'a-propos') }}">
                    <i class="fa-solid fa-circle-info"></i> À propos
                </a>
                <a href="{{ route('pages.show', 'cgv') }}">
                    <i class="fa-solid fa-file-contract"></i> CGV
                </a>
                <a href="{{ route('pages.show', 'mentions-legales') }}">
                    <i class="fa-solid fa-scale-balanced"></i> Mentions légales
                </a>
                <a href="{{ route('pages.show', 'politique-confidentialite') }}">
                    <i class="fa-solid fa-shield-halved"></i> Confidentialité
                </a>
                <a href="{{ route('pages.show', 'faq') }}">
                    <i class="fa-solid fa-circle-question"></i> FAQ
                </a>
                <a href="{{ route('pages.show', 'livraison') }}">
                    <i class="fa-solid fa-truck-fast"></i> Livraison
                </a>
                <a href="{{ route('pages.show', 'retours') }}">
                    <i class="fa-solid fa-rotate-left"></i> Retours
                </a>
            </div>

            {{-- Colonne 3 — Contact --}}
            <div class="footer-col">
                <h4>Contact</h4>
                <p>
                    <i class="fa-solid fa-location-dot"></i>
                    Abidjan, Côte d'Ivoire
                </p>
                <p>
                    <i class="fa-solid fa-envelope"></i>
                    <a href="mailto:contact@shopci.ci">contact@shopci.ci</a>
                </p>
                <p>
                    <i class="fa-solid fa-phone"></i>
                    <a href="tel:+2250700000000">+225 07 00 00 00 00</a>
                </p>
                <p>
                    <i class="fa-brands fa-whatsapp"></i>
                    <a href="https://wa.me/2250700000000" target="_blank" rel="noopener">
                        WhatsApp
                    </a>
                </p>
            </div>

            {{-- Colonne 4 — Paiement --}}
            <div class="footer-col">
                <h4>Moyens de paiement</h4>
                <p>
                    <i class="fa-solid fa-money-bill-wave"></i>
                    Paiement à la livraison
                </p>
                <p>
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    Orange Money
                </p>
                <p>
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    Wave
                </p>
                <p>
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    MTN MoMo
                </p>
                <p>
                    <i class="fa-solid fa-mobile-screen-button"></i>
                    Moov Money
                </p>
            </div>

        </div>

        <div class="footer-bottom">
            <p>© {{ date('Y') }} ShopCI — Tous droits réservés</p>
        </div>
    </div>
</footer>