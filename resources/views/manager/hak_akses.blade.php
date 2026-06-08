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
            width: 142px;
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
                        <p class="hero-subtitle">Peta akses fitur berdasarkan role Cashier, Manager, dan Owner di SwiftBite Morning Bakery.</p>
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
                            <button type="submit" class="button button-primary">Simpan Perubahan</button>
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
                                                    <span class="access-menu-group">{{ $feature['group'] }}</span>
                                                </div>
                                            </td>
                                            @foreach ($roles as $level => $roleName)
                                                <td>
                                                    <label class="sr-only" for="permission_{{ $featureKey }}_{{ $level }}">
                                                        {{ $feature['name'] }} untuk {{ $roleName }}
                                                    </label>
                                                    <input
                                                        type="checkbox"
                                                        id="permission_{{ $featureKey }}_{{ $level }}"
                                                        name="permissions[{{ $level }}][]"
                                                        value="{{ $featureKey }}"
                                                        {{ isset($permissions[$level][$featureKey]) && $permissions[$level][$featureKey] ? 'checked' : '' }}
                                                    />
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <p class="access-note">
                        Hak akses mengikuti level role terbaru: Customer 0, Waiter 1, Baker 2, Cashier 3, Manager 4, dan Owner 5. Halaman ini menampilkan fokus akses untuk role yang dikelola di area manager.
                    </p>
                </section>
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
