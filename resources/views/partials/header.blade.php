<header class="header">
    <div class="container">
        <div class="header-content">

            {{-- Logo --}}
            <div class="logo">
                <a href="{{ route('accueil') }}" class="nav-a">ShopCI</a>
            </div>

            {{-- Navigation --}}
            <nav class="nav">
                <a href="{{ route('accueil') }}" class="nav-a">Accueil</a>
                <a href="{{ route('catalogue') }}" class="nav-a">Produits</a>

                {{-- Panier --}}
                <a href="{{ route('panier.index') }}" class="panier-link nav-a">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Panier</span>

                    @php
                    $panierCount = count(session('panier', []));
                    @endphp

                    @if($panierCount > 0)
                    <span class="panier-count">{{ $panierCount }}</span>
                    @endif
                </a>

                {{-- Auth --}}
                @auth
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.dashboard') }}" class="nav-a">
                    <i class="fa-solid fa-gauge"></i> Admin
                </a>
                @else
                {{-- Espace client : à implémenter plus tard --}}
                @endif
                @endauth
            </nav>

            {{-- Menu mobile --}}
            <button class="menu-toggle" onclick="toggleMenu()">☰</button>

        </div>
    </div>
</header>