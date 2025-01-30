<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'id' => 2,
                'name' => 'Традиционные украшения',
                'slug' => 'traditional-jewelry',
                'description' => 'Украшения ручной работы с традиционными молдавскими мотивами'
            ],
            [
                'id' => 3,
                'name' => 'Текстиль и вышивка',
                'slug' => 'textiles-embroidery',
                'description' => 'Вышитые изделия, скатерти, полотенца и другой текстиль с национальными узорами'
            ],
            [
                'id' => 4,
                'name' => 'Керамика',
                'slug' => 'ceramics',
                'description' => 'Handmade керамические изделия в молдавском стиле'
            ],
            [
                'id' => 5,
                'name' => 'Деревянные изделия',
                'slug' => 'wooden-crafts',
                'description' => 'Резные изделия из дерева с традиционными орнаментами'
            ],
            [
                'id' => 6,
                'name' => 'Национальные костюмы',
                'slug' => 'national-costumes',
                'description' => 'Элементы традиционного молдавского костюма ручной работы'
            ],
            [
                'id' => 7,
                'name' => 'Сувениры',
                'slug' => 'souvenirs',
                'description' => 'Уникальные сувениры с молдавской символикой'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
