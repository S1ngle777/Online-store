<?php
namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Seeder;

class SizeSeeder extends Seeder
{
    public function run()
    {
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
        
        foreach ($sizes as $size) {
            Size::create([
                'name' => $size,
                'type' => 'clothing'
            ]);
        }
    }
}