<section class="newsletter">
    <div class="newsletter-overlay"></div>
    <div class="container">
        <div class="newsletter-content">
            <div class="newsletter-text">
                <h3>Restez informé</h3>
                <p>Recevez nos nouveautés et promos directement par email.</p>
            </div>

            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="newsletter-form">
                @csrf
                <input type="email" name="email" placeholder="Votre adresse email" required class="newsletter-input">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i>
                    S'inscrire
                </button>
            </form>
        </div>

        @if(session('success'))
        <p class="newsletter-success">{{ session('success') }}</p>
        @endif
    </div>
</section>