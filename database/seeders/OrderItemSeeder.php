<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {

        // INSERT INTO order_items VALUES(1,1,3,1,450,'2024-12-04 16:29:28','2024-12-04 16:29:28');
        // INSERT INTO order_items VALUES(2,1,4,1,1200,'2024-12-04 16:29:28','2024-12-04 16:29:28');
        // INSERT INTO order_items VALUES(3,1,6,1,890,'2024-12-04 16:29:28','2024-12-04 16:29:28');
        // INSERT INTO order_items VALUES(4,2,2,1,350,'2024-12-04 16:46:13','2024-12-04 16:46:13');
        // INSERT INTO order_items VALUES(5,3,3,1,450,'2024-12-04 17:05:40','2024-12-04 17:05:40');
        // INSERT INTO order_items VALUES(6,4,2,3,350,'2024-12-04 17:41:47','2024-12-04 17:41:47');

        $orderItems = [
            [
                'id' => 1,
                'order_id' => 1,
                'product_id' => 3,
                'quantity' => 1,
                'price' => 450,
                'created_at' => '2024-12-04 16:29:28',
                'updated_at' => '2024-12-04 16:29:28'
            ],

            [
                'id' => 2,
                'order_id' => 1,
                'product_id' => 4,
                'quantity' => 1,
                'price' => 1200,
                'created_at' => '2024-12-04 16:29:28',
                'updated_at' => '2024-12-04 16:29:28'
            ],

            [
                'id' => 3,
                'order_id' => 1,
                'product_id' => 6,
                'quantity' => 1,
                'price' => 890,
                'created_at' => '2024-12-04 16:29:28',
                'updated_at' => '2024-12-04 16:29:28'
            ],

            [
                'id' => 4,
                'order_id' => 2,
                'product_id' => 2,
                'quantity' => 1,
                'price' => 350,
                'created_at' => '2024-12-04 16:46:13',
                'updated_at' => '2024-12-04 16:46:13'
            ],

            [
                'id' => 5,
                'order_id' => 3,
                'product_id' => 3,
                'quantity' => 1,
                'price' => 450,
                'created_at' => '2024-12-04 17:05:40',
                'updated_at' => '2024-12-04 17:05:40'
            ],

            [
                'id' => 6,
                'order_id' => 4,
                'product_id' => 2,
                'quantity' => 3,
                'price' => 350,
                'created_at' => '2024-12-04 17:41:47',
                'updated_at' => '2024-12-04 17:41:47'
            ],

        ];

        foreach ($orderItems as $item) {
            OrderItem::create($item);
        }
    }
}
