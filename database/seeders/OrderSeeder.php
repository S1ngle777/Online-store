<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $orders = [
            [
                'id' => 1,
                'user_id' => 1,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'phone' => '077777777',
                'address' => 'Chishinau',
                'total_amount' => 2540,
                'status' => 'processing',
                'notes' => null,
                'created_at' => '2024-12-04 16:29:28',
                'updated_at' => '2024-12-04 17:01:12'
            ],

            [
                'id' => 2,
                'user_id' => 2,
                'name' => 'Nikita',
                'email' => 'nikita@gmal.com',
                'phone' => '07855555',
                'address' => '123',
                'total_amount' => 350,
                'status' => 'pending',
                'notes' => 'Быстро',
                'created_at' => '2024-12-04 16:46:13',
                'updated_at' => '2024-12-04 16:46:13'
            ],

            [
                'id' => 3,
                'user_id' => 1,
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'phone' => '123312',
                'address' => '12332',
                'total_amount' => 450,
                'status' => 'cancelled',
                'notes' => null,
                'created_at' => '2024-12-04 17:05:40',
                'updated_at' => '2024-12-04 17:34:21'
            ],

            [
                'id' => 4,
                'user_id' => 2,
                'name' => 'Nikita',
                'email' => 'nikita@gmal.com',
                'phone' => '123312',
                'address' => '123132',
                'total_amount' => 1050,
                'status' => 'completed',
                'notes' => null,
                'created_at' => '2024-12-04 17:41:47',
                'updated_at' => '2024-12-04 17:41:47'
            ],

        ];

        foreach ($orders as $order) {
            Order::create($order);
        }
    }
}
