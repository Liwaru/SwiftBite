<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page['title'] }}</title>
    <style>
        :root {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            --brown-dark: #27140d;
            --brown: #5a321f;
            --brown-mid: #7f4d30;
            --brown-light: #9a6239;
            --cream: #fff6e8;
            --cream-soft: #f4e3cd;
        }
        body { margin: 0; background: #ffffff; color: #2b1c15; }
        .app-shell .content-with-sidebar { background: #ffffff !important; }
        main { width: 100%; box-sizing: border-box; padding: 34px 30px 56px; }
        h1, p { margin: 0; }
        h1 { font-size: clamp(30px, 4vw, 44px); margin-bottom: 10px; }
        .muted { color: #7a5a46; line-height: 1.5; }
        .page-shell { max-width: 1120px; }
        .hero-card, .summary-card, .filter-card, .table-card, .panel {
            border-radius: 8px;
            box-shadow: 0 16px 38px rgba(39, 20, 13, .13);
        }
        .hero-card,
        .summary-card,
        .section-card,
        .menu-carousel,
        .menu-card,
        .action-btn,
        .row-action,
        .count-badge {
            -webkit-user-select: none;
            user-select: none;
        }
        input,
        select,
        textarea,
        .detail-list {
            -webkit-user-select: text;
            user-select: text;
        }
        .hero-card {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 16px;
            padding: 22px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown-dark));
            color: #fff8ed;
        }
        .eyebrow { margin-bottom: 8px; color: rgba(255, 248, 237, .76); font-size: 12px; font-weight: 900; letter-spacing: .04em; text-transform: uppercase; }
        .hero-title { margin: 0; font-size: clamp(32px, 4vw, 46px); line-height: 1.05; }
        .hero-subtitle { max-width: 720px; margin-top: 10px; color: rgba(255, 248, 237, .82); line-height: 1.55; }
        .summary-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 16px; }
        .summary-card { padding: 18px; background: linear-gradient(135deg, #8f5735, #4a2618); color: #fff8ed; }
        .summary-card.is-accent { background: linear-gradient(135deg, #a96f45, #5a321f); color: #fff8ed; }
        .summary-label { color: inherit; font-size: 13px; font-weight: 900; opacity: .82; }
        .summary-value { margin-top: 8px; font-size: 31px; line-height: 1; font-weight: 900; }
        .summary-note { margin-top: 10px; font-size: 13px; line-height: 1.4; opacity: .76; }
        .count-badge { border-radius: 999px; background: #fff6e8; color: var(--brown-dark); padding: 8px 12px; font-size: 13px; font-weight: 900; white-space: nowrap; }
        .filter-card, .table-card {
            margin-top: 16px;
            padding: 18px;
            background: #fff6e8;
            border: 1px solid #e1ad73;
        }
        .filter-form { display: grid; grid-template-columns: minmax(260px, 1fr) 220px auto; gap: 12px; align-items: end; }
        .filter-field { display: grid; gap: 7px; }
        .filter-field label { color: var(--brown); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .filter-field input, .filter-field select {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #d9b48b;
            border-radius: 8px;
            background: #fffdfa;
            color: #2b1c15;
            padding: 12px 13px;
            font: inherit;
        }
        .filter-actions { display: flex; gap: 8px; }
        .table-card .filter-form {
            margin-bottom: 16px;
            padding: 14px;
            border: 1px solid rgba(255, 246, 232, .18);
            border-radius: 8px;
            background: rgba(255, 246, 232, .08);
        }
        .table-card .filter-field label {
            color: rgba(255, 248, 237, .86);
        }
        .table-card .filter-field input,
        .table-card .filter-field select {
            border-color: rgba(255, 246, 232, .28);
            background: #fffdfa;
        }
        .filter-btn, .filter-link, .action-btn, .row-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid transparent;
            font: inherit;
            font-weight: 900;
            text-decoration: none;
            cursor: pointer;
            white-space: nowrap;
        }
        .filter-btn, .action-btn.primary { background: var(--brown); color: #fff8ed; }
        .filter-link, .row-action { background: #fffdfa; border-color: #d9b48b; color: var(--brown-dark); }
        .filter-btn:hover { background: var(--brown-dark); }
        .action-btn.primary:hover { background: #fff6e8; color: var(--brown-dark); border-color: #fff6e8; }
        .filter-link:hover, .row-action:hover { background: var(--cream-soft); }
        .table-card { background: linear-gradient(135deg, var(--brown-light), var(--brown-dark)); color: #fff8ed; border-color: rgba(255, 246, 232, .22); }
        .table-header { display: flex; align-items: center; justify-content: space-between; gap: 14px; margin-bottom: 16px; }
        .table-title { font-size: 22px; font-weight: 900; }
        .table-subtitle { margin-top: 5px; color: rgba(255, 248, 237, .76); font-size: 14px; font-weight: 700; }
        .table-wrap { overflow-x: auto; border: 1px solid rgba(255, 246, 232, .18); border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; min-width: 860px; }
        th, td { padding: 14px; text-align: left; border-bottom: 1px solid rgba(255, 246, 232, .14); vertical-align: middle; }
        th { background: rgba(255, 246, 232, .13); color: rgba(255, 248, 237, .86); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        tr:last-child td { border-bottom: 0; }
        .user-name { font-weight: 900; }
        .user-meta { margin-top: 4px; color: rgba(255, 248, 237, .72); font-size: 13px; font-weight: 700; }
        .muted-text { color: rgba(255, 248, 237, .68); }
        .pill, .status-badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 6px 10px; font-size: 12px; font-weight: 900; }
        .pill { background: #fff6e8; color: var(--brown-dark); }
        .pill.waiter { background: #d8ebff; color: #1f4e7b; }
        .pill.cashier { background: #e6ffd9; color: #2c642b; }
        .pill.manager { background: #ffe2ad; color: #6b3d00; }
        .pill.owner { background: #f2d9ff; color: #59306d; }
        .pill.food { background: #ffe2ad; color: #6b3d00; }
        .pill.drink { background: #d8ebff; color: #1f4e7b; }
        .status-badge { background: rgba(126, 211, 134, .18); color: #c9f5ce; border: 1px solid rgba(126, 211, 134, .42); }
        .action-group { display: flex; gap: 8px; }
        .row-action { padding: 9px 11px; font-size: 13px; }
        .menu-thumb {
            width: 100%;
            aspect-ratio: 16 / 10;
            display: grid;
            place-items: center;
            border-radius: 8px;
            overflow: hidden;
            background:
                linear-gradient(135deg, rgba(255, 246, 232, .18), rgba(255, 246, 232, .06)),
                rgba(255, 246, 232, .14);
            border: 1px solid rgba(255, 246, 232, .2);
            color: #fff8ed;
            font-size: 34px;
            font-weight: 900;
        }
        .menu-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .stock-badge {
            display: inline-flex;
            min-width: 42px;
            justify-content: center;
            border-radius: 999px;
            padding: 6px 10px;
            background: rgba(255, 246, 232, .14);
            color: #fff8ed;
            font-weight: 900;
        }
        .menu-sections { display: grid; gap: 18px; margin-top: 16px; }
        .section-card {
            padding: 18px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown-dark));
            color: #fff8ed;
            border: 1px solid rgba(255, 246, 232, .22);
            box-shadow: 0 16px 38px rgba(39, 20, 13, .13);
        }
        .section-head { display: flex; justify-content: space-between; gap: 14px; align-items: flex-start; margin-bottom: 16px; }
        .section-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; }
        .section-add-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 246, 232, .72);
            border-radius: 999px;
            background: #fff6e8;
            color: var(--brown-dark);
            padding: 8px 12px;
            font: inherit;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
            white-space: nowrap;
            transition: background .2s ease, color .2s ease, border-color .2s ease;
        }
        .section-add-btn:hover {
            background: transparent;
            color: #fff8ed;
            border-color: rgba(255, 246, 232, .86);
        }
        .section-manage-btn.is-active {
            background: transparent;
            color: #fff8ed;
            border-color: rgba(255, 246, 232, .86);
        }
        .section-title { font-size: 22px; font-weight: 900; }
        .section-subtitle { margin-top: 5px; color: rgba(255, 248, 237, .76); font-size: 14px; font-weight: 700; line-height: 1.45; }
        .bulk-toolbar {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
            padding: 11px 12px;
            border: 1px solid rgba(255, 246, 232, .2);
            border-radius: 8px;
            background: rgba(255, 246, 232, .1);
            color: #fff8ed;
            font-weight: 900;
        }
        .bulk-toolbar.is-visible { display: flex; }
        .bulk-actions { display: flex; gap: 8px; }
        .bulk-cancel-btn,
        .bulk-delete-btn {
            border-radius: 7px;
            padding: 9px 11px;
            border: 1px solid rgba(255, 246, 232, .26);
            font: inherit;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
        }
        .bulk-cancel-btn { background: rgba(255, 246, 232, .12); color: #fff8ed; }
        .bulk-delete-btn { background: #ffe2dc; color: #7b2418; }
        .menu-carousel {
            display: grid;
            grid-template-columns: 44px minmax(0, 1fr) 44px;
            gap: 10px;
            align-items: center;
        }
        .menu-carousel-btn {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            align-self: center;
            padding: 0 0 3px;
            border: 1px solid rgba(255, 246, 232, .72);
            border-radius: 999px;
            background: #fff6e8;
            color: var(--brown-dark);
            font: inherit;
            font-size: 26px;
            line-height: 1;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(24, 13, 7, .18);
            transition: background .2s ease, color .2s ease, transform .2s ease;
        }
        .menu-carousel-btn:hover {
            background: transparent;
            color: #fff8ed;
            transform: translateY(-1px);
        }
        .menu-rail {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: calc((100% - 42px) / 4);
            gap: 14px;
            overflow-x: auto;
            overscroll-behavior-inline: contain;
            scroll-behavior: smooth;
            scroll-snap-type: x proximity;
            padding: 2px 2px 8px;
            cursor: grab;
            touch-action: pan-y;
        }
        .menu-rail.is-dragging {
            cursor: grabbing;
            scroll-behavior: auto;
            scroll-snap-type: none;
        }
        .menu-card {
            position: relative;
            display: grid;
            grid-template-rows: auto 1fr auto;
            gap: 11px;
            min-width: 0;
            padding: 10px;
            border-radius: 8px;
            background: #fffdfa;
            border: 1px solid #ead4ba;
            color: var(--brown-dark);
            box-shadow: 0 12px 24px rgba(24, 13, 7, .18);
            scroll-snap-align: start;
            transition: border-color .2s ease, background .2s ease;
        }
        .menu-card.is-selected {
            background: #fff1ef;
            border-color: #e37a68;
        }
        .menu-select-control {
            position: absolute;
            left: 16px;
            top: 16px;
            z-index: 2;
            display: none;
            width: 26px;
            height: 26px;
            accent-color: #7b2418;
            cursor: pointer;
        }
        .section-card.is-managing .menu-select-control { display: block; }
        .menu-card-body { display: grid; gap: 9px; align-content: start; min-width: 0; }
        .menu-card-name {
            color: var(--brown-dark);
            font-size: 16px;
            font-weight: 900;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }
        .menu-card-price { color: var(--brown); font-size: 15px; font-weight: 900; }
        .menu-card-actions { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .menu-card-actions .row-action { width: 100%; }
        .menu-card .menu-thumb {
            background:
                linear-gradient(135deg, rgba(154, 98, 57, .16), rgba(39, 20, 13, .05)),
                #f4e3cd;
            border-color: #ead4ba;
            color: var(--brown);
        }
        .menu-card .status-badge {
            background: #e6ffd9;
            color: #2c642b;
            border-color: #9ad28e;
        }
        .menu-detail-thumb {
            width: 96px;
            height: 96px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: rgba(255, 246, 232, .14);
            border: 1px solid rgba(255, 246, 232, .2);
            color: #fff8ed;
            font-size: 36px;
            font-weight: 900;
        }
        .feedback-stack { display: grid; gap: 8px; margin-bottom: 14px; }
        .success-banner, .error-banner {
            border-radius: 8px;
            padding: 12px 14px;
            font-weight: 900;
        }
        .success-banner { background: #e6ffd9; color: #2c642b; border: 1px solid #9ad28e; }
        .error-banner { background: #ffe8dd; color: #7a2414; border: 1px solid #e5a08d; }
        .empty-state { padding: 28px; text-align: center; color: rgba(255, 248, 237, .78); }
        .pagination-wrap { display: flex; justify-content: flex-end; margin-top: 14px; }
        .pagination { display: flex; flex-wrap: wrap; align-items: center; gap: 7px; }
        .pagination-info { margin-right: 4px; color: rgba(255, 246, 232, .76); font-size: 13px; font-weight: 800; }
        .page-link, .page-current { min-width: 38px; box-sizing: border-box; border: 1px solid rgba(255, 246, 232, .26); border-radius: 7px; padding: 9px 11px; color: #fff8ed; text-align: center; text-decoration: none; font-weight: 900; }
        .page-current { background: #fff6e8; color: var(--brown-dark); }
        .page-disabled { opacity: .45; }
        .panel { max-width: 820px; margin-top: 24px; background: #fff6e8; border: 1px solid #e1ad73; border-radius: 8px; padding: 18px; box-shadow: 0 12px 30px rgba(39, 20, 13, .12); }
        .modal-shell {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(39, 20, 13, .58);
        }
        .modal-shell.is-open { display: flex; }
        .modal-dialog {
            width: min(520px, 100%);
            overflow: hidden;
            border: 1px solid rgba(255, 246, 232, .22);
            border-radius: 8px;
            background: linear-gradient(135deg, var(--brown-light), var(--brown-dark));
            color: #fff8ed;
            box-shadow: 0 24px 60px rgba(24, 13, 7, .32);
            background-clip: padding-box;
        }
        .modal-dialog.menu-create-dialog {
            width: min(460px, 100%);
            max-height: calc(100vh - 40px);
            overflow-y: auto;
        }
        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 18px;
            border-bottom: 1px solid rgba(255, 246, 232, .16);
        }
        .modal-title { font-size: 22px; font-weight: 900; }
        .modal-subtitle { margin-top: 4px; color: rgba(255, 248, 237, .78); font-size: 14px; line-height: 1.45; }
        .modal-close {
            width: 38px;
            height: 38px;
            border: 1px solid rgba(255, 246, 232, .72);
            border-radius: 8px;
            background: rgba(255, 246, 232, .9);
            color: var(--brown-dark);
            font: inherit;
            font-weight: 900;
            cursor: pointer;
        }
        .modal-close:hover {
            background: transparent;
            color: #fff8ed;
        }
        .modal-form { display: grid; gap: 14px; padding: 18px; background: transparent; }
        .menu-create-dialog .modal-header { padding: 15px 16px; }
        .menu-create-dialog .modal-form { gap: 10px; padding: 15px 16px 16px; }
        .menu-create-dialog .field-group { gap: 5px; }
        .menu-create-dialog .field-group input,
        .menu-create-dialog .field-group select { padding: 10px 12px; }
        .crop-panel { display: grid; gap: 10px; }
        .crop-box {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            border: 1px dashed rgba(255, 246, 232, .46);
            border-radius: 8px;
            background:
                linear-gradient(135deg, rgba(255, 246, 232, .16), rgba(255, 246, 232, .06)),
                rgba(255, 246, 232, .1);
            color: rgba(255, 248, 237, .76);
            cursor: grab;
        }
        .menu-create-dialog .crop-box {
            aspect-ratio: 16 / 8.4;
            max-height: 220px;
        }
        .crop-box.is-dragging { cursor: grabbing; }
        .crop-empty {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            padding: 16px;
            text-align: center;
            font-size: 13px;
            font-weight: 900;
        }
        .crop-image {
            position: absolute;
            left: 50%;
            top: 50%;
            max-width: none;
            user-select: none;
            -webkit-user-drag: none;
            display: none;
            transform-origin: center;
        }
        .crop-tools { display: grid; gap: 5px; }
        .crop-tools label { color: rgba(255, 248, 237, .88); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .crop-tools input[type="range"] { width: 100%; }
        .field-group { display: grid; gap: 7px; }
        .field-group label { color: rgba(255, 248, 237, .88); font-size: 12px; font-weight: 900; text-transform: uppercase; }
        .field-group input, .field-group select {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #d9b48b;
            border-radius: 8px;
            background: #fffdfa;
            color: #2b1c15;
            padding: 12px 13px;
            font: inherit;
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 9px;
            padding-top: 4px;
        }
        .detail-list { display: grid; gap: 10px; padding: 18px; background: transparent; }
        .detail-row {
            display: grid;
            grid-template-columns: 170px 1fr;
            gap: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 246, 232, .16);
        }
        .detail-row:last-child { border-bottom: 0; padding-bottom: 0; }
        .detail-label { color: rgba(255, 248, 237, .72); font-size: 13px; font-weight: 900; }
        .detail-value { color: #fff8ed; font-weight: 900; overflow-wrap: anywhere; }
        .ghost-btn, .submit-btn {
            border-radius: 8px;
            padding: 12px 14px;
            border: 1px solid #d9b48b;
            font: inherit;
            font-weight: 900;
            cursor: pointer;
        }
        .ghost-btn { background: #fffdfa; color: var(--brown-dark); }
        .submit-btn { background: #fff6e8; color: var(--brown-dark); border-color: #fff6e8; }
        .ghost-btn:hover { background: var(--cream-soft); }
        .submit-btn:hover { background: transparent; color: #fff8ed; }
        @media (max-width: 1180px) { .menu-rail { grid-auto-columns: calc((100% - 28px) / 3); } }
        @media (max-width: 980px) { .summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .filter-form { grid-template-columns: 1fr; } .filter-actions { justify-content: stretch; } .filter-actions > * { flex: 1; } .menu-rail { grid-auto-columns: calc((100% - 14px) / 2); } }
        @media (max-width: 760px) { main { padding: 24px 16px 44px; } .hero-card, .table-header, .section-head { align-items: flex-start; flex-direction: column; } .section-actions { width: 100%; justify-content: flex-start; } .summary-grid { grid-template-columns: 1fr; } .pagination-wrap { justify-content: flex-start; } .detail-row { grid-template-columns: 1fr; gap: 4px; } .menu-carousel { grid-template-columns: 1fr; } .menu-carousel-btn { display: none; } .menu-rail { grid-auto-columns: minmax(210px, 82vw); } }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                @if ($section === 'users')
                    @php
                        $roleLabels = [
                            1 => 'Waiter',
                            2 => 'Cashier',
                            3 => 'Manager',
                            4 => 'Owner',
                        ];

                        $roleClasses = [
                            1 => 'waiter',
                            2 => 'cashier',
                            3 => 'manager',
                            4 => 'owner',
                        ];
                    @endphp

                    <div class="page-shell">
                        <section class="hero-card">
                            <div>
                                <div class="eyebrow">Manager Operasional</div>
                                <h1 class="hero-title">Data User</h1>
                                <p class="hero-subtitle">Kelola akun pengguna SwiftBite, lihat role yang digunakan, dan pantau user yang aktif di sistem bakery.</p>
                            </div>
                        </section>

                        <section class="summary-grid">
                            <article class="summary-card">
                                <div class="summary-label">Total User</div>
                                <div class="summary-value">{{ number_format($summary['total_user']) }}</div>
                                <div class="summary-note">Semua akun yang terdaftar</div>
                            </article>
                            <article class="summary-card is-accent">
                                <div class="summary-label">Waiter</div>
                                <div class="summary-value">{{ number_format($summary['waiter']) }}</div>
                                <div class="summary-note">Akun pengantaran pesanan</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Cashier</div>
                                <div class="summary-value">{{ number_format($summary['cashier']) }}</div>
                                <div class="summary-note">Akun kasir operasional</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Pengelola</div>
                                <div class="summary-value">{{ number_format($summary['pengelola']) }}</div>
                                <div class="summary-note">Manager dan owner</div>
                            </article>
                        </section>

                        <section class="table-card">
                            <div class="table-header">
                                <div>
                                    <div class="table-title">Manajemen User</div>
                                    <div class="table-subtitle">Menampilkan {{ number_format($users->total()) }} user berdasarkan filter aktif.</div>
                                </div>
                                <div class="table-header-actions">
                                    <button type="button" class="action-btn primary js-open-modal" data-modal="create-user">
                                        <span>Tambah User</span>
                                    </button>
                                </div>
                            </div>

                            @if (session('success') || $errors->any())
                                <div class="feedback-stack">
                                    @if (session('success'))
                                        <div class="success-banner">{{ session('success') }}</div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="error-banner">
                                            @foreach ($errors->all() as $error)
                                                <div>{{ $error }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <form method="GET" action="{{ route('manager.page', 'users') }}" class="filter-form">
                                <div class="filter-field">
                                    <label for="searchUser">Search User</label>
                                    <input id="searchUser" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Cari nama, username, atau email...">
                                </div>
                                <div class="filter-field">
                                    <label for="roleFilter">Filter Role</label>
                                    <select id="roleFilter" name="role">
                                        <option value="semua" @selected($filters['role'] === 'semua')>Semua Role</option>
                                        @foreach ($roleOptions as $level => $label)
                                            <option value="{{ $level }}" @selected($filters['role'] === (string) $level)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="filter-actions">
                                    <button type="submit" class="filter-btn">
                                        <span>Terapkan</span>
                                    </button>
                                    <a href="{{ route('manager.page', 'users') }}" class="filter-link">
                                        <span>Reset</span>
                                    </a>
                                </div>
                            </form>

                            @if ($users->count() === 0)
                                <div class="empty-state">Belum ada data user yang cocok dengan filter saat ini.</div>
                            @else
                                <div class="table-wrap">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($users as $user)
                                                @php
                                                    $level = (int) $user->level;
                                                @endphp

                                                <tr>
                                                    <td>
                                                        <div class="user-name">{{ $user->name }}</div>
                                                        <div class="user-meta">ID User: {{ $user->id_user ?? $user->id }}</div>
                                                    </td>
                                                    <td>{{ $user->username ?? $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <span class="pill {{ $roleClasses[$level] ?? '' }}">{{ $roleLabels[$level] ?? 'Unknown' }}</span>
                                                    </td>
                                                    <td><span class="status-badge">Aktif</span></td>
                                                    <td>
                                                        <div class="action-group">
                                                            <button type="button" class="row-action js-open-modal" data-modal="edit-user-{{ $user->getKey() }}">Edit</button>
                                                            <button type="button" class="row-action js-open-modal" data-modal="detail-user-{{ $user->getKey() }}">Detail</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if ($users->hasPages())
                                    <div class="pagination-wrap">
                                        <div class="pagination">
                                            <span class="pagination-info">Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}</span>

                                            @if ($users->onFirstPage())
                                                <span class="page-link page-disabled">Prev</span>
                                            @else
                                                <a class="page-link" href="{{ $users->previousPageUrl() }}">Prev</a>
                                            @endif

                                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $pageNumber => $url)
                                                @if ($pageNumber === $users->currentPage())
                                                    <span class="page-current">{{ $pageNumber }}</span>
                                                @else
                                                    <a class="page-link" href="{{ $url }}">{{ $pageNumber }}</a>
                                                @endif
                                            @endforeach

                                            @if ($users->hasMorePages())
                                                <a class="page-link" href="{{ $users->nextPageUrl() }}">Next</a>
                                            @else
                                                <span class="page-link page-disabled">Next</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </section>

                        @foreach ($users as $user)
                            @php
                                $level = (int) $user->level;
                                $editModalId = 'edit-user-' . $user->getKey();
                                $detailModalId = 'detail-user-' . $user->getKey();
                            @endphp

                            <div class="modal-shell" id="modal-{{ $editModalId }}" aria-hidden="true">
                                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalEditUserTitle{{ $user->getKey() }}">
                                    <div class="modal-header">
                                        <div>
                                            <div class="modal-title" id="modalEditUserTitle{{ $user->getKey() }}">Edit User</div>
                                            <div class="modal-subtitle">Perbarui akun {{ $user->name }}. Nama user otomatis mengikuti username.</div>
                                        </div>
                                        <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">X</button>
                                    </div>

                                    <form method="POST" action="{{ route('manager.users.update', $user) }}" class="modal-form">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="modal_id" value="{{ $editModalId }}">

                                        <div class="field-group">
                                            <label for="editUsername{{ $user->getKey() }}">Username</label>
                                            <input id="editUsername{{ $user->getKey() }}" type="text" name="username" value="{{ old('modal_id') === $editModalId ? old('username', $user->username ?? $user->name) : ($user->username ?? $user->name) }}" required>
                                        </div>

                                        <div class="field-group">
                                            <label for="editPassword{{ $user->getKey() }}">Password Baru</label>
                                            <input id="editPassword{{ $user->getKey() }}" type="password" name="password" placeholder="Kosongkan jika tidak diubah">
                                        </div>

                                        <div class="field-group">
                                            <label for="editRole{{ $user->getKey() }}">Role</label>
                                            <select id="editRole{{ $user->getKey() }}" name="level" required>
                                                <option value="1" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '1')>Waiter</option>
                                                <option value="2" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '2')>Cashier</option>
                                                <option value="3" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '3')>Manager</option>
                                                <option value="4" @selected((string) (old('modal_id') === $editModalId ? old('level', $user->level) : $user->level) === '4')>Owner</option>
                                            </select>
                                        </div>

                                        <div class="modal-actions">
                                            <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                            <button type="submit" class="submit-btn">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="modal-shell" id="modal-{{ $detailModalId }}" aria-hidden="true">
                                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalDetailUserTitle{{ $user->getKey() }}">
                                    <div class="modal-header">
                                        <div>
                                            <div class="modal-title" id="modalDetailUserTitle{{ $user->getKey() }}">Detail User</div>
                                            <div class="modal-subtitle">Informasi akun {{ $user->name }}.</div>
                                        </div>
                                        <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">X</button>
                                    </div>

                                    <div class="detail-list">
                                        <div class="detail-row">
                                            <div class="detail-label">Nama</div>
                                            <div class="detail-value">{{ $user->name }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Username</div>
                                            <div class="detail-value">{{ $user->username ?? $user->name }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Email</div>
                                            <div class="detail-value">{{ $user->email }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Role</div>
                                            <div class="detail-value">{{ $roleLabels[$level] ?? 'Unknown' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Status</div>
                                            <div class="detail-value">Aktif</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Tanggal Dibuat</div>
                                            <div class="detail-value">{{ $user->created_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Terakhir Diperbarui</div>
                                            <div class="detail-value">{{ $user->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Login Terakhir</div>
                                            <div class="detail-value">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="modal-shell" id="modal-create-user" aria-hidden="true">
                        <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateUserTitle">
                            <div class="modal-header">
                                <div>
                                    <div class="modal-title" id="modalCreateUserTitle">Tambah User</div>
                                    <div class="modal-subtitle">Buat akun baru untuk waiter atau cashier. Nama user otomatis mengikuti username.</div>
                                </div>
                                <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">X</button>
                            </div>

                            <form method="POST" action="{{ route('manager.users.store') }}" class="modal-form">
                                @csrf
                                <input type="hidden" name="modal_id" value="create-user">
                                <div class="field-group">
                                    <label for="createUsername">Username</label>
                                    <input id="createUsername" type="text" name="username" value="{{ old('username') }}" placeholder="Contoh: waiter2" required>
                                </div>

                                <div class="field-group">
                                    <label for="createPassword">Password</label>
                                    <input id="createPassword" type="password" name="password" placeholder="Minimal 6 karakter" required>
                                </div>

                                <div class="field-group">
                                    <label for="createRole">Role</label>
                                    <select id="createRole" name="level" required>
                                        <option value="" disabled @selected(old('level') === null)>Pilih role</option>
                                        <option value="1" @selected(old('level') === '1')>Waiter</option>
                                        <option value="2" @selected(old('level') === '2')>Cashier</option>
                                    </select>
                                </div>

                                <div class="modal-actions">
                                    <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                    <button type="submit" class="submit-btn">Simpan User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @elseif ($section === 'menus')
                    <div class="page-shell">
                        <section class="hero-card">
                            <div>
                                <div class="eyebrow">Manager Operasional</div>
                                <h1 class="hero-title">Data Menu</h1>
                                <p class="hero-subtitle">Kelola makanan dan minuman yang tersedia di SwiftBite Morning Bakery.</p>
                            </div>
                        </section>

                        @php
                            $renderMenuSection = function ($title, $description, $items) {
                                return compact('title', 'description', 'items');
                            };

                            $menuSections = [
                                $renderMenuSection('Makanan', 'Daftar menu makanan dan bakery yang tersedia.', $foodMenuItems),
                                $renderMenuSection('Minuman', 'Daftar minuman yang tersedia.', $drinkMenuItems),
                            ];
                        @endphp

                        <section class="summary-grid">
                            <article class="summary-card">
                                <div class="summary-label">Total Menu</div>
                                <div class="summary-value">{{ number_format($menuSummary['total_menu']) }}</div>
                                <div class="summary-note">Semua menu terdaftar</div>
                            </article>
                            <article class="summary-card is-accent">
                                <div class="summary-label">Total Makanan</div>
                                <div class="summary-value">{{ number_format($menuSummary['makanan']) }}</div>
                                <div class="summary-note">Roti, pastry, dan dessert</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Total Minuman</div>
                                <div class="summary-value">{{ number_format($menuSummary['minuman']) }}</div>
                                <div class="summary-note">Kopi dan non-kopi</div>
                            </article>
                            <article class="summary-card">
                                <div class="summary-label">Menu Aktif</div>
                                <div class="summary-value">{{ number_format($menuSummary['aktif']) }}</div>
                                <div class="summary-note">Menu yang tersedia</div>
                            </article>
                        </section>

                        @if (session('success') || $errors->any())
                            <div class="feedback-stack">
                                @if (session('success'))
                                    <div class="success-banner">{{ session('success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="error-banner">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="menu-sections">
                            @foreach ($menuSections as $menuSection)
                                <section class="section-card">
                                    <div class="section-head">
                                        <div>
                                            <div class="section-title">{{ $menuSection['title'] }}</div>
                                            <div class="section-subtitle">{{ $menuSection['description'] }}</div>
                                        </div>
                                        <div class="section-actions">
                                            <button type="button" class="section-add-btn js-open-modal" data-modal="create-menu" data-category="{{ $menuSection['title'] }}">
                                                Tambah {{ $menuSection['title'] }}
                                            </button>
                                            <button type="button" class="section-add-btn section-manage-btn js-toggle-menu-manage">
                                                Kelola Menu
                                            </button>
                                            <span class="count-badge">{{ $menuSection['items']->count() }} Menu</span>
                                        </div>
                                    </div>

                                    @if ($menuSection['items']->isEmpty())
                                        <div class="empty-state">Belum ada menu {{ strtolower($menuSection['title']) }}.</div>
                                    @else
                                        <form method="POST" action="{{ route('manager.menus.confirm-destroy') }}" class="js-bulk-delete-form">
                                            @csrf

                                            <div class="bulk-toolbar">
                                                <span><span class="js-selected-count">0</span> menu dipilih</span>
                                                <div class="bulk-actions">
                                                    <button type="button" class="bulk-cancel-btn js-cancel-menu-manage">Batal</button>
                                                    <button type="submit" class="bulk-delete-btn">Hapus Terpilih</button>
                                                </div>
                                            </div>

                                        <div class="menu-carousel">
                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="-1" aria-label="Geser {{ $menuSection['title'] }} ke kiri">&lsaquo;</button>

                                            <div class="menu-rail">
                                                @foreach ($menuSection['items'] as $menu)
                                                    @php
                                                        $statusLabel = $menu->status === 'tersedia' ? 'Aktif' : 'Nonaktif';
                                                        $initial = strtoupper(substr($menu->nama_menu, 0, 1));
                                                    @endphp

                                                    <article class="menu-card">
                                                        <input type="checkbox" class="menu-select-control js-menu-select" name="menu_ids[]" value="{{ $menu->getKey() }}" aria-label="Pilih {{ $menu->nama_menu }}">
                                                        <div class="menu-thumb">
                                                            @if ($menu->foto)
                                                                <img src="{{ asset($menu->foto) }}" alt="{{ $menu->nama_menu }}">
                                                            @else
                                                                {{ $initial }}
                                                            @endif
                                                        </div>

                                                        <div class="menu-card-body">
                                                            <div class="menu-card-name">{{ $menu->nama_menu }}</div>
                                                            <div class="menu-card-price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</div>
                                                            <div><span class="status-badge">{{ $statusLabel }}</span></div>
                                                        </div>

                                                        <div class="menu-card-actions">
                                                            <button type="button" class="row-action">Edit</button>
                                                            <button type="button" class="row-action js-single-delete-menu" data-menu-id="{{ $menu->getKey() }}">Hapus</button>
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </div>

                                            <button type="button" class="menu-carousel-btn js-menu-scroll" data-direction="1" aria-label="Geser {{ $menuSection['title'] }} ke kanan">&rsaquo;</button>
                                        </div>
                                        </form>
                                    @endif
                                </section>
                            @endforeach
                        </div>

                        <div class="modal-shell" id="modal-create-menu" aria-hidden="true">
                            <div class="modal-dialog menu-create-dialog" role="dialog" aria-modal="true" aria-labelledby="modalCreateMenuTitle">
                                <div class="modal-header">
                                    <div>
                                        <div class="modal-title" id="modalCreateMenuTitle">Tambah Menu</div>
                                        <div class="modal-subtitle" id="modalCreateMenuSubtitle">Tambahkan menu baru ke SwiftBite Morning Bakery.</div>
                                    </div>
                                    <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">X</button>
                                </div>

                                <form method="POST" action="{{ route('manager.menus.store') }}" class="modal-form js-menu-create-form">
                                    @csrf
                                    <input type="hidden" name="category" id="createMenuCategory" value="Makanan">
                                    <input type="hidden" name="image_data" class="js-cropped-image">

                                    <div class="field-group">
                                        <label for="createMenuPhoto">Gambar Menu</label>
                                        <input id="createMenuPhoto" type="file" class="js-crop-input" accept="image/png,image/jpeg,image/webp">
                                    </div>

                                    <div class="crop-panel">
                                        <div class="crop-box js-crop-box">
                                            <div class="crop-empty js-crop-empty">Pilih gambar, lalu geser area ini untuk menentukan crop.</div>
                                            <img class="crop-image js-crop-image" alt="Preview crop menu">
                                        </div>
                                        <div class="crop-tools">
                                            <label for="createMenuZoom">Zoom Gambar</label>
                                            <input id="createMenuZoom" type="range" class="js-crop-zoom" min="1" max="2.5" step="0.01" value="1" disabled>
                                        </div>
                                    </div>

                                    <div class="field-group">
                                        <label for="createMenuName">Nama <span class="js-menu-category-label">Makanan</span></label>
                                        <input id="createMenuName" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Butter Croissant" required>
                                    </div>

                                    <div class="field-group">
                                        <label for="createMenuPrice">Harga</label>
                                        <input id="createMenuPrice" type="number" name="price" value="{{ old('price') }}" min="0" step="1000" placeholder="Contoh: 18000" required>
                                    </div>

                                    <div class="modal-actions">
                                        <button type="button" class="ghost-btn js-close-modal">Batal</button>
                                        <button type="submit" class="submit-btn">Simpan Menu</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('manager.menus.confirm-destroy') }}" class="js-single-delete-form" hidden>
                            @csrf
                            <input type="hidden" name="menu_ids[]" class="js-single-delete-id">
                        </form>

                        @foreach ($foodMenuItems->concat($drinkMenuItems) as $menu)
                            @php
                                $initial = strtoupper(substr($menu->nama_menu, 0, 1));
                                $statusLabel = $menu->status === 'tersedia' ? 'Aktif' : 'Nonaktif';
                                $totalSold = (int) ($menu->total_sold ?? 0);
                            @endphp

                            <div class="modal-shell" id="modal-detail-menu-{{ $menu->getKey() }}" aria-hidden="true">
                                <div class="modal-dialog" role="dialog" aria-modal="true" aria-labelledby="modalDetailMenuTitle{{ $menu->getKey() }}">
                                    <div class="modal-header">
                                        <div>
                                            <div class="modal-title" id="modalDetailMenuTitle{{ $menu->getKey() }}">Detail Menu</div>
                                            <div class="modal-subtitle">Informasi lengkap menu {{ $menu->nama_menu }}.</div>
                                        </div>
                                        <button type="button" class="modal-close js-close-modal" aria-label="Tutup modal">X</button>
                                    </div>

                                    <div class="detail-list">
                                        <div class="menu-detail-thumb">{{ $initial }}</div>
                                        <div class="detail-row">
                                            <div class="detail-label">Nama Menu</div>
                                            <div class="detail-value">{{ $menu->nama_menu }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Kategori</div>
                                            <div class="detail-value">{{ $menu->category }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Harga</div>
                                            <div class="detail-value">Rp{{ number_format($menu->harga, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Stok Produk</div>
                                            <div class="detail-value">{{ $menu->stok }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Total Terjual</div>
                                            <div class="detail-value">{{ $totalSold }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Status</div>
                                            <div class="detail-value">{{ $statusLabel }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Deskripsi</div>
                                            <div class="detail-value">{{ $menu->deskripsi ?: '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Tanggal Dibuat</div>
                                            <div class="detail-value">{{ $menu->created_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                        <div class="detail-row">
                                            <div class="detail-label">Tanggal Diperbarui</div>
                                            <div class="detail-value">{{ $menu->updated_at?->format('d M Y H:i') ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <h1>{{ $page['title'] }}</h1>
                    <p class="muted">{{ $page['description'] }}</p>

                    <section class="panel">
                        <p class="muted">Halaman ini sudah disiapkan untuk fitur manager.</p>
                    </section>
                @endif
            </main>
        </div>
    </div>
    <script>
        (function () {
            const modalTriggers = document.querySelectorAll('.js-open-modal');
            const closeButtons = document.querySelectorAll('.js-close-modal');
            const modals = document.querySelectorAll('.modal-shell');
            const cropState = {
                image: null,
                box: null,
                img: null,
                zoomInput: null,
                hiddenInput: null,
                empty: null,
                baseWidth: 0,
                baseHeight: 0,
                offsetX: 0,
                offsetY: 0,
                startX: 0,
                startY: 0,
                startOffsetX: 0,
                startOffsetY: 0,
                isDragging: false,
            };

            function prepareCreateMenuModal(trigger) {
                if (trigger.dataset.modal !== 'create-menu') {
                    return;
                }

                const category = trigger.dataset.category || 'Makanan';
                const title = document.getElementById('modalCreateMenuTitle');
                const subtitle = document.getElementById('modalCreateMenuSubtitle');
                const categoryInput = document.getElementById('createMenuCategory');

                document.querySelectorAll('.js-menu-category-label').forEach((label) => {
                    label.textContent = category;
                });

                if (title) {
                    title.textContent = 'Tambah ' + category;
                }

                if (subtitle) {
                    subtitle.textContent = 'Tambahkan ' + category.toLowerCase() + ' baru ke SwiftBite Morning Bakery.';
                }

                if (categoryInput) {
                    categoryInput.value = category;
                }
            }

            function updateCropImage() {
                if (!cropState.image || !cropState.box || !cropState.img || !cropState.zoomInput) {
                    return;
                }

                const zoom = Number(cropState.zoomInput.value || 1);
                const boxWidth = cropState.box.clientWidth;
                const boxHeight = cropState.box.clientHeight;
                const width = cropState.baseWidth * zoom;
                const height = cropState.baseHeight * zoom;
                const maxOffsetX = Math.max(0, (width - boxWidth) / 2);
                const maxOffsetY = Math.max(0, (height - boxHeight) / 2);

                cropState.offsetX = Math.max(-maxOffsetX, Math.min(maxOffsetX, cropState.offsetX));
                cropState.offsetY = Math.max(-maxOffsetY, Math.min(maxOffsetY, cropState.offsetY));
                cropState.img.style.width = cropState.baseWidth + 'px';
                cropState.img.style.height = cropState.baseHeight + 'px';
                cropState.img.style.transform = 'translate(calc(-50% + ' + cropState.offsetX + 'px), calc(-50% + ' + cropState.offsetY + 'px)) scale(' + zoom + ')';
            }

            function setCropImage(file) {
                if (!file || !cropState.img || !cropState.box || !cropState.zoomInput) {
                    return;
                }

                const reader = new FileReader();

                reader.onload = () => {
                    const image = new Image();

                    image.onload = () => {
                        const boxWidth = cropState.box.clientWidth;
                        const boxHeight = cropState.box.clientHeight;
                        const coverScale = Math.max(boxWidth / image.naturalWidth, boxHeight / image.naturalHeight);

                        cropState.image = image;
                        cropState.baseWidth = image.naturalWidth * coverScale;
                        cropState.baseHeight = image.naturalHeight * coverScale;
                        cropState.offsetX = 0;
                        cropState.offsetY = 0;
                        cropState.zoomInput.value = '1';
                        cropState.zoomInput.disabled = false;
                        cropState.img.src = reader.result;
                        cropState.img.style.display = 'block';

                        if (cropState.empty) {
                            cropState.empty.style.display = 'none';
                        }

                        updateCropImage();
                    };

                    image.src = reader.result;
                };

                reader.readAsDataURL(file);
            }

            function writeCroppedImage() {
                if (!cropState.image || !cropState.box || !cropState.hiddenInput || !cropState.zoomInput) {
                    return;
                }

                const canvas = document.createElement('canvas');
                const boxWidth = cropState.box.clientWidth;
                const boxHeight = cropState.box.clientHeight;
                const zoom = Number(cropState.zoomInput.value || 1);
                const scale = (cropState.baseWidth * zoom) / cropState.image.naturalWidth;
                const imageLeft = (boxWidth - cropState.baseWidth * zoom) / 2 + cropState.offsetX;
                const imageTop = (boxHeight - cropState.baseHeight * zoom) / 2 + cropState.offsetY;
                const sourceX = Math.max(0, -imageLeft / scale);
                const sourceY = Math.max(0, -imageTop / scale);
                const sourceWidth = Math.min(cropState.image.naturalWidth - sourceX, boxWidth / scale);
                const sourceHeight = Math.min(cropState.image.naturalHeight - sourceY, boxHeight / scale);
                const outputWidth = 800;
                const outputHeight = 500;

                canvas.width = outputWidth;
                canvas.height = outputHeight;
                canvas.getContext('2d').drawImage(cropState.image, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, outputWidth, outputHeight);
                cropState.hiddenInput.value = canvas.toDataURL('image/jpeg', .9);
            }

            function initMenuCropper() {
                cropState.box = document.querySelector('.js-crop-box');
                cropState.img = document.querySelector('.js-crop-image');
                cropState.zoomInput = document.querySelector('.js-crop-zoom');
                cropState.hiddenInput = document.querySelector('.js-cropped-image');
                cropState.empty = document.querySelector('.js-crop-empty');

                const fileInput = document.querySelector('.js-crop-input');
                const form = document.querySelector('.js-menu-create-form');

                if (!cropState.box || !cropState.img || !fileInput || !form) {
                    return;
                }

                fileInput.addEventListener('change', () => {
                    setCropImage(fileInput.files ? fileInput.files[0] : null);
                });

                cropState.zoomInput?.addEventListener('input', updateCropImage);

                cropState.box.addEventListener('pointerdown', (event) => {
                    if (!cropState.image || event.button !== 0) {
                        return;
                    }

                    cropState.isDragging = true;
                    cropState.startX = event.clientX;
                    cropState.startY = event.clientY;
                    cropState.startOffsetX = cropState.offsetX;
                    cropState.startOffsetY = cropState.offsetY;
                    cropState.box.classList.add('is-dragging');
                    cropState.box.setPointerCapture(event.pointerId);
                });

                cropState.box.addEventListener('pointermove', (event) => {
                    if (!cropState.isDragging) {
                        return;
                    }

                    event.preventDefault();
                    cropState.offsetX = cropState.startOffsetX + event.clientX - cropState.startX;
                    cropState.offsetY = cropState.startOffsetY + event.clientY - cropState.startY;
                    updateCropImage();
                });

                function stopCropDrag() {
                    cropState.isDragging = false;
                    cropState.box.classList.remove('is-dragging');
                }

                cropState.box.addEventListener('pointerup', stopCropDrag);
                cropState.box.addEventListener('pointercancel', stopCropDrag);
                form.addEventListener('submit', writeCroppedImage);
            }

            function openModal(id) {
                const modal = document.getElementById('modal-' + id);

                if (!modal) {
                    return;
                }

                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal(modal) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');

                if (!document.querySelector('.modal-shell.is-open')) {
                    document.body.style.overflow = '';
                }
            }

            modalTriggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    prepareCreateMenuModal(trigger);
                    openModal(trigger.dataset.modal);
                });
            });

            closeButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    const modal = button.closest('.modal-shell');

                    if (modal) {
                        closeModal(modal);
                    }
                });
            });

            modals.forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            document.querySelectorAll('.js-menu-scroll').forEach((button) => {
                button.addEventListener('click', () => {
                    const carousel = button.closest('.menu-carousel');
                    const rail = carousel ? carousel.querySelector('.menu-rail') : null;

                    if (!rail) {
                        return;
                    }

                    const direction = Number(button.dataset.direction || 1);
                    const firstCard = rail.querySelector('.menu-card');
                    const step = firstCard ? (firstCard.offsetWidth + 14) * 2 : 520;

                    rail.scrollBy({
                        left: direction * step,
                        behavior: 'smooth',
                    });
                });
            });

            document.querySelectorAll('.menu-rail').forEach((rail) => {
                let isDragging = false;
                let startX = 0;
                let startScrollLeft = 0;

                function stopDrag() {
                    if (!isDragging) {
                        return;
                    }

                    isDragging = false;
                    rail.classList.remove('is-dragging');
                }

                rail.addEventListener('pointerdown', (event) => {
                    if (event.button !== 0 || event.target.closest('button, a, input, select, textarea')) {
                        return;
                    }

                    isDragging = true;
                    startX = event.clientX;
                    startScrollLeft = rail.scrollLeft;
                    rail.classList.add('is-dragging');
                    rail.setPointerCapture(event.pointerId);
                });

                rail.addEventListener('pointermove', (event) => {
                    if (!isDragging) {
                        return;
                    }

                    event.preventDefault();
                    const walk = event.clientX - startX;
                    rail.scrollLeft = startScrollLeft - walk;
                });

                rail.addEventListener('pointerup', stopDrag);
                rail.addEventListener('pointercancel', stopDrag);
                rail.addEventListener('pointerleave', stopDrag);
            });

            function updateBulkToolbar(section) {
                const selected = section.querySelectorAll('.js-menu-select:checked');
                const toolbar = section.querySelector('.bulk-toolbar');
                const count = section.querySelector('.js-selected-count');

                section.querySelectorAll('.menu-card').forEach((card) => {
                    const checkbox = card.querySelector('.js-menu-select');
                    card.classList.toggle('is-selected', Boolean(checkbox && checkbox.checked));
                });

                if (count) {
                    count.textContent = selected.length;
                }

                if (toolbar) {
                    toolbar.classList.toggle('is-visible', selected.length > 0);
                }
            }

            document.querySelectorAll('.section-card').forEach((section) => {
                const manageButton = section.querySelector('.js-toggle-menu-manage');
                const cancelButton = section.querySelector('.js-cancel-menu-manage');
                const form = section.querySelector('.js-bulk-delete-form');

                manageButton?.addEventListener('click', () => {
                    const isManaging = section.classList.toggle('is-managing');
                    manageButton.classList.toggle('is-active', isManaging);
                    manageButton.textContent = isManaging ? 'Selesai Kelola' : 'Kelola Menu';

                    if (!isManaging) {
                        section.querySelectorAll('.js-menu-select').forEach((checkbox) => {
                            checkbox.checked = false;
                        });
                    }

                    updateBulkToolbar(section);
                });

                cancelButton?.addEventListener('click', () => {
                    section.classList.remove('is-managing');
                    manageButton?.classList.remove('is-active');

                    if (manageButton) {
                        manageButton.textContent = 'Kelola Menu';
                    }

                    section.querySelectorAll('.js-menu-select').forEach((checkbox) => {
                        checkbox.checked = false;
                    });

                    updateBulkToolbar(section);
                });

                section.querySelectorAll('.js-menu-select').forEach((checkbox) => {
                    checkbox.addEventListener('change', () => updateBulkToolbar(section));
                });

                form?.addEventListener('submit', (event) => {
                    const selected = section.querySelectorAll('.js-menu-select:checked').length;

                    if (selected === 0) {
                        event.preventDefault();
                    }
                });
            });

            document.querySelectorAll('.js-single-delete-menu').forEach((button) => {
                button.addEventListener('click', () => {
                    const form = document.querySelector('.js-single-delete-form');
                    const input = document.querySelector('.js-single-delete-id');

                    if (!form || !input) {
                        return;
                    }

                    input.value = button.dataset.menuId || '';
                    form.submit();
                });
            });

            initMenuCropper();

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    const modal = document.querySelector('.modal-shell.is-open');

                    if (modal) {
                        closeModal(modal);
                    }
                }
            });

            @if ($section === 'users' && $errors->any())
                openModal(@json(old('modal_id', 'create-user')));
            @endif

            @if ($section === 'menus' && $errors->any())
                prepareCreateMenuModal({ dataset: { modal: 'create-menu', category: @json(old('category', 'Makanan')) } });
                openModal('create-menu');
            @endif
        })();
    </script>
</body>
</html>
