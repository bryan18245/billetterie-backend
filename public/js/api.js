// ============================================================
// ShopCI — Module API
// Centralise tous les appels à l'API Laravel
// ============================================================

/**
 * Configuration de l'API
 */
const API = {
    BASE_URL: '/api',
    TOKEN_KEY: 'shopci_token',
};

/**
 * Récupérer le token stocké
 */
function getToken() {
    return localStorage.getItem(API.TOKEN_KEY);
}

/**
 * Sauvegarder le token
 */
function setToken(token) {
    localStorage.setItem(API.TOKEN_KEY, token);
}

/**
 * Supprimer le token
 */
function removeToken() {
    localStorage.removeItem(API.TOKEN_KEY);
}

/**
 * Récupérer le token CSRF (pour les requêtes POST web)
 */
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : null;
}

/**
 * Requête générique vers l'API
 */
async function apiRequest(endpoint, options = {}) {
    const url = `${API.BASE_URL}${endpoint}`;

    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...(options.headers || {}),
    };

    // Ajouter le token si présent
    const token = getToken();
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const config = {
        method: options.method || 'GET',
        headers,
    };

    if (options.body) {
        config.body = JSON.stringify(options.body);
    }

    try {
        const response = await fetch(url, config);
        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw {
                status: response.status,
                message: data.message || 'Erreur inconnue',
                errors: data.errors || {},
            };
        }

        return data;
    } catch (error) {
        console.error('Erreur API :', error);
        throw error;
    }
}

/**
 * Méthodes raccourcies
 */
const Api = {
    get:    (endpoint)         => apiRequest(endpoint),
    post:   (endpoint, body)   => apiRequest(endpoint, { method: 'POST',   body }),
    put:    (endpoint, body)   => apiRequest(endpoint, { method: 'PUT',    body }),
    patch:  (endpoint, body)   => apiRequest(endpoint, { method: 'PATCH',  body }),
    delete: (endpoint)         => apiRequest(endpoint, { method: 'DELETE' }),
};

/**
 * API Catégories
 */
const CategoriesApi = {
    getAll:      ()   => Api.get('/categories'),
    getById:     (id) => Api.get(`/categories/${id}`),
    getProduits: (id) => Api.get(`/categories/${id}/produits`),
};

/**
 * API Produits
 */
const ProduitsApi = {
    getAll: (params = {}) => {
        const query = new URLSearchParams(params).toString();
        return Api.get(`/produits${query ? '?' + query : ''}`);
    },
    getById:    (id) => Api.get(`/produits/${id}`),
    search:     (q)  => Api.get(`/produits/search?q=${encodeURIComponent(q)}`),
    populaires: ()   => Api.get('/produits/populaires'),
    promotions: ()   => Api.get('/produits/promotions'),
    nouveaux:   ()   => Api.get('/produits/nouveaux'),
};

/**
 * API Auth (pour usage futur)
 */
const AuthApi = {
    login:    (data) => Api.post('/auth/login', data),
    register: (data) => Api.post('/auth/register', data),
    logout:   ()     => Api.post('/auth/logout'),
    me:       ()     => Api.get('/auth/me'),
};