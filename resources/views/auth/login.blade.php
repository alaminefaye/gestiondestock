<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <style>
        .auth-main {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .auth-wrapper {
            width: 100%;
            max-width: 450px;
        }
    </style>
</head>
<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-direction="ltr">
    <div class="auth-main">
        <div class="auth-wrapper">
            <div class="auth-form">
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="text-center">
                            <i data-feather="box" class="w-16 h-16 text-primary-500 mx-auto mb-3" style="width: 48px; height: 48px;"></i>
                        </div>
                        
                        <div class="mb-4 text-center">
                            <h3 class="mb-2"><b>Bienvenue</b></h3>
                            <p class="text-muted">Connectez-vous pour continuer vers {{ config('app.name') }}</p>
                        </div>

                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Adresse Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="Email Address" 
                                       required autofocus>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       placeholder="Password" 
                                       required>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Se souvenir de moi
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="log-in" class="inline mr-2" style="width: 16px; height: 16px;"></i>
                                    Se connecter
                                </button>
                            </div>
                        </form>

                        <div class="text-center mt-4">
                            <p class="mb-0 text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>
