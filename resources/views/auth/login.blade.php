<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Sistem</title>
    <style>
        :root {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --espresso: #352016;
            --coffee: #6f452b;
            --caramel: #b77a43;
            --cream: #fff6e8;
            --sage: #49664e;
            --ink: #2b1c15;
        }

        * { box-sizing: border-box; }
        html, body, * { scrollbar-width: none; -ms-overflow-style: none; }
        *::-webkit-scrollbar { display: none; width: 0; height: 0; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background:
                linear-gradient(135deg, rgba(53, 32, 22, .82), rgba(111, 69, 43, .9)),
                repeating-linear-gradient(45deg, rgba(255, 246, 232, .08) 0 1px, transparent 1px 14px),
                #6f452b;
            color: var(--ink);
            padding: 28px;
        }

        .shell {
            width: min(900px, 100%);
            min-height: 520px;
            display: grid;
            grid-template-columns: minmax(260px, .9fr) minmax(320px, 1fr);
            overflow: hidden;
            border-radius: 8px;
            background: var(--cream);
            box-shadow: 0 28px 80px rgba(24, 13, 7, .38);
            border: 1px solid rgba(255, 246, 232, .48);
        }

        .brand-panel {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 34px;
            color: #fff8ed;
            background:
                linear-gradient(160deg, rgba(53, 32, 22, .18), rgba(53, 32, 22, .74)),
                linear-gradient(135deg, var(--caramel), var(--coffee) 58%, var(--espresso));
        }

        .brand-panel::after {
            content: "";
            position: absolute;
            inset: auto 30px 30px auto;
            width: 124px;
            height: 124px;
            border: 1px solid rgba(255, 246, 232, .28);
            transform: rotate(12deg);
        }

        .mark {
            width: 58px;
            height: 58px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            overflow: hidden;
            background: var(--cream);
            border: 1px solid rgba(255, 246, 232, .28);
        }

        .mark img {
            width: 100%;
            height: 100%;
            display: block;
            border-radius: inherit;
            object-fit: cover;
        }

        .brand-copy {
            position: relative;
            z-index: 1;
            display: grid;
            gap: 12px;
        }

        .brand-copy p,
        .footnote,
        h1,
        h2,
        p {
            margin: 0;
        }

        .brand-copy h1 {
            color: #fff8ed;
            font-size: 42px;
            line-height: 1.02;
        }

        .brand-copy p {
            max-width: 280px;
            color: rgba(255, 248, 237, .78);
            line-height: 1.6;
        }

        .footnote {
            position: relative;
            z-index: 1;
            color: rgba(255, 248, 237, .72);
            font-size: 13px;
            font-weight: 700;
        }

        .form-panel {
            display: grid;
            align-content: center;
            padding: 46px 48px;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, .62), rgba(255, 246, 232, .88)),
                var(--cream);
        }

        .form-heading {
            display: grid;
            gap: 8px;
            margin-bottom: 28px;
        }

        .eyebrow {
            color: var(--sage);
            font-size: 12px;
            font-weight: 900;
            text-transform: uppercase;
        }

        .form-heading h2 {
            color: var(--espresso);
            font-size: 34px;
            line-height: 1.1;
        }

        .form-heading p {
            color: #7a5a46;
            line-height: 1.5;
        }

        form {
            display: grid;
            gap: 16px;
        }

        label {
            display: grid;
            gap: 7px;
            color: var(--espresso);
            font-size: 13px;
            font-weight: 900;
        }

        input {
            width: 100%;
            border: 1px solid #d8b893;
            border-radius: 7px;
            padding: 13px 14px;
            background: #fffaf2;
            color: var(--espresso);
            font: inherit;
            font-weight: 700;
            caret-color: var(--coffee);
            transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        }

        input:focus {
            outline: 0;
            border-color: var(--coffee);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(183, 122, 67, .18);
        }

        .password-field {
            position: relative;
        }

        .password-field input {
            padding-right: 48px;
        }

        .toggle-password {
            position: absolute;
            right: 9px;
            top: 50%;
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border: 0;
            border-radius: 7px;
            transform: translateY(-50%);
            background: transparent;
            color: var(--coffee);
            cursor: pointer;
            padding: 0;
        }

        .toggle-password:hover {
            background: rgba(111, 69, 43, .1);
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
        }

        .toggle-password .eye-off { display: none; }
        .toggle-password.is-visible .eye { display: none; }
        .toggle-password.is-visible .eye-off { display: block; }

        .login-button {
            width: 100%;
            display: grid;
            place-items: center;
            border: 0;
            border-radius: 7px;
            background: linear-gradient(135deg, var(--coffee), var(--espresso));
            color: #fff8ed;
            padding: 14px;
            font: inherit;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 14px 26px rgba(53, 32, 22, .22);
        }

        .login-button:hover {
            filter: brightness(1.04);
        }

        .notice {
            padding: 12px 13px;
            border-radius: 7px;
            margin-bottom: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: opacity .18s ease, transform .18s ease;
        }

        .notice.is-hiding {
            opacity: 0;
            transform: translateY(-4px);
        }

        .error {
            background: #fff0e8;
            color: #8a341b;
            border: 1px solid #e6b292;
        }

        .success {
            background: #edf5e8;
            color: #355b28;
            border: 1px solid #c5ddb7;
        }

        @media (max-width: 780px) {
            body {
                align-items: start;
                padding: 18px;
            }

            .shell {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .brand-panel {
                min-height: 220px;
                padding: 26px;
            }

            .brand-copy h1 {
                font-size: 34px;
            }

            .form-panel {
                padding: 30px 24px;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <section class="brand-panel" aria-label="SwiftBite">
            <div class="mark">
                <img src="{{ asset('images/Swiftbite.png') }}" alt="SwiftBite">
            </div>
            <div class="brand-copy">
                <h1>SwiftBite</h1>
                <p>Platform kasir dan pemesanan digital restoran.</p>
            </div>
            <p class="footnote">QR table ordering system</p>
        </section>

        <section class="form-panel">
            <div class="form-heading">
                <h2>Masuk akun</h2>
                <p>Masuk untuk mulai mengelola pesanan.</p>
            </div>

            @if (session('success'))
                <div class="notice success" role="button" tabindex="0" aria-label="Tutup notifikasi">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="notice error" role="button" tabindex="0" aria-label="Tutup notifikasi">{{ $errors->first() }}</div>
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
        </section>
    </main>

    <script>
        const togglePassword = document.querySelector('.toggle-password');
        const passwordInput = document.querySelector('#password');
        const notices = document.querySelectorAll('.notice');

        togglePassword.addEventListener('click', () => {
            const isHidden = passwordInput.type === 'password';

            passwordInput.type = isHidden ? 'text' : 'password';
            togglePassword.classList.toggle('is-visible', isHidden);
            togglePassword.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Lihat password');
        });

        notices.forEach((notice) => {
            const dismiss = () => {
                notice.classList.add('is-hiding');
                setTimeout(() => notice.remove(), 180);
            };

            notice.addEventListener('click', dismiss);
            notice.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    dismiss();
                }
            });
        });
    </script>
</body>
</html>
