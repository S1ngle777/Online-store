<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // \App\Models\User::create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        //     'password' => bcrypt('adminadmin'),
        //     'role' => 'admin'
        // ]);

        // \App\Models\Category::create([
        //     'name' => 'Default Category',
        //     'slug' => 'default-category',
        //     'description' => 'This is the default category'
        // ]);

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            DeliveryMethodSeeder::class,
            SizeSeeder::class,
        ]);
    }
}
