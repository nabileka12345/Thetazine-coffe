<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@thetazine.com',
            'password' => Hash::make('password123'),
            'role' => 'admin', // Set role as admin
        ]);

        // Create sample products
        $products = [
            [
                'name' => 'Espresso',
                'description' => 'Strong and bold Italian coffee',
                'price' => 35000,
                'stock' => 100,
                'category' => 'coffee',
                'image_url' => 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04',
                'is_available' => true,
            ],
            [
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk and foam',
                'price' => 45000,
                'stock' => 100,
                'category' => 'coffee',
                'image_url' => 'https://images.unsplash.com/photo-1534778101976-62847782c213',
                'is_available' => true,
            ],
            [
                'name' => 'Latte',
                'description' => 'Espresso with steamed milk',
                'price' => 42000,
                'stock' => 100,
                'category' => 'coffee',
                'image_url' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735',
                'is_available' => true,
            ],
            [
                'name' => 'Americano',
                'description' => 'Espresso with hot water',
                'price' => 38000,
                'stock' => 100,
                'category' => 'coffee',
                'image_url' => 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e',
                'is_available' => true,
            ],
            [
                'name' => 'Mocha',
                'description' => 'Espresso with chocolate and steamed milk',
                'price' => 48000,
                'stock' => 100,
                'category' => 'coffee',
                'image_url' => 'https://images.unsplash.com/photo-1578374173705-8e73f9cbb28e',
                'is_available' => true,
            ],
            [
                'name' => 'Croissant',
                'description' => 'Buttery and flaky French pastry',
                'price' => 25000,
                'stock' => 50,
                'category' => 'pastry',
                'image_url' => 'https://images.unsplash.com/photo-1555507036-ab1f4038808a',
                'is_available' => true,
            ],
            [
                'name' => 'Chocolate Muffin',
                'description' => 'Rich chocolate muffin',
                'price' => 30000,
                'stock' => 50,
                'category' => 'pastry',
                'image_url' => 'https://images.unsplash.com/photo-1607958996333-41aef7caefaa',
                'is_available' => true,
            ],
            [
                'name' => 'Banana Bread',
                'description' => 'Moist and delicious banana bread',
                'price' => 28000,
                'stock' => 30,
                'category' => 'pastry',
                'image_url' => 'https://images.unsplash.com/photo-1606890737304-57a1ca8a5b62',
                'is_available' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
