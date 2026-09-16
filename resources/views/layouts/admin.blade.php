<!DOCTYPE html>
<html lang="{{ auth()->user()->langue ?? 'fr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — ShopCI</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @stack('styles')
</head>

<body class="theme-{{ auth()->user()->theme ?? 'light' }}">

    <div class="admin-layout">

        {{-- Overlay mobile --}}
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        {{-- Sidebar --}}
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span>ShopCI</span>
                </a>
                <button class="sidebar-close" onclick="toggleSidebar()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section">Menu principal</div>

                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Tableau de bord</span>
                </a>

                <a href="{{ route('admin.commandes.index') }}"
                    class="nav-link {{ request()->routeIs('admin.commandes.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box"></i>
                    <span>Commandes</span>
                </a>

                <a href="{{ route('admin.produits.index') }}"
                    class="nav-link {{ request()->routeIs('admin.produits.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i>
                    <span>Produits</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-folder-tree"></i>
                    <span>Catégories</span>
                </a>

                <div class="nav-section">Gestion</div>
                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.utilisateurs.index') }}"
                    class="nav-link {{ request()->routeIs('admin.utilisateurs.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Utilisateurs</span>
                </a>
                <a href="{{ route('admin.audits.index') }}"
                    class="nav-link {{ request()->routeIs('admin.audits.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-history"></i>
                    <span>Audits</span>
                </a>
                @endif

                <a href="{{ route('admin.clients.index') }}"
                    class="nav-link {{ request()->routeIs('admin.clients.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Clients</span>
                </a>

                <a href="{{ route('admin.newsletter.index') }}"
                    class="nav-link {{ request()->routeIs('admin.newsletter.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Newsletter</span>
                </a>

                <a href="{{ route('admin.parametres.index') }}"
                    class="nav-link {{ request()->routeIs('admin.parametres.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i>
                    <span>Paramètres</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="user-details">
                        <strong>{{ auth()->user()->name }} {{ auth()->user()->surname }}</strong>
                        <small>{{ auth()->user()->role?->libelle }}</small>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Contenu principal --}}
        <div class="admin-content">

            {{-- Topbar --}}
            <header class="admin-topbar">
                <button class="menu-btn" onclick="toggleSidebar()">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <h1 class="page-title">@yield('page-title', 'Admin')</h1>

                <div class="topbar-actions">
                    @yield('page-actions')
                </div>
            </header>

            {{-- Messages --}}
            <div class="admin-body">
                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                @yield('content')
            </div>

        </div>

    </div>
    {{-- Modal de confirmation --}}
    <div class="confirm-modal" id="confirmModal">
        <div class="confirm-modal-content">
            <div class="confirm-modal-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="confirm-modal-title" id="confirmModalTitle">Confirmer la suppression</h3>
            <p class="confirm-modal-message" id="confirmModalMessage">
                Êtes-vous sûr de vouloir effectuer cette action ?
            </p>
            <div class="confirm-modal-actions">
                <button type="button" class="btn btn-outline" onclick="closeConfirmModal()">
                    Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmModalBtn">
                    Confirmer
                </button>
            </div>
        </div>
    </div>
    <script>
    function toggleSidebar() {
        document.getElementById('adminSidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('show');
    }
    // ============================================================
    // MODAL DE CONFIRMATION
    // ============================================================

    let pendingForm = null;

    function openConfirmModal(form, options = {}) {
        pendingForm = form;

        const title = options.title || 'Confirmer la suppression';
        const message = options.message ||
            'Êtes-vous sûr de vouloir effectuer cette action ? Cette action est irréversible.';

        document.getElementById('confirmModalTitle').textContent = title;
        document.getElementById('confirmModalMessage').textContent = message;

        document.getElementById('confirmModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirmModal() {
        pendingForm = null;
        document.getElementById('confirmModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    // Quand on clique sur "Confirmer", on soumet le formulaire
    document.getElementById('confirmModalBtn').addEventListener('click', () => {
        if (pendingForm) {
            pendingForm.submit();
        }
    });

    // Fermer au clic sur l'overlay
    document.getElementById('confirmModal').addEventListener('click', (e) => {
        if (e.target.id === 'confirmModal') {
            closeConfirmModal();
        }
    });

    // Fermer avec Échap
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeConfirmModal();
    });

    // Intercepte tous les formulaires avec data-confirm
    document.addEventListener('submit', (e) => {
        const form = e.target;
        if (form.dataset.confirm !== undefined) {
            e.preventDefault();
            openConfirmModal(form, {
                title: form.dataset.confirmTitle || 'Confirmer la suppression',
                message: form.dataset.confirmMessage || form.dataset.confirm ||
                    'Êtes-vous sûr de vouloir effectuer cette action ? Cette action est irréversible.',
            });
        }
    });
    </script>

    @stack('scripts')
</body>

</html>