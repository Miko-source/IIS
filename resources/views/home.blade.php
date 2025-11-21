<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <title>DisinfoCamp Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f3f4f6;
            color: #111827;
        }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            background: #ffffff;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            padding: 12px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-title {
            font-weight: 700;
            font-size: 20px;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-link {
            font-size: 14px;
            background: transparent;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 6px;
            background: #111827;
            color: #ffffff;
            border: none;
            cursor: pointer;
        }

        .btn-outline {
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 6px;
            background: #ffffff;
            border: 1px solid #d1d5db;
            cursor: pointer;
        }

        /* Hero */
        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 16px;
        }

        .hero {
            max-width: 800px;
            text-align: center;
            margin-bottom: 40px;
        }

        .hero-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .hero-text {
            font-size: 18px;
            color: #4b5563;
            margin-bottom: 24px;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Role cards */
        .roles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            width: 100%;
            max-width: 1000px;
        }

        .role-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 16px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            text-align: left;
        }

        .role-title {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .role-text {
            font-size: 14px;
            color: #4b5563;
        }

        /* Footer */
        .footer {
            padding: 12px 0;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }

        /* LOGIN MODAL */

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }

        .modal-backdrop.is-visible {
            display: flex;
        }

        .modal {
            background: #ffffff;
            border-radius: 10px;
            padding: 24px;
            width: 100%;
            max-width: 420px;
            position: relative;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .modal-close {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 18px;
            color: #6b7280;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .form-input {
            width: 100%;
            padding: 8px 10px;
            font-size: 14px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
        }

        .form-error {
            font-size: 12px;
            color: red;
            margin-top: 4px;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            margin-top: 4px;
        }

        .link-button {
            border: none;
            background: transparent;
            text-decoration: underline;
            cursor: pointer;
            padding: 0;
            font-size: 12px;
        }

        .flash-message {
            font-size: 12px;
            color: red;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
<div class="page">

    
    <header class="navbar">
        <div class="navbar-title">
            DisinfoCamp Manager
        </div>
        <nav class="navbar-nav">
            <button class="btn-link">Domů</button>
            <button class="btn-link">Kampaně</button>

            {{-- tlačidlo otvorí login modal --}}
            <button class="btn-primary js-open-login">Přihlásit se</button>

            <a href="{{ route('register') }}" class="btn-outline" style="text-decoration:none;display:inline-block;">
                Registrovat se
            </a>
        </nav>
    </header>

    <!-- HLAVNÍ OBSAH -->
    <main class="main">
        <section class="hero">
            <h1 class="hero-title">Systém pro správu dezinformačních kampaní</h1>
            <p class="hero-text">
                Plánujte témata, kroky kampaní a spravujte pracovníky, kteří se podílejí na šíření dezinformací.
            </p>
            <div class="hero-buttons">
                <button class="btn-primary js-open-login">Začít – přihlásit se</button>
                <a href="{{ route('register') }}" class="btn-outline" style="text-decoration:none;display:inline-block;">
                    Registrovat účet pracovníka
                </a>
            </div>
        </section>

        <section class="roles">
            <div class="role-card">
                <h2 class="role-title">Administrátor</h2>
                <p class="role-text">
                    Spravuje uživatele, role, témata a přiřazuje správce kampaní.
                </p>
            </div>
            <div class="role-card">
                <h2 class="role-title">Správce kampaně</h2>
                <p class="role-text">
                    Definuje kroky kampaně, přiděluje koordinátory a mění stav kampaně.
                </p>
            </div>
            <div class="role-card">
                <h2 class="role-title">Koordinátor</h2>
                <p class="role-text">
                    Spravuje aktivity v jednotlivých krocích kampaně a pracovníky.
                </p>
            </div>
            <div class="role-card">
                <h2 class="role-title">Pracovník</h2>
                <p class="role-text">
                    Hlásí se na aktivity, provádí je a vyplňuje úspěšnost.
                </p>
            </div>
        </section>
    </main>

    <footer class="footer">
        &copy; 2025 DisinfoCamp Manager – váš tým
    </footer>
</div>

<div class="modal-backdrop" id="login-backdrop">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="login-title">
        <button class="modal-close" id="login-close" aria-label="Zavřít">
            ×
        </button>

        <h2 class="modal-title" id="login-title">Přihlášení do systému</h2>

        @include('components.flash-message')

        <form id="login-form" method="POST" action="{{ route('login.post') }}" novalidate>
            @csrf

            <div class="form-group">
                <label class="form-label">
                    E-mail <span style="color:red">*</span>
                </label>
                <input
                    type="email"
                    name="email"
                    id="login-email"
                    class="form-input"
                    value="{{ old('email') }}"
                    placeholder="např. worker@disinfo.test"
                >
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    Heslo <span style="color:red">*</span>
                </label>
                <input
                    type="password"
                    name="password"
                    id="login-password"
                    class="form-input"
                >
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <label style="display:flex; align-items:center; gap:4px;">
                    <input type="checkbox" name="remember">
                    <span>Zapamatovat si mě</span>
                </label>
                <button type="button" class="link-button">Zapomenuté heslo</button>
            </div>

            <button type="submit" class="btn-primary" style="width:100%; margin-top:8px;">
                Přihlásit se
            </button>

            <p style="font-size:12px; text-align:center; margin-top:8px;">
                Nemáte účet?
                <a href="{{ route('register') }}" class="link-button">Registrovat se</a>
            </p>
        </form>
    </div>
</div>

<script>
    const backdrop = document.getElementById('login-backdrop');
    const closeBtn = document.getElementById('login-close');
    const openButtons = document.querySelectorAll('.js-open-login');
    const shouldOpen = {{ json_encode(($openLoginModal ?? false) || session('openLoginModal') || $errors->has('login')) }};

    openButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            backdrop.classList.add('is-visible');
        });
    });

    if (shouldOpen) {
        backdrop.classList.add('is-visible');
    }

    closeBtn.addEventListener('click', () => {
        backdrop.classList.remove('is-visible');
    });

    backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
            backdrop.classList.remove('is-visible');
        }
    });
</script>

</body>
</html>
