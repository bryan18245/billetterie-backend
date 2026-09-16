<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin — ShopCI</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
</head>

<body>

    <div class="admin-login">
        <div class="admin-login-box">
            <div class="login-logo">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>

            <h1>ShopCI Admin</h1>
            <p class="login-subtitle">Connectez-vous à votre espace</p>

            @if($errors->any())
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">
                        <i class="fa-solid fa-envelope"></i>
                        Email
                    </label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                        placeholder="votre@email.com" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fa-solid fa-lock"></i>
                        Mot de passe
                    </label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••"
                        required>
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        <span>Se souvenir de moi</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Se connecter
                </button>
            </form>
        </div>
    </div>

</body>

</html>