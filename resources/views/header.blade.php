<style>
    :root {
        --sidebar-red: #d90416;
        --sidebar-red-dark: #a90010;
        --sidebar-red-light: #ff2a2a;
    }

    .app-shell {
        min-height: 100vh;
        background: #ffffff;
    }

    .sidebar {
        position: fixed;
        inset: 0 auto 0 0;
        z-index: 1000;
        width: 260px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        padding: 24px 18px;
        overflow: hidden;
        background:
            radial-gradient(circle at 18% 12%, rgba(255, 255, 255, .2), transparent 28%),
            linear-gradient(160deg, var(--sidebar-red-light) 0%, var(--sidebar-red) 48%, var(--sidebar-red-dark) 100%);
        color: #ffffff;
        box-shadow: 14px 0 36px rgba(169, 0, 16, .16);
        transition: width .28s ease, padding .28s ease, box-shadow .28s ease;
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 20px;
        margin-bottom: 18px;
        border-bottom: 1px solid rgba(255, 255, 255, .18);
        min-width: 0;
    }

    .sidebar-logo {
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 12px;
        background: rgba(255, 255, 255, .18);
        color: #ffffff;
        font-size: 15px;
        font-weight: 900;
    }

    .sidebar-brand-text {
        min-width: 0;
        flex: 1 1 auto;
    }

    .sidebar-brand-text strong,
    .sidebar-brand-text span {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-brand-text strong {
        font-size: 22px;
        line-height: 1.15;
    }

    .sidebar-brand-text span {
        margin-top: 6px;
        color: rgba(255, 255, 255, .82);
        font-size: 13px;
        font-weight: 700;
    }

    .sidebar-toggle {
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        border: 0;
        border-radius: 12px;
        background: rgba(255, 255, 255, .16);
        color: #ffffff;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
    }

    .sidebar-toggle:hover {
        background: rgba(255, 255, 255, .26);
        transform: translateY(-1px);
    }

    .sidebar-toggle span {
        position: relative;
        display: block;
        width: 18px;
        height: 2px;
        background: currentColor;
        border-radius: 999px;
    }

    .sidebar-toggle span::before,
    .sidebar-toggle span::after {
        content: "";
        position: absolute;
        left: 0;
        width: 18px;
        height: 2px;
        background: currentColor;
        border-radius: 999px;
    }

    .sidebar-toggle span::before {
        top: -6px;
    }

    .sidebar-toggle span::after {
        top: 6px;
    }

    .sidebar-nav {
        display: grid;
        gap: 8px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        padding: 12px 13px;
        border-radius: 8px;
        color: #ffffff;
        text-decoration: none;
        font-weight: 900;
        background: rgba(255, 255, 255, .1);
        white-space: nowrap;
        overflow: hidden;
        transition: background .2s ease, color .2s ease;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
        background: #ffffff;
        color: var(--sidebar-red-dark);
    }

    .sidebar-icon {
        flex: 0 0 24px;
        width: 24px;
        height: 24px;
        display: grid;
        place-items: center;
        font-size: 13px;
        font-weight: 900;
        line-height: 1;
    }

    .sidebar-label {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-footer {
        margin-top: auto;
        display: grid;
        gap: 10px;
    }

    .sidebar-user {
        padding: 12px 13px;
        border-radius: 8px;
        background: rgba(255, 255, 255, .14);
        font-weight: 800;
    }

    .sidebar-user span {
        display: block;
        color: rgba(255, 255, 255, .78);
        font-size: 12px;
        margin-bottom: 3px;
    }

    .sidebar-logout {
        width: 100%;
        border: 0;
        border-radius: 8px;
        background: #ffffff;
        color: var(--sidebar-red-dark);
        padding: 11px 13px;
        font: inherit;
        font-weight: 900;
        cursor: pointer;
    }

    .content-with-sidebar {
        margin-left: 260px;
        min-height: 100vh;
        box-sizing: border-box;
        transition: margin-left .28s ease;
    }

    .app-shell.sidebar-collapsed .sidebar {
        width: 88px;
        padding-inline: 14px;
        box-shadow: 10px 0 24px rgba(169, 0, 16, .14);
    }

    .app-shell.sidebar-collapsed .content-with-sidebar {
        margin-left: 88px;
    }

    .app-shell.sidebar-collapsed .sidebar-brand {
        justify-content: center;
        padding-bottom: 18px;
    }

    .app-shell.sidebar-collapsed .sidebar-logo,
    .app-shell.sidebar-collapsed .sidebar-brand-text,
    .app-shell.sidebar-collapsed .sidebar-footer,
    .app-shell.sidebar-collapsed .sidebar-label {
        display: none;
    }

    .app-shell.sidebar-collapsed .sidebar-toggle {
        margin-left: 0;
    }

    .app-shell.sidebar-collapsed .sidebar-nav {
        margin-top: 8px;
    }

    .app-shell.sidebar-collapsed .sidebar-link {
        justify-content: center;
        padding: 12px 0;
        border-radius: 12px;
    }

    .app-shell.sidebar-collapsed .sidebar-icon {
        flex-basis: auto;
        width: 24px;
    }

    @media (max-width: 760px) {
        .sidebar {
            position: static;
            width: 100%;
            min-height: auto;
        }

        .sidebar-toggle {
            display: none;
        }

        .content-with-sidebar,
        .app-shell.sidebar-collapsed .content-with-sidebar {
            margin-left: 0;
        }

        .app-shell.sidebar-collapsed .sidebar {
            width: 100%;
        }
    }
</style>

<aside class="sidebar">
    <div class="sidebar-brand">
        <span class="sidebar-logo">QB</span>
        <div class="sidebar-brand-text">
            <strong>QuickBite</strong>
            <span>Sistem Pesanan QR</span>
        </div>
        <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Toggle sidebar" aria-expanded="true">
            <span></span>
        </button>
    </div>

    <nav class="sidebar-nav">
        @if ((int) session('auth_level') === 1)
            <a class="sidebar-link {{ request()->routeIs('customer.home') ? 'active' : '' }}" href="{{ route('customer.home') }}" title="Dashboard Pelanggan">
                <span class="sidebar-icon">DP</span>
                <span class="sidebar-label">Dashboard Pelanggan</span>
            </a>
        @elseif ((int) session('auth_level') === 2)
            <a class="sidebar-link {{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}" href="{{ route('cashier.dashboard') }}" title="Pesanan">
                <span class="sidebar-icon">PS</span>
                <span class="sidebar-label">Pesanan</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('cashier.history') ? 'active' : '' }}" href="{{ route('cashier.history') }}" title="Riwayat Pesanan">
                <span class="sidebar-icon">RP</span>
                <span class="sidebar-label">Riwayat Pesanan</span>
            </a>
        @elseif ((int) session('auth_level') === 3)
            <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" title="Dashboard Admin">
                <span class="sidebar-icon">DA</span>
                <span class="sidebar-label">Dashboard Admin</span>
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <span>
                @if ((int) session('auth_level') === 3)
                    Admin
                @elseif ((int) session('auth_level') === 2)
                    Kasir
                @else
                    Pelanggan
                @endif
            </span>
            {{ session('auth_name') }}
        </div>
        <form method="post" action="{{ route('logout') }}">
            @csrf
            <button class="sidebar-logout" type="submit">Logout</button>
        </form>
    </div>
</aside>

<script>
    (function () {
        function initSidebarToggle() {
            const toggleButton = document.getElementById('sidebarToggle');
            const appShell = toggleButton ? toggleButton.closest('.app-shell') : null;

            if (!appShell || !toggleButton || window.innerWidth <= 760) {
                return;
            }

            toggleButton.addEventListener('click', function () {
                appShell.classList.toggle('sidebar-collapsed');
                const expanded = !appShell.classList.contains('sidebar-collapsed');
                toggleButton.setAttribute('aria-expanded', String(expanded));
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebarToggle, { once: true });
        } else {
            initSidebarToggle();
        }
    })();
</script>
