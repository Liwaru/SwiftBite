<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Sistem</title>
    <style>
        :root { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        :root {
            --brand-red: #d90416;
            --brand-red-dark: #a90010;
            --brand-red-soft: #fff0f1;
            --text-red: #8f0010;
        }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background:
                radial-gradient(circle at 18% 16%, rgba(255, 255, 255, .22), transparent 26%),
                linear-gradient(135deg, #ff2a2a 0%, #e50914 48%, #b80012 100%);
            color: var(--text-red);
        }
        .card { width: min(390px, calc(100vw - 32px)); background: #ffffff; border-radius: 8px; padding: 30px; box-shadow: 0 26px 70px rgba(86, 0, 0, .26); box-sizing: border-box; border: 1px solid rgba(255, 255, 255, .65); }
        h1, p { margin: 0; }
        h1 { font-size: 38px; margin-top: 10px; margin-bottom: 28px; color: var(--brand-red-dark); text-align: center; }
        p { color: #b33a43; margin-bottom: 24px; }
        form { display: grid; gap: 16px; }
        label { display: grid; gap: 7px; font-size: 13px; font-weight: 800; color: var(--brand-red-dark); }
        input { width: 100%; box-sizing: border-box; border: 1px solid #f0a7ad; border-radius: 7px; padding: 12px; font: inherit; color: var(--brand-red-dark); background: var(--brand-red-soft); caret-color: var(--brand-red); font-weight: 700; }
        input::placeholder { color: #c75b65; }
        input:focus { outline: 3px solid rgba(217, 4, 22, .16); border-color: var(--brand-red); background: #ffffff; }
        .password-field { position: relative; }
        .password-field input { padding-right: 46px; }
        .toggle-password { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: grid; place-items: center; width: 32px; height: 32px; border: 0; border-radius: 7px; background: transparent; color: var(--brand-red-dark); padding: 0; cursor: pointer; }
        .toggle-password:hover { background: rgba(217, 4, 22, .08); }
        .toggle-password svg { width: 20px; height: 20px; stroke: currentColor; }
        .toggle-password .eye-off { display: none; }
        .toggle-password.is-visible .eye { display: none; }
        .toggle-password.is-visible .eye-off { display: block; }
        .login-button { width: 100%; display: grid; place-items: center; border: 0; border-radius: 7px; background: linear-gradient(135deg, var(--brand-red), var(--brand-red-dark)); color: #ffffff; padding: 13px 14px; font: inherit; font-weight: 900; cursor: pointer; box-shadow: 0 10px 22px rgba(217, 4, 22, .22); }
        .login-button:hover { filter: brightness(1.04); }
        .notice { padding: 11px 12px; border-radius: 7px; margin-bottom: 14px; font-weight: 700; }
        .error { background: #fff0f0; color: #b00000; border: 1px solid #ffc5c5; }
        .success { background: #edf9ef; color: #1c6b31; border: 1px solid #bfe7c7; }
    </style>
</head>
<body>
    <main class="card">
        <h1>Login</h1>

        @if (session('success'))
            <div class="notice success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="notice error">{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ route('login.store') }}">
            @csrf
            <label>
                Username
                <input name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>
            </label>
            <label>
                Password
                <span class="password-field">
                    <input id="password" name="password" type="password" autocomplete="current-password" required>
                    <button class="toggle-password" type="button" aria-label="Lihat password" aria-controls="password">
                        <svg class="eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.6 10.6A3 3 0 0 0 13.4 13.4" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 6.9C3.8 8.8 2.25 12 2.25 12S6 18.75 12 18.75c1.8 0 3.4-.6 4.8-1.4" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.7 5.6c.7-.2 1.5-.35 2.3-.35 6 0 9.75 6.75 9.75 6.75a17.6 17.6 0 0 1-2.7 3.4" />
                        </svg>
                    </button>
                </span>
            </label>
            <button class="login-button" type="submit">Masuk</button>
        </form>
    </main>
    <script>
        const togglePassword = document.querySelector('.toggle-password');
        const passwordInput = document.querySelector('#password');

        togglePassword.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';
            togglePassword.classList.toggle('is-visible', isHidden);
            togglePassword.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Lihat password');
        });
    </script>
</body>
</html>
