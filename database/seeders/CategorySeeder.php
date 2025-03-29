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
                'name' => [
                    'en' => 'Traditional Jewelry',
                    'ro' => 'Bijuterii Tradiționale',
                    'ru' => 'Традиционные украшения'
                ],
                'slug' => 'traditional-jewelry',
                'description' => [
                    'en' => 'Handmade jewelry with traditional Moldovan motifs',
                    'ro' => 'Bijuterii lucrate manual cu motive tradiționale moldovenești',
                    'ru' => 'Украшения ручной работы с традиционными молдавскими мотивами'
                ]
            ],
            [
                'id' => 3,
                'name' => [
                    'en' => 'Textiles and Embroidery',
                    'ro' => 'Textile și Broderie',
                    'ru' => 'Текстиль и вышивка'
                ],
                'slug' => 'textiles-embroidery',
                'description' => [
                    'en' => 'Embroidered items, tablecloths, towels and other textiles with national patterns',
                    'ro' => 'Articole brodate, fețe de masă, prosoape și alte textile cu motive naționale',
                    'ru' => 'Вышитые изделия, скатерти, полотенца и другой текстиль с национальными узорами'
                ]
            ],
            [
                'id' => 4,
                'name' => [
                    'en' => 'Ceramics',
                    'ro' => 'Ceramică',
                    'ru' => 'Керамика'
                ],
                'slug' => 'ceramics',
                'description' => [
                    'en' => 'Handmade ceramic products in Moldovan style',
                    'ro' => 'Produse ceramice lucrate manual în stil moldovenesc',
                    'ru' => 'Handmade керамические изделия в молдавском стиле'
                ]
            ],
            [
                'id' => 5,
                'name' => [
                    'en' => 'Wooden Crafts',
                    'ro' => 'Obiecte din Lemn',
                    'ru' => 'Деревянные изделия'
                ],
                'slug' => 'wooden-crafts',
                'description' => [
                    'en' => 'Carved wooden items with traditional ornaments',
                    'ro' => 'Obiecte sculptate din lemn cu ornamente tradiționale',
                    'ru' => 'Резные изделия из дерева с традиционными орнаментами'
                ]
            ],
            [
                'id' => 6,
                'name' => [
                    'en' => 'National Costumes',
                    'ro' => 'Costume Naționale',
                    'ru' => 'Национальные костюмы'
                ],
                'slug' => 'national-costumes',
                'description' => [
                    'en' => 'Elements of the traditional Moldovan costume handmade',
                    'ro' => 'Elemente ale costumului tradițional moldovenesc lucrate manual',
                    'ru' => 'Элементы традиционного молдавского костюма ручной работы'
                ]
            ],
            [
                'id' => 7,
                'name' => [
                    'en' => 'Souvenirs',
                    'ro' => 'Suveniruri',
                    'ru' => 'Сувениры'
                ],
                'slug' => 'souvenirs',
                'description' => [
                    'en' => 'Unique souvenirs with Moldovan symbols',
                    'ro' => 'Suveniruri unice cu simboluri moldovenești',
                    'ru' => 'Уникальные сувениры с молдавской символикой'
                ]
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
