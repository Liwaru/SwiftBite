<?php

namespace Database\Seeders;

use App\Models\DiningTable;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['name' => 'pelanggan'],
            [
                'email' => 'pelanggan@example.com',
                'password' => 'pelanggan',
                'level' => 1,
            ],
        );

        User::updateOrCreate(
            ['name' => 'kasir'],
            [
                'email' => 'kasir@example.com',
                'password' => 'kasir',
                'level' => 2,
            ],
        );

        User::updateOrCreate(
            ['name' => 'admin'],
            [
                'email' => 'admin@example.com',
                'password' => 'admin',
                'level' => 3,
            ],
        );

        foreach (range(1, 6) as $number) {
            DiningTable::firstOrCreate(
                ['name' => 'Meja ' . $number],
                ['qr_token' => Str::random(16), 'is_active' => true],
            );
        }

        $menus = [
            ['name' => 'Roti Srikaya', 'category' => 'Roti', 'description' => 'Roti lembut isi srikaya manis.', 'price' => 8000],
            ['name' => 'Roti Sosis', 'category' => 'Roti', 'description' => 'Roti gurih dengan sosis panggang.', 'price' => 10000],
            ['name' => 'Nasi Goreng Kampung', 'category' => 'Makanan', 'description' => 'Nasi goreng gurih dengan telur.', 'price' => 25000],
            ['name' => 'Mie Goreng Seafood', 'category' => 'Makanan', 'description' => 'Mie goreng dengan topping seafood.', 'price' => 28000],
            ['name' => 'Kopi Susu Panas', 'category' => 'Minuman', 'description' => 'Kopi susu klasik untuk sarapan.', 'price' => 12000],
            ['name' => 'Teh Obeng', 'category' => 'Minuman', 'description' => 'Es teh manis khas Kepri.', 'price' => 7000],
        ];

        foreach ($menus as $menu) {
            MenuItem::firstOrCreate(
                ['name' => $menu['name']],
                $menu + ['is_available' => true],
            );
        }
    }
}
