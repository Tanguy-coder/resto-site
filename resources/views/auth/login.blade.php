<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - NIWA FOOD</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #1a1a2e; color: #e0e0e0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .login-card { background: #16213e; border-radius: 12px; padding: 40px; width: 100%; max-width: 400px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); }
        .login-card h1 { color: #e94560; text-align: center; font-size: 1.6rem; letter-spacing: 3px; margin-bottom: 8px; }
        .login-card p { text-align: center; color: #94a3b8; margin-bottom: 30px; font-size: 0.9rem; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 0.9rem; color: #94a3b8; }
        .form-group input[type="email"],
        .form-group input[type="password"] { width: 100%; padding: 12px 14px; background: #1a1a2e; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; color: #e0e0e0; font-size: 0.95rem; transition: border 0.3s; }
        .form-group input:focus { outline: none; border-color: #e94560; }
        .checkbox-wrap { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
        .checkbox-wrap input { accent-color: #e94560; width: 16px; height: 16px; }
        .checkbox-wrap label { font-size: 0.85rem; color: #94a3b8; margin: 0; }
        .btn-login { width: 100%; padding: 12px; background: #e94560; color: #fff; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; transition: background 0.3s; font-weight: 600; }
        .btn-login:hover { background: #c73651; }
        .error { color: #fca5a5; font-size: 0.8rem; margin-top: 4px; }
        .errors-box { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); border-radius: 6px; padding: 12px; margin-bottom: 20px; }
        .errors-box li { color: #fca5a5; font-size: 0.85rem; list-style: none; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>NIWA FOOD</h1>
        <p>Espace d'administration</p>

        @if($errors->any())
            <div class="errors-box">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="checkbox-wrap">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Se souvenir de moi</label>
            </div>

            <button type="submit" class="btn-login">Se connecter</button>
        </form>
    </div>
</body>
</html>
