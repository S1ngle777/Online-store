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
            'name' => 'Стандартная доставка',
            'description' => 'Доставка в течение 2-4 рабочих дней',
            'price' => 50.00,
            'delivery_time' => 3,
        ]);

        DeliveryMethod::create([
            'name' => 'Экспресс-доставка',
            'description' => 'Доставка на следующий рабочий день',
            'price' => 100.00,
            'delivery_time' => 1,
        ]);

        DeliveryMethod::create([
            'name' => 'Самовывоз',
            'description' => 'Бесплатный самовывоз из нашего магазина',
            'price' => 0.00,
            'delivery_time' => 0,
        ]);
    }
}
