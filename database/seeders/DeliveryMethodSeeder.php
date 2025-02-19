<?php

namespace Database\Seeders;

use App\Models\DeliveryMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DeliveryMethod::create([
            'name' => 'standard',
            'description' => 'standard',
            'price' => 50.00,
            'delivery_time' => 3,
        ]);

        DeliveryMethod::create([
            'name' => 'express',
            'description' => 'express',
            'price' => 100.00,
            'delivery_time' => 1,
        ]);

        DeliveryMethod::create([
            'name' => 'pickup',
            'description' => 'pickup',
            'price' => 0.00,
            'delivery_time' => 0,
        ]);
    }
}
