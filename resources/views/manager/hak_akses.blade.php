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
                    $roles = ['cashier' => 'Cashier', 'manager' => 'Manager', 'owner' => 'Owner'];
                    $accessRows = [
                        ['group' => 'Cashier', 'name' => 'Pesanan', 'cashier' => true, 'manager' => false, 'owner' => false],
                        ['group' => 'Cashier', 'name' => 'Riwayat Transaksi', 'cashier' => true, 'manager' => false, 'owner' => false],
                        ['group' => 'Cashier', 'name' => 'Ubah Status Pesanan', 'cashier' => true, 'manager' => false, 'owner' => false],
                        ['group' => 'Manager', 'name' => 'Data User', 'cashier' => false, 'manager' => true, 'owner' => false],
                        ['group' => 'Manager', 'name' => 'Data Menu', 'cashier' => false, 'manager' => true, 'owner' => false],
                        ['group' => 'Manager', 'name' => 'Data Paket Promo', 'cashier' => false, 'manager' => true, 'owner' => false],
                        ['group' => 'Manager', 'name' => 'Data Bahan', 'cashier' => false, 'manager' => true, 'owner' => false],
                        ['group' => 'Manager', 'name' => 'Data Meja', 'cashier' => false, 'manager' => true, 'owner' => false],
                        ['group' => 'Manager', 'name' => 'Stok Produk', 'cashier' => false, 'manager' => true, 'owner' => false],
                        ['group' => 'Manager', 'name' => 'Hak Akses', 'cashier' => false, 'manager' => true, 'owner' => false],
                        ['group' => 'Manager', 'name' => 'Database', 'cashier' => false, 'manager' => true, 'owner' => false],
                        ['group' => 'Manager', 'name' => 'Catatan Aktivitas', 'cashier' => false, 'manager' => true, 'owner' => false],
                        ['group' => 'Owner', 'name' => 'Laporan Penjualan', 'cashier' => false, 'manager' => false, 'owner' => true],
                        ['group' => 'Owner', 'name' => 'Laporan Keuangan', 'cashier' => false, 'manager' => false, 'owner' => true],
                        ['group' => 'Owner', 'name' => 'Laporan Produk', 'cashier' => false, 'manager' => false, 'owner' => true],
                        ['group' => 'Owner', 'name' => 'Laporan Bahan', 'cashier' => false, 'manager' => false, 'owner' => true],
                        ['group' => 'Owner', 'name' => 'Export PDF, Excel, dan Print', 'cashier' => false, 'manager' => false, 'owner' => true],
                    ];
                @endphp

                <section class="hero-card">
                    <div>
                        <div class="eyebrow">MANAGER OPERASIONAL</div>
                        <h1>Hak Akses</h1>
                        <p class="hero-subtitle">Peta akses fitur berdasarkan role Cashier, Manager, dan Owner di SwiftBite Morning Bakery.</p>
                    </div>
                </section>

                <section class="table-card access-panel">
                    <div class="table-header">
                        <div>
                            <div class="table-title">Hak Akses Fitur</div>
                            <div class="table-subtitle">Tanda centang menunjukkan role yang boleh membuka atau menjalankan fitur tersebut.</div>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="access-table">
                            <thead>
                                <tr>
                                    <th>Fitur Menu</th>
                                    @foreach ($roles as $role)
                                        <th>{{ $role }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accessRows as $row)
                                    <tr>
                                        <td>
                                            <div class="access-menu-name">
                                                <span class="access-menu-title">{{ $row['name'] }}</span>
                                                <span class="access-menu-group">{{ $row['group'] }}</span>
                                            </div>
                                        </td>
                                        @foreach (array_keys($roles) as $roleKey)
                                            <td>
                                                @if ($row[$roleKey])
                                                    <span class="access-check" aria-label="Boleh">&#10003;</span>
                                                @else
                                                    <span class="access-empty" aria-label="Tidak boleh">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p class="access-note">
                        Hak akses mengikuti level role terbaru: Customer 0, Waiter 1, Chef 2, Cashier 3, Manager 4, dan Owner 5. Halaman ini menampilkan fokus akses untuk role yang dikelola di area manager.
                    </p>
                </section>
            </main>
        </div>
    </div>
    @include('manager.partials.page_scripts')
</body>
</html>
