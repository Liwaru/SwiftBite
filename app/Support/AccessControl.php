<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AccessControl
{
    public static function roles(): array
    {
        return [
            3 => 'Cashier',
            4 => 'Manager',
            5 => 'Owner',
        ];
    }

    public static function features(): array
    {
        return [
            'cashier.orders' => ['group' => 'Cashier', 'name' => 'Pesanan', 'default_roles' => [3]],
            'cashier.history' => ['group' => 'Cashier', 'name' => 'Riwayat Transaksi', 'default_roles' => [3]],
            'cashier.order_status' => ['group' => 'Cashier', 'name' => 'Ubah Status Pesanan', 'default_roles' => [3]],
            'manager.users' => ['group' => 'Manager', 'name' => 'Data User', 'default_roles' => [4]],
            'manager.menus' => ['group' => 'Manager', 'name' => 'Data Menu', 'default_roles' => [4]],
            'manager.packages' => ['group' => 'Manager', 'name' => 'Data Paket Promo', 'default_roles' => [4]],
            'manager.ingredients' => ['group' => 'Manager', 'name' => 'Data Bahan', 'default_roles' => [4]],
            'manager.tables' => ['group' => 'Manager', 'name' => 'Data Meja', 'default_roles' => [4]],
            'manager.stock' => ['group' => 'Manager', 'name' => 'Stok Produk', 'default_roles' => [4]],
            'manager.access' => ['group' => 'Manager', 'name' => 'Hak Akses', 'default_roles' => [4]],
            'manager.database' => ['group' => 'Manager', 'name' => 'Database', 'default_roles' => [4]],
            'manager.activity' => ['group' => 'Manager', 'name' => 'Catatan Aktivitas', 'default_roles' => [4]],
            'owner.sales' => ['group' => 'Owner', 'name' => 'Laporan Penjualan', 'default_roles' => [5]],
            'owner.finance' => ['group' => 'Owner', 'name' => 'Laporan Keuangan', 'default_roles' => [5]],
            'owner.products' => ['group' => 'Owner', 'name' => 'Laporan Produk', 'default_roles' => [5]],
            'owner.ingredients' => ['group' => 'Owner', 'name' => 'Laporan Bahan', 'default_roles' => [5]],
            'owner.exports' => ['group' => 'Owner', 'name' => 'Export PDF, Excel, dan Print', 'default_roles' => [5]],
        ];
    }

    public static function defaults(): array
    {
        $permissions = [];

        foreach (self::roles() as $level => $role) {
            foreach (self::features() as $key => $feature) {
                $permissions[$level][$key] = in_array($level, $feature['default_roles'], true);
            }
        }

        return $permissions;
    }

    public static function permissions(): array
    {
        $permissions = self::defaults();

        if (! Schema::hasTable('role_menu_permissions')) {
            return $permissions;
        }

        DB::table('role_menu_permissions')
            ->get(['level', 'feature_key', 'is_enabled'])
            ->each(function ($row) use (&$permissions) {
                if (isset($permissions[(int) $row->level][$row->feature_key])) {
                    $permissions[(int) $row->level][$row->feature_key] = (bool) $row->is_enabled;
                }
            });

        return $permissions;
    }

    public static function allowed(int $level, string $featureKey): bool
    {
        $permissions = self::permissions();

        return (bool) ($permissions[$level][$featureKey] ?? false);
    }

    public static function sync(array $selected): void
    {
        if (! Schema::hasTable('role_menu_permissions')) {
            return;
        }

        $now = now();

        foreach (self::roles() as $level => $role) {
            foreach (self::features() as $key => $feature) {
                DB::table('role_menu_permissions')->updateOrInsert(
                    ['level' => $level, 'feature_key' => $key],
                    [
                        'is_enabled' => in_array($key, $selected[$level] ?? [], true),
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }
    }

    public static function managerFeatureForSection(string $section): string
    {
        return match ($section) {
            'users' => 'manager.users',
            'menus' => 'manager.menus',
            'ingredients' => 'manager.ingredients',
            'tables' => 'manager.tables',
            'stock' => 'manager.stock',
            'database' => 'manager.database',
            'activity' => 'manager.activity',
            default => 'manager.access',
        };
    }
}
