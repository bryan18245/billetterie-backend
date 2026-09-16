// ============================================================
// ShopCI — Logique commune à toutes les pages
// ============================================================

/**
 * ============================================
 * 1. UTILITAIRES
 * ============================================
 */

/**
 * Formater un prix en FCFA
 */
function formatPrix(prix) {
    if (!prix && prix !== 0) return '0 FCFA';
    return new Intl.NumberFormat('fr-FR').format(prix) + ' FCFA';
}

/**
 * Afficher une notification
 */
function notifier(message, type = 'info') {
    const div = document.createElement('div');
    div.className = `notification notification-${type}`;
    div.textContent = message;

    document.body.appendChild(div);

    setTimeout(() => div.classList.add('show'), 100);

    setTimeout(() => {
        div.classList.remove('show');
        setTimeout(() => div.remove(), 300);
    }, 3000);
}

/**
 * Afficher un loader
 */
function showLoader() {
    let loader = document.getElementById('loader');
    if (!loader) {
        loader = document.createElement('div');
        loader.id = 'loader';
        loader.innerHTML = '<div class="spinner"></div>';
        document.body.appendChild(loader);
    }
    loader.style.display = 'flex';
}

function hideLoader() {
    const loader = document.getElementById('loader');
    if (loader) loader.style.display = 'none';
}

/**
 * Échapper le HTML (sécurité XSS)
 */
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * ============================================
 * 2. MENU MOBILE
 * ============================================
 */

function toggleMenu() {
    const nav = document.querySelector('.nav');
    if (nav) {
        nav.classList.toggle('active');
    }
}

/**
 * ============================================
 * 3. GESTION DU PANIER (localStorage)
 * ============================================
 */

const Panier = {
    KEY: 'shopci_panier',

    /**
     * Récupérer le panier
     */
    get() {
        const data = localStorage.getItem(this.KEY);
        try {
            return data ? JSON.parse(data) : [];
        } catch (e) {
            return [];
        }
    },

    /**
     * Sauvegarder le panier
     */
    save(items) {
        localStorage.setItem(this.KEY, JSON.stringify(items));
        this.updateBadge();
    },

    /**
     * Ajouter un produit
     */
    add(produit, options = {}, quantite = 1) {
        const items = this.get();

        const optionsKey = JSON.stringify(options);
        const existant = items.find(item =>
            item.produit_id === produit.id &&
            JSON.stringify(item.options) === optionsKey
        );

        if (existant) {
            existant.quantite += quantite;
        } else {
            items.push({
                produit_id: produit.id,
                nom:        produit.nom,
                prix:       produit.prix_actuel || produit.prix,
                image:      produit.image_principale?.url || null,
                options:    options,
                quantite:   quantite,
            });
        }

        this.save(items);
    },

    /**
     * Supprimer un produit par index
     */
    remove(index) {
        const items = this.get();
        items.splice(index, 1);
        this.save(items);
    },

    /**
     * Modifier la quantité
     */
    updateQuantite(index, quantite) {
        const items = this.get();
        if (items[index]) {
            items[index].quantite = Math.max(1, quantite);
            this.save(items);
        }
    },

    /**
     * Total du panier
     */
    total() {
        return this.get().reduce((sum, item) =>
            sum + (item.prix * item.quantite), 0
        );
    },

    /**
     * Nombre d'articles
     */
    count() {
        return this.get().reduce((sum, item) => sum + item.quantite, 0);
    },

    /**
     * Vider le panier
     */
    clear() {
        this.save([]);
    },

    /**
     * Mettre à jour le badge
     */
    updateBadge() {
        const badge = document.getElementById('panier-count');
        if (badge) {
            const count = this.count();
            badge.textContent = count;
            badge.style.display = count > 0 ? 'inline-block' : 'none';
        }
    },
};

/**
 * ============================================
 * 4. GESTION DE L'AUTHENTIFICATION
 * ============================================
 */

const Auth = {
    isConnected() {
        return getToken() !== null;
    },

    async login(email, password) {
        const data = await AuthApi.login({ email, password });
        setToken(data.token);
        return data.user;
    },

    async register(userData) {
        const data = await AuthApi.register(userData);
        setToken(data.token);
        return data.user;
    },

    async logout() {
        try {
            await AuthApi.logout();
        } catch (e) {
            // Ignorer
        }
        removeToken();
        window.location.href = '/';
    },

    async me() {
        return await AuthApi.me();
    },
};

