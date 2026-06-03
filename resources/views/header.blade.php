<style>
    :root {
        --sidebar-red: #5a321f;
        --sidebar-red-dark: #27140d;
        --sidebar-red-light: #9a6239;
        --page-cream: #fff6e8;
    }

    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    html,
    body,
    * {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    *::-webkit-scrollbar {
        display: none;
        width: 0;
        height: 0;
    }

    .app-shell {
        width: 100%;
        min-width: 0;
        min-height: 100vh;
        overflow-x: hidden;
        background:
            linear-gradient(135deg, rgba(53, 32, 22, .82), rgba(111, 69, 43, .9)),
            repeating-linear-gradient(45deg, rgba(255, 246, 232, .08) 0 1px, transparent 1px 14px),
            #6f452b;
    }

    .mobile-appbar,
    .mobile-sidebar-backdrop {
        display: none;
    }

    .mobile-appbar {
        position: fixed;
        inset: 0 0 auto 0;
        z-index: 1100;
        height: 72px;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 12px 16px;
        background:
            radial-gradient(circle at 12% 10%, rgba(255, 246, 232, .14), transparent 30%),
            linear-gradient(135deg, var(--sidebar-red-light), var(--sidebar-red-dark));
        color: #fff8ed;
        box-shadow: 0 12px 30px rgba(24, 13, 7, .22);
    }

    .mobile-appbar-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
        color: inherit;
        text-decoration: none;
        font-weight: 900;
    }

    .mobile-appbar-logo {
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        border-radius: 9px;
        background: rgba(255, 246, 232, .2);
        color: #fff8ed;
        font-size: 14px;
        font-weight: 900;
    }

    .mobile-appbar-title {
        overflow: hidden;
        font-size: 20px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .mobile-menu-toggle {
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 12px;
        background: rgba(255, 246, 232, .18);
        color: #fff8ed;
        cursor: pointer;
    }

    .mobile-sidebar-backdrop {
        position: fixed;
        inset: 0;
        z-index: 1050;
        background: rgba(24, 13, 7, .48);
        opacity: 0;
        pointer-events: none;
        transition: opacity .22s ease;
    }

    .sidebar {
        position: fixed;
        inset: 0 auto 0 0;
        z-index: 1000;
        width: 260px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        padding: 26px 18px 28px;
        overflow-x: hidden;
        overflow-y: auto;
        overscroll-behavior: contain;
        background:
            radial-gradient(circle at 18% 12%, rgba(255, 246, 232, .14), transparent 28%),
            linear-gradient(180deg, var(--sidebar-red-light) 0%, var(--sidebar-red) 38%, var(--sidebar-red-dark) 100%);
        color: #fff8ed;
        box-shadow: 14px 0 36px rgba(24, 13, 7, .34);
        transition: width .28s ease, padding .28s ease, box-shadow .28s ease;
        -webkit-user-select: none;
        user-select: none;
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 20px;
        margin-bottom: 22px;
        border-bottom: 1px solid rgba(255, 246, 232, .18);
        min-width: 0;
        flex: 0 0 auto;
    }

    .sidebar-brand-link {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        flex: 1 1 auto;
        color: inherit;
        text-decoration: none;
    }

    .sidebar-logo {
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        display: grid;
        place-items: center;
        border-radius: 9px;
        background: rgba(255, 246, 232, .2);
        color: #fff8ed;
        font-size: 15px;
        font-weight: 900;
    }

    .sidebar-brand-text {
        min-width: 0;
        flex: 1 1 auto;
        text-align: left;
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
        color: rgba(255, 248, 237, .82);
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
        background: rgba(255, 246, 232, .18);
        color: #fff8ed;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
    }

    .sidebar-toggle:hover {
        background: rgba(255, 246, 232, .26);
    }

    .hamburger-icon {
        width: 21px;
        display: grid;
        gap: 4px;
    }

    .hamburger-icon span {
        display: block;
        width: 21px;
        height: 2px;
        background: currentColor;
        border-radius: 2px;
    }

    .sidebar-nav {
        display: grid;
        gap: 10px;
        flex: 0 0 auto;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 0;
        padding: 13px 14px;
        border-radius: 9px;
        color: #fff8ed;
        text-decoration: none;
        font-weight: 900;
        background: rgba(255, 246, 232, .09);
        white-space: nowrap;
        overflow: hidden;
        transition: background .2s ease, color .2s ease;
    }

    .sidebar-link:hover,
    .sidebar-link.active {
        background: #fff6e8;
        color: var(--sidebar-red-dark);
    }

    .sidebar-link:hover .sidebar-icon,
    .sidebar-link.active .sidebar-icon {
        border-color: rgba(39, 20, 13, .2);
        color: var(--sidebar-red-dark);
    }

    .sidebar-icon {
        flex: 0 0 30px;
        width: 30px;
        height: 30px;
        display: grid;
        place-items: center;
        border: 1.5px solid rgba(255, 248, 237, .48);
        border-radius: 7px;
        color: #fff8ed;
    }

    .sidebar-icon svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
        stroke-width: 2.35;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .sidebar-label {
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar-group {
        display: grid;
        gap: 8px;
    }

    .sidebar-group summary {
        list-style: none;
    }

    .sidebar-group summary::-webkit-details-marker {
        display: none;
    }

    .sidebar-group-toggle {
        cursor: pointer;
    }

    .sidebar-caret {
        margin-left: auto;
        color: currentColor;
        font-size: 12px;
        transition: transform .2s ease;
    }

    .sidebar-group[open] .sidebar-caret {
        transform: rotate(180deg);
    }

    .sidebar-subnav {
        display: none;
        gap: 8px;
        max-height: none;
        overflow: visible;
        padding-left: 12px;
        opacity: 1;
        transform: none;
        transition: none;
    }

    .sidebar-group[open] .sidebar-subnav {
        display: grid;
    }

    .app-shell:not(.sidebar-collapsed) .sidebar-group[open] .sidebar-subnav {
        display: grid;
        max-height: none;
        overflow: visible;
        opacity: 1;
        transform: none;
    }

    .app-shell:not(.sidebar-collapsed) .sidebar-subnav .sidebar-icon {
        display: grid;
        visibility: visible;
        opacity: 1;
    }

    .app-shell:not(.sidebar-collapsed) .sidebar-subnav .sidebar-label {
        display: block;
        visibility: visible;
        opacity: 1;
    }

    .app-shell.sidebar-collapsed .sidebar-subnav {
        display: none !important;
    }

    .sidebar-subnav .sidebar-link {
        padding: 10px 12px;
        background: rgba(255, 246, 232, .07);
    }

    .sidebar-subnav .sidebar-link:hover,
    .sidebar-subnav .sidebar-link.active {
        background: #fff6e8;
        color: var(--sidebar-red-dark);
    }

    .sidebar-subnav .sidebar-link:hover .sidebar-icon,
    .sidebar-subnav .sidebar-link.active .sidebar-icon {
        border-color: rgba(39, 20, 13, .2);
        color: var(--sidebar-red-dark);
    }

    .sidebar-footer {
        margin-top: auto;
        display: grid;
        gap: 10px;
        padding-top: 22px;
        flex: 0 0 auto;
    }

    .sidebar-account {
        position: relative;
    }

    .sidebar-user-info {
        width: 100%;
        display: grid;
        grid-template-columns: 38px 1fr;
        gap: 11px;
        align-items: center;
        padding: 12px;
        border-radius: 8px;
        background: rgba(255, 246, 232, .1);
        border: 1px solid rgba(255, 246, 232, .12);
        color: #fff8ed;
        font: inherit;
        text-align: left;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
    }

    .sidebar-user-info:hover,
    .sidebar-account.open .sidebar-user-info {
        background: rgba(255, 246, 232, .15);
        border-color: rgba(255, 246, 232, .22);
    }

    .sidebar-avatar,
    .account-menu-avatar {
        display: grid;
        place-items: center;
        border-radius: 8px;
        background: rgba(255, 246, 232, .18);
        color: #fff8ed;
        font-weight: 900;
    }

    .sidebar-avatar {
        width: 38px;
        height: 38px;
    }

    .sidebar-user-details {
        min-width: 0;
        display: grid;
        gap: 3px;
    }

    .sidebar-user-name {
        overflow: hidden;
        color: #fff8ed;
        font-weight: 900;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sidebar-user-role {
        display: block;
        color: rgba(255, 248, 237, .78);
        font-size: 12px;
        font-weight: 800;
    }

    .account-menu {
        position: absolute;
        left: 0;
        right: 0;
        bottom: calc(100% + 10px);
        z-index: 3;
        display: none;
        overflow: hidden;
        border: 1px solid #e1ad73;
        border-radius: 8px;
        background: #fff6e8;
        box-shadow: 0 18px 38px rgba(39, 20, 13, .24);
        color: #2b1c15;
    }

    .sidebar-account.open .account-menu {
        display: block;
    }

    .account-menu-head {
        display: grid;
        grid-template-columns: 40px 1fr;
        gap: 11px;
        align-items: center;
        padding: 13px;
        border-bottom: 1px solid #ead4ba;
    }

    .account-menu-avatar {
        width: 40px;
        height: 40px;
        background: #6f452b;
    }

    .account-menu-name {
        overflow: hidden;
        font-weight: 900;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .account-menu-role {
        color: #7a5a46;
        font-size: 12px;
        font-weight: 800;
    }

    .account-menu-main {
        display: grid;
        gap: 4px;
        padding: 8px;
    }

    .account-menu-link,
    .account-menu-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
        border: 0;
        border-radius: 7px;
        background: transparent;
        color: #352016;
        padding: 10px;
        font: inherit;
        font-weight: 900;
        text-align: left;
        text-decoration: none;
        cursor: pointer;
    }

    .account-menu-link:hover,
    .account-menu-btn:hover {
        background: #f4e3cd;
    }

    .account-menu-icon {
        width: 18px;
        height: 18px;
        stroke: currentColor;
    }

    .account-menu-logout {
        margin: 0;
    }

    .sidebar-logout {
        width: 100%;
        border: 1px solid rgba(255, 246, 232, .74);
        border-radius: 8px;
        background: rgba(255, 246, 232, .9);
        color: var(--sidebar-red-dark);
        padding: 11px 13px;
        font: inherit;
        font-weight: 900;
        cursor: pointer;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .sidebar-logout:hover {
        background: transparent;
        border-color: rgba(255, 246, 232, .86);
        color: #fff8ed;
    }

    .content-with-sidebar {
        margin-left: 260px;
        width: calc(100% - 260px);
        min-width: 0;
        min-height: 100vh;
        overflow-x: hidden;
        background: #ffffff;
        transition: margin-left .28s ease;
    }

    .content-with-sidebar > main {
        width: 100% !important;
        max-width: none !important;
        min-width: 0;
        box-sizing: border-box;
    }

    .content-with-sidebar > main > * {
        max-width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }

    .content-with-sidebar .panel,
    .content-with-sidebar .hero-card,
    .content-with-sidebar .stat-card,
    .content-with-sidebar .summary-card,
    .content-with-sidebar .filter-card,
    .content-with-sidebar .table-card,
    .content-with-sidebar .stats,
    .content-with-sidebar .report-grid,
    .content-with-sidebar .dashboard-grid,
    .content-with-sidebar .summary-grid,
    .content-with-sidebar .report-column,
    .content-with-sidebar .page-shell,
    .content-with-sidebar .table-wrap,
    .content-with-sidebar .history-table-wrap,
    .content-with-sidebar .summary-panel {
        width: 100%;
        max-width: none;
        min-width: 0;
        box-sizing: border-box;
    }

    .content-with-sidebar .stats,
    .content-with-sidebar .report-grid,
    .content-with-sidebar .dashboard-grid,
    .content-with-sidebar .summary-grid {
        min-width: 0;
    }

    .content-with-sidebar .panel,
    .content-with-sidebar .stat-card,
    .content-with-sidebar .summary-card,
    .content-with-sidebar .filter-card,
    .content-with-sidebar .table-card {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .content-with-sidebar .table-wrap table,
    .content-with-sidebar .history-table,
    .content-with-sidebar .summary-table {
        width: 100%;
        min-width: max(100%, 680px);
    }

    .app-shell.sidebar-collapsed .sidebar {
        width: 88px;
        padding-inline: 14px;
        box-shadow: 10px 0 24px rgba(24, 13, 7, .2);
    }

    .app-shell.sidebar-collapsed .content-with-sidebar {
        margin-left: 88px;
        width: calc(100% - 88px);
    }

    .app-shell.sidebar-collapsed .content-with-sidebar > main {
        width: 100%;
        max-width: none;
    }

    .app-shell.sidebar-collapsed .sidebar-brand {
        display: grid;
        place-items: center;
        padding-bottom: 18px;
    }

    .app-shell.sidebar-collapsed .sidebar-brand-link {
        flex: 0 0 auto;
    }

    .app-shell.sidebar-collapsed .sidebar-brand-link,
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
        border-radius: 9px;
    }

    .app-shell.sidebar-collapsed .sidebar-group-toggle {
        justify-content: center;
    }

    .app-shell.sidebar-collapsed .sidebar-caret {
        display: none;
    }

    .app-shell.sidebar-collapsed .sidebar-icon {
        flex-basis: auto;
    }

    .app-shell.sidebar-state-loading .sidebar,
    .app-shell.sidebar-state-loading .content-with-sidebar {
        transition: none;
    }

    @media (max-width: 860px) {
        .mobile-appbar {
            display: flex;
        }

        .mobile-sidebar-backdrop {
            display: block;
        }

        .app-shell.sidebar-mobile-open .mobile-sidebar-backdrop {
            opacity: 1;
            pointer-events: auto;
        }

        .sidebar {
            z-index: 1200;
            width: min(82vw, 290px);
            max-width: 290px;
            padding: 24px 18px 26px;
            transform: translateX(-105%);
            transition: transform .26s ease, box-shadow .26s ease;
        }

        .app-shell.sidebar-mobile-open .sidebar {
            transform: translateX(0);
            box-shadow: 18px 0 44px rgba(24, 13, 7, .36);
        }

        .sidebar-toggle {
            display: none;
        }

        .content-with-sidebar,
        .app-shell.sidebar-collapsed .content-with-sidebar {
            margin-left: 0;
            width: 100%;
            padding-top: 72px;
        }

        .content-with-sidebar .table-wrap table,
        .content-with-sidebar .history-table,
        .content-with-sidebar .summary-table {
            min-width: 680px;
        }

        .app-shell.sidebar-collapsed .content-with-sidebar > main {
            max-width: none;
        }

        .app-shell.sidebar-collapsed .sidebar {
            width: min(82vw, 290px);
            padding-inline: 18px;
        }

        .app-shell.sidebar-collapsed .sidebar-brand {
            display: flex;
            place-items: initial;
        }

        .app-shell.sidebar-collapsed .sidebar-brand-link,
        .app-shell.sidebar-collapsed .sidebar-logo,
        .app-shell.sidebar-collapsed .sidebar-brand-text,
        .app-shell.sidebar-collapsed .sidebar-footer,
        .app-shell.sidebar-collapsed .sidebar-label {
            display: flex;
        }

        .app-shell.sidebar-collapsed .sidebar-brand-text,
        .app-shell.sidebar-collapsed .sidebar-footer,
        .app-shell.sidebar-collapsed .sidebar-label {
            display: block;
        }

        .app-shell.sidebar-collapsed .sidebar-link,
        .app-shell.sidebar-collapsed .sidebar-group-toggle {
            justify-content: flex-start;
            padding: 13px 14px;
        }

        .app-shell.sidebar-collapsed .sidebar-caret {
            display: inline;
        }

        .app-shell.sidebar-collapsed .sidebar-subnav {
            display: grid !important;
        }

        .app-shell:not(.sidebar-mobile-open) .sidebar {
            box-shadow: none;
        }
    }
</style>

<script>
    (function () {
        const appShell = document.currentScript.parentElement;

        if (
            appShell
            && window.innerWidth > 860
            && localStorage.getItem('swiftbite.sidebarCollapsed') === 'true'
        ) {
            appShell.classList.add('sidebar-collapsed', 'sidebar-state-loading');
        }
    })();
</script>

@php
    $authLevel = (int) session('auth_level');
    $authName = session('auth_name', 'User');
    $roleName = match ($authLevel) {
        5 => 'Owner',
        4 => 'Manager',
        3 => 'Cashier',
        2 => 'Chef',
        1 => 'Waiter',
        default => 'Customer',
    };
    $dashboardRoute = match ($authLevel) {
        5 => route('owner.dashboard'),
        4 => route('manager.dashboard'),
        3 => route('cashier.dashboard'),
        2 => route('chef.dashboard'),
        1 => route('waiter.dashboard'),
        default => route('customer.home'),
    };
@endphp

<header class="mobile-appbar">
    <a class="mobile-appbar-brand" href="{{ $dashboardRoute }}" aria-label="Buka dashboard">
        <span class="mobile-appbar-logo">SB</span>
        <span class="mobile-appbar-title">SwiftBite</span>
    </a>
    <button class="mobile-menu-toggle" id="mobileMenuToggle" type="button" aria-label="Buka navigasi" aria-expanded="false" aria-controls="sidebarNavigation">
        <span class="hamburger-icon" aria-hidden="true">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </button>
</header>
<div class="mobile-sidebar-backdrop" id="mobileSidebarBackdrop" aria-hidden="true"></div>

<aside class="sidebar" id="sidebarNavigation">
    <div class="sidebar-brand">
        <a class="sidebar-brand-link" href="{{ $dashboardRoute }}" aria-label="Buka dashboard">
            <span class="sidebar-logo">SB</span>
            <div class="sidebar-brand-text">
                <strong>SwiftBite</strong>
            </div>
        </a>
        <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Toggle sidebar" aria-expanded="true">
            <span class="hamburger-icon" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
    </div>

    <nav class="sidebar-nav">
        @if ($authLevel === 0)
            <a class="sidebar-link {{ request()->routeIs('customer.home') ? 'active' : '' }}" href="{{ route('customer.home') }}" title="Dashboard Pelanggan">
                <span class="sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M3 11.5 12 4l9 7.5" />
                        <path d="M5 10.5V20h14v-9.5" />
                        <path d="M9 20v-6h6v6" />
                    </svg>
                </span>
                <span class="sidebar-label">Dashboard Pelanggan</span>
            </a>
        @endif

        @if ($authLevel === 1)
            <a class="sidebar-link {{ request()->routeIs('waiter.dashboard') ? 'active' : '' }}" href="{{ route('waiter.dashboard') }}" title="Pesanan Antar">
                <span class="sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M6 4h12v6H6V4Z" />
                        <path d="M4 14h16" />
                        <path d="M7 14v6M17 14v6" />
                        <path d="M9 7h6" />
                    </svg>
                </span>
                <span class="sidebar-label">Pesanan Antar</span>
            </a>
        @endif

        @if ($authLevel === 2)
            <a class="sidebar-link {{ request()->routeIs('chef.dashboard') ? 'active' : '' }}" href="{{ route('chef.dashboard') }}" title="Dashboard Chef">
                <span class="sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M6 12h12" />
                        <path d="M7 5h10l1 7H6l1-7Z" />
                        <path d="M8 12v8h8v-8" />
                    </svg>
                </span>
                <span class="sidebar-label">Dashboard Chef</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('chef.orders') ? 'active' : '' }}" href="{{ route('chef.orders') }}" title="Pesanan Diproses">
                <span class="sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 4h14v16H5V4Z" />
                        <path d="M8 8h8M8 12h8M8 16h5" />
                    </svg>
                </span>
                <span class="sidebar-label">Pesanan Diproses</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('chef.ingredients') ? 'active' : '' }}" href="{{ route('chef.ingredients') }}" title="Data Bahan">
                <span class="sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M6 4h12l-1 16H7L6 4Z" />
                        <path d="M9 8h6M9 12h6M9 16h4" />
                    </svg>
                </span>
                <span class="sidebar-label">Data Bahan</span>
            </a>
        @endif

        @if ($authLevel === 3)
            <a class="sidebar-link {{ request()->routeIs('cashier.orders') ? 'active' : '' }}" href="{{ route('cashier.orders') }}" title="Pesanan">
                <span class="sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M5 4h14v16H5V4Z" />
                        <path d="M9 8h6M9 12h6M9 16h3" />
                        <path d="M16 16h2" />
                    </svg>
                </span>
                <span class="sidebar-label">Pesanan</span>
            </a>
            <a class="sidebar-link {{ request()->routeIs('cashier.history') ? 'active' : '' }}" href="{{ route('cashier.history') }}" title="Riwayat Transaksi">
                <span class="sidebar-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 8v5l3 2" />
                        <path d="M4 12a8 8 0 1 0 2.34-5.66" />
                        <path d="M4 4v5h5" />
                    </svg>
                </span>
                <span class="sidebar-label">Riwayat Transaksi</span>
            </a>
        @endif

        @if ($authLevel === 4)
            @php
                $dataMasterMenus = [
                    'users' => [
                        'label' => 'Data User',
                        'icon' => '<path d="M16 20v-2a4 4 0 0 0-8 0v2" /><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" /><path d="M19 8v5M21.5 10.5h-5" />',
                    ],
                    'menus' => [
                        'label' => 'Data Menu',
                        'icon' => '<path d="M5 4h14v16H5V4Z" /><path d="M9 8h6M9 12h6M9 16h3" /><path d="M7 8h.01M7 12h.01M7 16h.01" />',
                    ],
                    'ingredients' => [
                        'label' => 'Data Bahan',
                        'icon' => '<path d="M6 4h12l-1 16H7L6 4Z" /><path d="M9 8h6M9 12h6M9 16h4" />',
                    ],
                    'tables' => [
                        'label' => 'Data Meja',
                        'icon' => '<path d="M4 10h16" /><path d="M6 10l-2 9M18 10l2 9" /><path d="M8 5h8a2 2 0 0 1 2 2v3H6V7a2 2 0 0 1 2-2Z" />',
                    ],
                    'stock' => [
                        'label' => 'Stok Produk',
                        'icon' => '<path d="M5 8h8l-1 12H6L5 8Z" /><path d="M7 5h4" /><path d="M16 10h3a2 2 0 0 1 0 4h-3" /><path d="M15 8v12h2a4 4 0 0 0 4-4v-2" />',
                    ],
                ];

                $managerMenus = [
                    'access' => [
                        'label' => 'Hak Akses',
                        'icon' => '<path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4Z" /><path d="M9 12l2 2 4-4" />',
                    ],
                    'database' => [
                        'label' => 'Database',
                        'icon' => '<path d="M4 7c0 2 3.58 4 8 4s8-2 8-4-3.58-4-8-4-8 2-8 4Z" /><path d="M4 7v10c0 2 3.58 4 8 4s8-2 8-4V7" /><path d="M4 12c0 2 3.58 4 8 4s8-2 8-4" />',
                    ],
                    'activity' => [
                        'label' => 'Catatan Aktivitas',
                        'icon' => '<path d="M5 4h14v16H5V4Z" /><path d="M8 8h8M8 12h8M8 16h5" /><path d="M17 20l3 2" />',
                    ],
                ];

                $dataMasterSections = array_keys($dataMasterMenus);
                $dataMasterOpen = request()->routeIs('manager.page') && in_array(request()->route('section'), $dataMasterSections, true);
            @endphp

            <details class="sidebar-group" @open($dataMasterOpen)>
                <summary class="sidebar-link sidebar-group-toggle">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 5h7v7H4V5Z" />
                            <path d="M13 5h7v7h-7V5Z" />
                            <path d="M4 14h7v5H4v-5Z" />
                            <path d="M13 14h7v5h-7v-5Z" />
                        </svg>
                    </span>
                    <span class="sidebar-label">Data Master</span>
                    <span class="sidebar-caret">▼</span>
                </summary>

                <div class="sidebar-subnav">
                    @foreach ($dataMasterMenus as $section => $menu)
                        <a class="sidebar-link {{ request()->routeIs('manager.page') && request()->route('section') === $section ? 'active' : '' }}" href="{{ route('manager.page', $section) }}" title="{{ $menu['label'] }}">
                            <span class="sidebar-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    {!! $menu['icon'] !!}
                                </svg>
                            </span>
                            <span class="sidebar-label">{{ $menu['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </details>

            @foreach ($managerMenus as $section => $menu)
                <a class="sidebar-link {{ request()->routeIs('manager.page') && request()->route('section') === $section ? 'active' : '' }}" href="{{ route('manager.page', $section) }}" title="{{ $menu['label'] }}">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            {!! $menu['icon'] !!}
                        </svg>
                    </span>
                    <span class="sidebar-label">{{ $menu['label'] }}</span>
                </a>
            @endforeach
        @endif

        @if ($authLevel === 5)
            @php
                $ownerMenus = [
                    ['route' => 'owner.sales', 'label' => 'Penjualan', 'title' => 'Laporan Penjualan', 'icon' => '<path d="M4 19V5" /><path d="M8 17V9" /><path d="M12 17V7" /><path d="M16 17v-5" /><path d="M20 17V4" />'],
                    ['route' => 'owner.finance', 'label' => 'Keuangan', 'title' => 'Laporan Keuangan', 'icon' => '<path d="M4 7h16v12H4V7Z" /><path d="M8 7V5h8v2" /><path d="M8 13h.01M12 13h.01M16 13h.01" />'],
                    ['route' => 'owner.products', 'label' => 'Produk', 'title' => 'Laporan Produk', 'icon' => '<path d="M5 4h14v16H5V4Z" /><path d="M8 8h8M8 12h8M8 16h5" />'],
                    ['route' => 'owner.ingredients', 'label' => 'Bahan', 'title' => 'Laporan Bahan', 'icon' => '<path d="M6 4h12l-1 16H7L6 4Z" /><path d="M9 8h6M9 12h6M9 16h4" />'],
                ];
                $ownerReportOpen = request()->routeIs('owner.sales', 'owner.finance', 'owner.products', 'owner.ingredients');
            @endphp

            <details class="sidebar-group" @open($ownerReportOpen)>
                <summary class="sidebar-link sidebar-group-toggle">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 19V5" />
                            <path d="M8 17V9" />
                            <path d="M12 17V7" />
                            <path d="M16 17v-5" />
                            <path d="M20 17V4" />
                        </svg>
                    </span>
                    <span class="sidebar-label">Laporan</span>
                    <span class="sidebar-caret">▼</span>
                </summary>

                <div class="sidebar-subnav">
                    @foreach ($ownerMenus as $menu)
                        <a class="sidebar-link {{ request()->routeIs($menu['route']) ? 'active' : '' }}" href="{{ route($menu['route']) }}" title="{{ $menu['title'] }}">
                            <span class="sidebar-icon">
                                <svg viewBox="0 0 24 24" fill="none">
                                    {!! $menu['icon'] !!}
                                </svg>
                            </span>
                            <span class="sidebar-label">{{ $menu['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </details>
        @endif

    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-account" id="sidebarAccount">
            <button type="button" class="sidebar-user-info" id="accountMenuToggle" aria-label="Buka menu akun" aria-expanded="false" aria-controls="accountMenu">
                <span class="sidebar-avatar">{{ strtoupper(substr($authName, 0, 1)) }}</span>
                <span class="sidebar-user-details">
                    <span class="sidebar-user-name">{{ $authName }}</span>
                    <span class="sidebar-user-role">{{ $roleName }}</span>
                </span>
            </button>

            <div class="account-menu" id="accountMenu">
                <div class="account-menu-head">
                    <div class="account-menu-avatar">{{ strtoupper(substr($authName, 0, 1)) }}</div>
                    <div class="sidebar-user-details">
                        <div class="account-menu-name">{{ $authName }}</div>
                        <div class="account-menu-role">{{ $roleName }}</div>
                    </div>
                </div>
                <div class="account-menu-main">
                    <a class="account-menu-link" href="{{ route('profile.show') }}">
                        <svg class="account-menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="2">
                            <path d="M20 21a8 8 0 0 0-16 0" />
                            <path d="M12 13a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" />
                        </svg>
                        <span>Profil</span>
                    </a>
                    <form method="post" action="{{ route('logout') }}" class="account-menu-logout">
                        @csrf
                        <button type="submit" class="account-menu-btn">
                            <svg class="account-menu-icon" viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <path d="M10 17l5-5-5-5" />
                                <path d="M15 12H3" />
                                <path d="M21 4v16" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>

<script>
    (function () {
        const sidebarStorageKey = 'swiftbite.sidebarCollapsed';
        const mobileBreakpoint = 860;

        function initSidebarToggle() {
            const toggleButton = document.getElementById('sidebarToggle');
            const mobileToggleButton = document.getElementById('mobileMenuToggle');
            const mobileBackdrop = document.getElementById('mobileSidebarBackdrop');
            const sidebar = document.getElementById('sidebarNavigation');
            const appShell = (sidebar || toggleButton || mobileToggleButton)?.closest('.app-shell');
            const account = document.getElementById('sidebarAccount');
            const accountToggle = document.getElementById('accountMenuToggle');

            function closeAccountMenu() {
                if (!account || !accountToggle) {
                    return;
                }

                account.classList.remove('open');
                accountToggle.setAttribute('aria-expanded', 'false');
            }

            function openActiveGroups() {
                document.querySelectorAll('.sidebar-group').forEach(function (group) {
                    if (group.querySelector('.sidebar-subnav .sidebar-link.active')) {
                        group.open = true;
                    }
                });
            }

            function isMobile() {
                return window.innerWidth <= mobileBreakpoint;
            }

            function closeMobileSidebar() {
                if (!appShell || !mobileToggleButton) {
                    return;
                }

                appShell.classList.remove('sidebar-mobile-open');
                mobileToggleButton.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
                closeAccountMenu();
            }

            function openMobileSidebar() {
                if (!appShell || !mobileToggleButton) {
                    return;
                }

                appShell.classList.remove('sidebar-collapsed');
                appShell.classList.add('sidebar-mobile-open');
                mobileToggleButton.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
                closeAccountMenu();
                openActiveGroups();
            }

            function syncSidebarMode() {
                if (!appShell) {
                    return;
                }

                if (isMobile()) {
                    appShell.classList.remove('sidebar-collapsed', 'sidebar-state-loading');
                    toggleButton?.setAttribute('aria-expanded', 'true');
                    return;
                }

                closeMobileSidebar();

                if (localStorage.getItem(sidebarStorageKey) === 'true') {
                    appShell.classList.add('sidebar-collapsed');
                    toggleButton?.setAttribute('aria-expanded', 'false');
                } else {
                    appShell.classList.remove('sidebar-collapsed');
                    toggleButton?.setAttribute('aria-expanded', 'true');
                }

                requestAnimationFrame(function () {
                    appShell.classList.remove('sidebar-state-loading');
                });
            }

            if (account && accountToggle) {
                accountToggle.addEventListener('click', function () {
                    const isOpen = account.classList.toggle('open');
                    accountToggle.setAttribute('aria-expanded', String(isOpen));
                });

                document.addEventListener('click', function (event) {
                    if (!account.contains(event.target)) {
                        closeAccountMenu();
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closeAccountMenu();
                    }
                });
            }

            if (!appShell) {
                return;
            }

            openActiveGroups();
            syncSidebarMode();

            mobileToggleButton?.addEventListener('click', function () {
                if (appShell.classList.contains('sidebar-mobile-open')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            });

            mobileBackdrop?.addEventListener('click', closeMobileSidebar);

            toggleButton?.addEventListener('click', function () {
                if (isMobile()) {
                    return;
                }

                appShell.classList.toggle('sidebar-collapsed');
                const expanded = !appShell.classList.contains('sidebar-collapsed');
                toggleButton.setAttribute('aria-expanded', String(expanded));
                localStorage.setItem(sidebarStorageKey, String(!expanded));
                closeAccountMenu();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeMobileSidebar();
                }
            });

            window.addEventListener('resize', syncSidebarMode);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebarToggle, { once: true });
        } else {
            initSidebarToggle();
        }
    })();
</script>
