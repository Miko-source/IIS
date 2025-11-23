<!doctype html>
<html lang="cs">
<head>
    <meta charset="utf-8">
    <title>DisinfoCamp Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    >

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #dbeafe 0, #f3f4f6 45%, #f9fafb 100%);
            color: #111827;
        }

        .landing-navbar {
            background: #111827;
            color: #fff;
            box-shadow: 0 1px 4px rgba(0,0,0,.15);
        }

        .landing-navbar .navbar-brand {
            font-weight: 700;
            letter-spacing: .02em;
        }

        .landing-navbar .nav-link {
            color: #e5e7eb;
            font-size: 14px;
        }

        .landing-navbar .nav-link.active {
            color: #ffffff;
            font-weight: 600;
        }

        .hero-wrap {
            min-height: calc(100vh - 64px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 48px 16px 32px;
        }

        .hero-title {
            font-size: clamp(24px, 3vw, 32px);
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
        }

        .hero-subtitle {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 32px;
            color: #4b5563;
            font-size: 15px;
        }

        .auth-card {
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 24px;
            padding: 28px 28px 22px;
            box-shadow: 0 18px 45px rgba(15,23,42,.18);
            border: 1px solid rgba(148,163,184,.25);
        }

        .auth-card h2 {
            font-size: 20px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 4px;
        }

        .auth-card p.small {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 18px;
        }

        .form-label {
            font-size: 13px;
            margin-bottom: 4px;
            color: #374151;
        }

        .form-control {
            border-radius: 9999px;
            padding: 8px 14px;
            font-size: 14px;
        }

        .form-check-label {
            font-size: 13px;
        }

        .auth-submit-btn {
            width: 100%;
            border-radius: 9999px;
            padding: 10px 16px;
            font-size: 15px;
            font-weight: 600;
            border: none;
            background: linear-gradient(135deg, #111827, #020617);
            color: #ffffff;            
        }

        .auth-submit-btn:hover {
            filter: brightness(1.05);
        }

        .auth-switch-line {
            margin-top: 10px;
            font-size: 13px;
            text-align: center;
            color: #6b7280;
        }

        .auth-switch-link {
            border: none;
            background: transparent;
            color: #111827;
            font-weight: 600;
            text-decoration: underline;
            cursor: pointer;
            padding: 0 2px;
        }

        .roles-toggle-btn {
            background: transparent;
            border: none;
            margin-top: 18px;
            font-size: 13px;
            text-align: center;
            color: #272424ff;
        }

        .roles-grid {
            max-width: 960px;
            margin: 18px auto 0;
            display: none;
            gap: 16px;
        }

        .roles-grid.visible {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .role-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 16px 18px;
            box-shadow: 0 10px 30px rgba(15,23,42,.12);
        }

        .role-card h3 {
            font-size: 16px;
            margin-bottom: 6px;
        }

        .role-card p {
            font-size: 13px;
            color: #4b5563;
            margin: 0;
        }

        .error-text {
            font-size: 12px;
            color: #dc2626;
            margin-top: 4px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand landing-navbar">
    <div class="container">
        <a class="navbar-brand text-danger fw-bold" href="{{ route('home') }}">
            DisinfoCamp Manager
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#landingNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="landingNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('home') }}">Domů</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Kampaně</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="hero-wrap">
    @include('components.flash-message')
    <h1 class="hero-title">Systém pro správu dezinformačních kampaní</h1>
    <p class="hero-subtitle">
        Plánujte témata, kroky kampaní a spravujte pracovníky, kteří se podílejí na šíření dezinformací.
    </p>

    {{-- LOGIN / REGISTER CARD --}}
    <section class="auth-card">

        {{-- LOGIN FORM --}}
        <form
            id="login-form"
            method="POST"
            action="{{ route('login.post') }}"
            novalidate
            style="{{ $errors->any() && !old('form_mode') ? '' : '' }}"
        >
            @csrf
            <h2>Přihlášení do systému</h2>
            <p class="small">Zadejte svůj e-mail a heslo.</p>

            {{-- obecná chyba z LoginControlleru --}}
            @if ($errors->has('login'))
                <div class="error-text mb-2">
                    {{ $errors->first('login') }}
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="např. worker@disinfo.test"
                >
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Heslo</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                >
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <!-- <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Zapamatovat si mě
                    </label> -->
                </div>
                <!-- <button type="button" class="btn btn-link p-0 small">
                    Zapomenuté heslo
                </button> -->
            </div>

            <button type="submit" class="auth-submit-btn">
                Přihlásit se
            </button>

            <div class="auth-switch-line">
                Nemáte účet?
                <button type="button" class="auth-switch-link" id="show-register">
                    Registrovat účet pracovníka
                </button>
            </div>
        </form>

        <form
            id="register-form"
            method="POST"
            action="{{ route('register') }}"
            style="display:none;"
        >
            @csrf
            <h2>Registrace pracovníka</h2>
            <p class="small">Vyplňte údaje pro vytvoření nového účtu.</p>

            <div class="mb-3">
                <label class="form-label">Jméno</label>
                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                >
                @error('name')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Příjmení</label>
                <input
                    type="text"
                    name="surname"
                    class="form-control"
                    value="{{ old('surname') }}"
                >
                @error('surname')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                >
                @error('email')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Heslo</label>
                <input
                    type="password"
                    name="password"
                    class="form-control"
                >
                @error('password')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Potvrzení hesla</label>
                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                >
                @error('password_confirmation')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="auth-submit-btn">
                Registrovat se
            </button>

            <div class="auth-switch-line">
                Už máte účet?
                <button type="button" class="auth-switch-link" id="show-login">
                    Přihlásit se
                </button>
            </div>
        </form>
    </section>

    {{-- button na zobrazení rolí --}}
    <button type="button" class="roles-toggle-btn" id="toggle-roles">
        Zobrazit role v systému
    </button>

    <section id="roles-section" class="roles-grid">
        <article class="role-card">
            <h3>Administrátor</h3>
            <p>Spravuje uživatele, role, témata a přiřazuje správce kampaní.</p>
        </article>
        <article class="role-card">
            <h3>Správce kampaně</h3>
            <p>Definuje kroky kampaně, přiděluje koordinátory a mění stav kampaně.</p>
        </article>
        <article class="role-card">
            <h3>Koordinátor</h3>
            <p>Spravuje aktivity v jednotlivých krocích kampaně a pracovníky.</p>
        </article>
        <article class="role-card">
            <h3>Pracovník</h3>
            <p>Hlásí se na aktivity, provádí je a vyplňuje úspěšnost.</p>
        </article>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const showRegisterBtn = document.getElementById('show-register');
    const showLoginBtn = document.getElementById('show-login');
    const rolesToggleBtn = document.getElementById('toggle-roles');
    const rolesSection = document.getElementById('roles-section');
    

    if (showRegisterBtn) {
        showRegisterBtn.addEventListener('click', () => {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        });
    }


    if(showLoginBtn){
        showLoginBtn.addEventListener('click', () => {
            registerForm.style.display = 'none';
            loginForm.style.display = 'block';
        })
    }

    if (rolesToggleBtn) {
        rolesToggleBtn.addEventListener('click', () => {
            rolesSection.classList.toggle('visible');
        });
    }
</script>

</body>
</html>