/**
 * ============================================
 * 5. INITIALISATION
 * ============================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // Mettre à jour le badge panier
    Panier.updateBadge();

    // Fermer le menu mobile au clic sur un lien
    document.querySelectorAll('.nav a').forEach(link => {
        link.addEventListener('click', () => {
            const nav = document.querySelector('.nav');
            if (nav) nav.classList.remove('active');
        });
    });

    // Ajouter les écouteurs sur les boutons "Ajouter au panier"
    // document.querySelectorAll('form[action*="panier/ajouter"]').forEach(form => {
    //     form.addEventListener('submit', async (e) => {
    //         e.preventDefault();

    //         const formData = new FormData(form);
    //         const produitId = formData.get('produit_id');
    //         const quantite = parseInt(formData.get('quantite')) || 1;

    //         // Récupérer les options
    //         const options = {};
    //         formData.forEach((value, key) => {
    //             if (key.startsWith('options[')) {
    //                 const nomOption = key.match(/\[(.*?)\]/)[1];
    //                 options[nomOption] = value;
    //             }
    //         });

    //         try {
    //             showLoader();

    //             // Récupérer les infos du produit
    //             const data = await ProduitsApi.getById(produitId);
    //             const produit = data.produit;

    //             // Ajouter au panier local
    //             Panier.add(produit, options, quantite);

    //             notifier('✓ Produit ajouté au panier', 'success');

    //         } catch (error) {
    //             notifier('✗ Erreur lors de l\'ajout', 'error');
    //             console.error(error);
    //         } finally {
    //             hideLoader();
    //         }
    //     });
    // });
});

/**
 * ============================================
 * 6. FONCTIONS SPÉCIFIQUES AUX PAGES
 * ============================================
 */

/**
 * Voir le détail d'un produit
 */
function voirProduit(id) {
    window.location.href = `/produits/${id}`;
}

/**
 * Confirmer une suppression
 */
function confirmer(message = 'Êtes-vous sûr ?') {
    return confirm(message);
}

/**
 * Formater une date
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function slideCategories(direction) {
    const slider = document.getElementById('categoriesSlider');
    if (!slider) return;
    const card = slider.querySelector('.categorie-card');
    const step = card ? card.offsetWidth + 12 : 200;
    slider.scrollBy({ left: direction * step * 3, behavior: 'smooth' });
}
// Quantité
function changerQuantite(delta, btnOrId, formToSubmit = null) {
    let input;

    if (btnOrId instanceof HTMLElement) {
        const control = btnOrId.closest('.quantite-control');
        input = control?.querySelector('input[type="number"]');
    } else {
        input = document.getElementById(btnOrId);
    }

    if (!input) return;

    const min = parseInt(input.min) || 1;
    const max = parseInt(input.max) || 9999;
    let valeur = parseInt(input.value) || 1;

    valeur = Math.max(min, Math.min(max, valeur + delta));
    input.value = valeur;

    // Affiche un feedback si bloqué au max
    if (valeur === max && delta > 0) {
        // Optionnel : notifier l'utilisateur
        // notifier('Stock maximum atteint', 'warning');
    }

    if (formToSubmit) {
        formToSubmit.submit();
    }
}
// ============================================================
// AUTOCOMPLÉTION VILLE
// ============================================================

document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('ville');
    const results = document.getElementById('villeResults');

    if (!input || !results) return;

    let timeout = null;

    input.addEventListener('input', () => {
        clearTimeout(timeout);

        const q = input.value.trim();

        if (q.length < 2) {
            results.style.display = 'none';
            results.innerHTML = '';
            return;
        }

        timeout = setTimeout(async () => {
            try {
                const res = await fetch(`/api/villes?q=${encodeURIComponent(q)}`);
                const villes = await res.json();

                if (villes.length === 0) {
                    results.innerHTML = '<div class="autocomplete-empty">Aucune ville trouvée</div>';
                    results.style.display = 'block';
                    return;
                }

                results.innerHTML = villes.map(v =>
                    `<div class="autocomplete-item" data-value="${escapeHtml(v.nom)}">
                        <strong>${escapeHtml(v.nom)}</strong>
                        <small>${escapeHtml(v.region || '')}</small>
                    </div>`
                ).join('');

                results.style.display = 'block';

                // Clic sur une suggestion
                results.querySelectorAll('.autocomplete-item').forEach(item => {
                    item.addEventListener('click', () => {
                        input.value = item.dataset.value;
                        results.style.display = 'none';
                    });
                });

            } catch (err) {
                console.error('Erreur autocomplétion :', err);
            }
        }, 250);
    });

    // Cacher au clic ailleurs
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.ville-autocomplete')) {
            results.style.display = 'none';
        }
    });

    // Navigation clavier
    input.addEventListener('keydown', (e) => {
        if (results.style.display !== 'block') return;

        const items = results.querySelectorAll('.autocomplete-item');
        const active = results.querySelector('.autocomplete-item.active');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!active) {
                items[0]?.classList.add('active');
            } else {
                active.classList.remove('active');
                (active.nextElementSibling || items[0]).classList.add('active');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (!active) {
                items[items.length - 1]?.classList.add('active');
            } else {
                active.classList.remove('active');
                (active.previousElementSibling || items[items.length - 1]).classList.add('active');
            }
        } else if (e.key === 'Enter') {
            if (active) {
                e.preventDefault();
                input.value = active.dataset.value;
                results.style.display = 'none';
            }
        } else if (e.key === 'Escape') {
            results.style.display = 'none';
        }
    });
});

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}