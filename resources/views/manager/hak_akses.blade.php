<!DOCTYPE html>
<html lang="id">
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page['title'] }}</title>
    @include('manager.partials.page_styles')
    <style>
        .access-panel {
            margin-top: 16px;
        }
        .access-table {
            min-width: 920px;
        }
        .access-table th:not(:first-child),
        .access-table td:not(:first-child) {
            text-align: center;
            width: 118px;
        }
        .access-menu-name {
            display: grid;
            gap: 4px;
        }
        .access-menu-title {
            font-weight: 900;
            color: #fff8ed;
        }
        .access-menu-group {
            color: rgba(255, 248, 237, .68);
            font-size: 12px;
            font-weight: 800;
        }
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        .access-toggle {
            display: inline-grid;
            place-items: center;
            width: 36px;
            height: 36px;
            cursor: pointer;
        }
        .access-toggle-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }
        .access-toggle-mark {
            display: inline-grid;
            place-items: center;
            width: 24px;
            height: 24px;
            border-radius: 999px;
            background: #fffdfa;
            border: 2px solid #f4e3cd;
            box-shadow: 0 5px 12px rgba(39, 20, 13, .2);
            transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease;
        }
        .access-toggle-mark::after {
            content: "";
            width: 7px;
            height: 12px;
            margin-top: -2px;
            border: solid var(--brown);
            border-width: 0 3px 3px 0;
            opacity: 0;
            transform: rotate(45deg) scale(.72);
            transition: opacity .16s ease, transform .16s ease;
        }
        .access-toggle:hover .access-toggle-mark {
            transform: translateY(-1px);
            border-color: #fffdfa;
        }
        .access-toggle-input:checked + .access-toggle-mark::after {
            opacity: 1;
            transform: rotate(45deg) scale(1);
        }
        .access-toggle-input:focus-visible + .access-toggle-mark {
            outline: 3px solid rgba(255, 248, 237, .42);
            outline-offset: 3px;
        }
        .access-toggle.is-locked {
            cursor: not-allowed;
        }
        .access-toggle.is-locked .access-toggle-mark {
            border-color: #fffdfa;
            box-shadow: 0 0 0 3px rgba(255, 246, 232, .14), 0 5px 12px rgba(39, 20, 13, .2);
        }
        .access-save-button {
            min-height: 42px;
            border: 1px solid #fff6e8;
            border-radius: 999px;
            background: #fffdfa;
            color: var(--brown-dark);
            padding: 10px 16px;
            font: inherit;
            font-size: 13px;
            font-weight: 900;
            cursor: pointer;
            box-shadow: 0 10px 22px rgba(24, 13, 7, .18);
            transition: background .18s ease, color .18s ease, transform .18s ease;
        }
        .access-save-button:hover {
            background: var(--cream-soft);
            color: var(--brown);
            transform: translateY(-1px);
        }
        .access-check,
        .access-empty {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 999px;
            font-size: 16px;
            font-weight: 900;
            user-select: none;
        }
        .access-check {
            background: #fffdfa;
            color: var(--brown);
            border: 1px solid #f4e3cd;
            box-shadow: 0 7px 16px rgba(39, 20, 13, .22);
        }
        .access-empty {
            background: rgba(255, 246, 232, .08);
            color: rgba(255, 248, 237, .45);
            border: 1px solid rgba(255, 246, 232, .14);
        }
        .access-note {
            margin-top: 12px;
            color: rgba(255, 248, 237, .72);
            font-size: 13px;
            font-weight: 700;
            line-height: 1.45;
        }
        .access-table th,
        .access-table td {
            padding: 11px 14px;
        }
    </style>
</head>
<body>
    <div class="app-shell">
        @include('header')

        <div class="content-with-sidebar">
            <main>
                @php
                    $roles = $roles ?? [];
                    $features = $features ?? [];
                    $permissions = $permissions ?? [];

                    // Show only menu-like features in the access matrix.
                    // Exclude non-menu actions such as export/print or internal actions.
                    $menuFeatureKeys = [
                        'waiter.orders',
                        'chef.orders', 'chef.ingredients',
                        'cashier.orders', 'cashier.history',
                        'manager.users', 'manager.menus', 'manager.ingredients', 'manager.tables', 'manager.stock', 'manager.access', 'manager.database', 'manager.activity',
                        'owner.sales', 'owner.finance', 'owner.products', 'owner.ingredients',
                    ];

                    $features = array_filter($features, function ($feature, $key) use ($menuFeatureKeys) {
                        return in_array($key, $menuFeatureKeys, true);
                    }, ARRAY_FILTER_USE_BOTH);
                @endphp

                <section class="hero-card">
                    <div>
                        <div class="eyebrow">MANAGER OPERASIONAL</div>
                        <h1>Hak Akses</h1>
                        <p class="hero-subtitle">Peta akses fitur berdasarkan role Waiter, Baker, Cashier, Manager, dan Owner di SwiftBite Morning Bakery.</p>
                    </div>
                </section>

                <section class="table-card access-panel">
                    <form action="{{ route('manager.access.update') }}" method="post">
                        @csrf
                        <div class="table-header">
                            <div>
                                <div class="table-title">Hak Akses Fitur</div>
                                <div class="table-subtitle">Tanda centang menunjukkan role yang boleh membuka atau menjalankan fitur tersebut.</div>
                            </div>
                            <button type="submit" class="access-save-button">Simpan Perubahan</button>
                        </div>

                        <div class="table-wrap">
                            <table class="access-table">
                                <thead>
                                    <tr>
                                        <th>Fitur Menu</th>
                                        @foreach ($roles as $level => $roleName)
                                            <th>{{ $roleName }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($features as $featureKey => $feature)
                                        <tr>
                                            <td>
                                                <div class="access-menu-name">
                                                    <span class="access-menu-title">{{ $feature['name'] }}</span>
                                                </div>
                                            </td>
                                            @foreach ($roles as $level => $roleName)
                                                @php
                                                    $isLockedManagerAccess = (int) $level === 4 && $featureKey === 'manager.access';
                                                @endphp
                                                <td>
                                                    <label class="access-toggle {{ $isLockedManagerAccess ? 'is-locked' : '' }}" for="permission_{{ $featureKey }}_{{ $level }}" title="{{ $isLockedManagerAccess ? 'Hak Akses Manager wajib aktif' : '' }}">
                                                        <input
                                                            class="access-toggle-input"
                                                            type="checkbox"
                                                            id="permission_{{ $featureKey }}_{{ $level }}"
                                                            name="permissions[{{ $level }}][]"
                                                            value="{{ $featureKey }}"
                                                            {{ isset($permissions[$level][$featureKey]) && $permissions[$level][$featureKey] ? 'checked' : '' }}
                                                            {{ $isLockedManagerAccess ? 'disabled' : '' }}
                                                        />
                                                        <span class="access-toggle-mark" aria-hidden="true"></span>
                                                        <span class="sr-only">{{ $feature['name'] }} untuk {{ $roleName }}</span>
                                                    </label>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <p class="access-note">
                        Hak akses mengikuti level role terbaru: Customer 0, Waiter 1, Baker 2, Cashier 3, Manager 4, dan Owner 5. Hak Akses untuk Manager wajib aktif agar manager tidak mengunci aksesnya sendiri.
                    </p>
                </section>
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
