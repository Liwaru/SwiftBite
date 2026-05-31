<?php

namespace Database\Seeders;

use App\Models\DiningTable;
use App\Models\Category;
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
                'level' => 0,
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
            ['name' => 'waiter'],
            [
                'email' => 'waiter@example.com',
                'password' => 'waiter',
                'level' => 1,
            ],
        );

        User::updateOrCreate(
            ['name' => 'manager'],
            [
                'email' => 'manager@example.com',
                'password' => 'manager',
                'level' => 3,
            ],
        );

        User::updateOrCreate(
            ['name' => 'owner'],
            [
                'email' => 'owner@example.com',
                'password' => 'owner',
                'level' => 4,
            ],
        );

        foreach (range(1, 6) as $number) {
            DiningTable::firstOrCreate(
                ['nama_meja' => 'Meja ' . $number],
                ['token' => Str::random(32), 'status' => 'aktif'],
            );
        }

        $menus = [
            ['name' => 'Butter Croissant', 'category' => 'Makanan', 'description' => 'Croissant klasik dengan aroma butter.', 'price' => 18000, 'stock' => 20],
            ['name' => 'Chocolate Croissant', 'category' => 'Makanan', 'description' => 'Croissant isi cokelat lembut.', 'price' => 20000, 'stock' => 18],
            ['name' => 'Almond Croissant', 'category' => 'Makanan', 'description' => 'Croissant almond dengan taburan kacang.', 'price' => 24000, 'stock' => 12],
            ['name' => 'Cinnamon Roll', 'category' => 'Makanan', 'description' => 'Roti gulung kayu manis dengan glaze.', 'price' => 22000, 'stock' => 16],
            ['name' => 'Pain au Chocolat', 'category' => 'Makanan', 'description' => 'Pastry lapis isi cokelat.', 'price' => 24000, 'stock' => 14],
            ['name' => 'Cheese Bread', 'category' => 'Makanan', 'description' => 'Roti lembut dengan isian keju.', 'price' => 16000, 'stock' => 22],
            ['name' => 'Sausage Roll', 'category' => 'Makanan', 'description' => 'Roti gurih isi sosis panggang.', 'price' => 19000, 'stock' => 15],
            ['name' => 'Chicken Floss Bread', 'category' => 'Makanan', 'description' => 'Roti abon ayam gurih.', 'price' => 21000, 'stock' => 15],
            ['name' => 'Donut Glazed', 'category' => 'Makanan', 'description' => 'Donat lembut dengan glaze manis.', 'price' => 14000, 'stock' => 24],
            ['name' => 'Chocolate Muffin', 'category' => 'Makanan', 'description' => 'Muffin cokelat dengan tekstur moist.', 'price' => 18000, 'stock' => 18],
            ['name' => 'Americano', 'category' => 'Minuman', 'description' => 'Kopi hitam espresso dan air panas.', 'price' => 18000, 'stock' => 30],
            ['name' => 'Cafe Latte', 'category' => 'Minuman', 'description' => 'Espresso dengan susu steamed.', 'price' => 22000, 'stock' => 25],
            ['name' => 'Cappuccino', 'category' => 'Minuman', 'description' => 'Espresso, susu, dan foam lembut.', 'price' => 24000, 'stock' => 25],
            ['name' => 'Mocha', 'category' => 'Minuman', 'description' => 'Kopi susu dengan cokelat.', 'price' => 25000, 'stock' => 20],
            ['name' => 'Chocolate Milk', 'category' => 'Minuman', 'description' => 'Susu cokelat dingin atau hangat.', 'price' => 18000, 'stock' => 24],
            ['name' => 'Matcha Latte', 'category' => 'Minuman', 'description' => 'Matcha premium dengan susu.', 'price' => 25000, 'stock' => 20],
            ['name' => 'Lemon Tea', 'category' => 'Minuman', 'description' => 'Teh lemon segar.', 'price' => 15000, 'stock' => 28],
            ['name' => 'Mineral Water', 'category' => 'Minuman', 'description' => 'Air mineral botol.', 'price' => 10000, 'stock' => 40],
        ];

        foreach ($menus as $menu) {
            $category = Category::firstOrCreate([
                'nama_kategori' => $menu['category'],
            ]);

            MenuItem::updateOrCreate(
                ['nama_menu' => $menu['name']],
                [
                    'id_kategori' => $category->id_kategori,
                    'deskripsi' => $menu['description'],
                    'harga' => $menu['price'],
                    'stok' => $menu['stock'],
                    'status' => 'tersedia',
                ],
            );
        }
    }
}
