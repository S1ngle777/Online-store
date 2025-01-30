<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$Bbnh/sHHbdjOKhyvyiVObepuYyD36/Rc5R37SEHUv.PAIPIRewzYS',
            'role' => 'admin',
            'created_at' => '2024-12-04 00:06:53',
            'updated_at' => '2024-12-04 00:06:53',
        ]);

        User::create([
            'name' => 'Nikita',
            'email' => 'nikita@gmal.com',
            'password' => '$2y$10$t22vhoB5KBn8cPV/atbqYOlxY5SytBpVgyTJK1YIcrM.igizjSZg6',
            'role' => 'user',
            'created_at' => '2024-12-04 00:24:19',
            'updated_at' => '2024-12-04 00:24:19',
        ]);
    }
}
