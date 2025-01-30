<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'id' => 2,
                'name' => 'Брошь "Молдавский узор"',
                'slug' => 'bros-moldavskii-uzor',
                'description' => 'Handmade брошь с традиционным молдавским орнаментом, выполненная из бисера и металла',
                'price' => 350,
                'stock' => 7,
                'category_id' => 2,
                'image' => 'products/PZW3M3Q1oXbv3BeYdJ4SH09Fksb1m6zAVKf3f776.jpg',
                'created_at' => '2024-12-04 15:07:33',
                'updated_at' => '2024-12-04 17:41:47'
            ],
            // Add all other products here...

            [
                'id' => 3,
                'name' => 'Серьги "Виноградная лоза"',
                'slug' => 'sergi-vinogradnaia-loza',
                'description' => 'Серьги ручной работы в виде виноградной лозы из серебра 925 пробы',
                'price' => 450,
                'stock' => 2,
                'category_id' => 2,
                'image' => 'products/bdmIA5BIhSCmoHjLVhXxStvVonpBXjZZ26eaUmyC.jpg',
                'created_at' => '2024-12-04 15:09:29',
                'updated_at' => '2024-12-04 17:34:21'
            ],
            [
                'id' => 4,
                'name' => 'Скатерть "Молдавское наследие"',
                'slug' => 'skatert-moldavskoe-nasledie',
                'description' => 'Большая скатерть с традиционной молдавской вышивкой, 150x250 см',
                'price' => 1200,
                'stock' => 3,
                'category_id' => 3,
                'image' => 'products/SZz0Vq6QOHv4800ZdL42iXONxoTnywGtXy3xJV26.jpg',
                'created_at' => '2024-12-04 15:11:54',
                'updated_at' => '2024-12-04 15:11:54'
            ],
            [
                'id' => 5,
                'name' => 'Рушник "Древо жизни"',
                'slug' => 'rusnik-drevo-zizni',
                'description' => 'Декоративное полотенце с вышитым узором древа жизни',
                'price' => 580,
                'stock' => 8,
                'category_id' => 3,
                'image' => 'products/Cbxzg0k1XBkR120vbIAZgbMKBlXwOUZDXJSstvET.jpg',
                'created_at' => '2024-12-04 15:15:39',
                'updated_at' => '2024-12-04 15:15:39'
            ],

            [
                'id' => 6,
                'name' => 'Кувшин "Молдавский дом"',
                'slug' => 'kuvsin-moldavskii-dom',
                'description' => 'Керамический кувшин с ручной росписью, изображающей традиционный молдавский дом',
                'price' => 890,
                'stock' => 4,
                'category_id' => 4,
                'image' => 'products/NHjXrf8pesDPP3vdF7XW8F7sYSvfDCJ6S9euTLnY.jpg',
                'created_at' => '2024-12-04 15:17:16',
                'updated_at' => '2024-12-04 15:17:16'
            ],
            [
                'id' => 7,
                'name' => 'Тарелка "Национальные мотивы"',
                'slug' => 'tarelka-nacionalnye-motivy',
                'description' => 'Декоративная тарелка с традиционным молдавским орнаментом',
                'price' => 450,
                'stock' => 15,
                'category_id' => 4,
                'image' => 'products/k28XIEydScjpOhicwugPVZGAiw2C6GsSRWownggU.jpg',
                'created_at' => '2024-12-04 15:21:15',
                'updated_at' => '2024-12-04 15:21:15'
            ],
            [
                'id' => 8,
                'name' => 'Шкатулка "Молдавские узоры"',
                'slug' => 'skatulka-moldavskie-uzory',
                'description' => 'Резная деревянная шкатулка с традиционными молдавскими орнаментами',
                'price' => 750,
                'stock' => 6,
                'category_id' => 5,
                'image' => 'products/wUejZSlnXBBIwRahdNPZQE6Xh0CGqUl8HQVBrowt.jpg',
                'created_at' => '2024-12-04 15:22:51',
                'updated_at' => '2024-12-04 15:22:51'
            ],

            [
                'id' => 9,
                'name' => 'Рама "Виноградник"',
                'slug' => 'rama-vinogradnik',
                'description' => 'Резная деревянная рама для фотографий с мотивами виноградной лозы',
                'price' => 420,
                'stock' => 12,
                'category_id' => 5,
                'image' => 'products/6WZYTd1jN2tZUUfRXvPEalmx0voGCPU9pBgkxC91.jpg',
                'created_at' => '2024-12-04 15:26:31',
                'updated_at' => '2024-12-04 15:26:31'
            ],
            [
                'id' => 10,
                'name' => 'Блузка ручной работы',
                'slug' => 'bluzka-rucnoi-raboty',
                'description' => 'Традиционная молдавская блузка с ручной вышивкой',
                'price' => 2500,
                'stock' => 3,
                'category_id' => 6,
                'image' => 'products/MMFCcFE6BrrwsyTpRINLSnf9D3wAMuuYXwfPx8Jz.jpg',
                'created_at' => '2024-12-04 15:33:29',
                'updated_at' => '2024-12-04 15:33:29'
            ],
            [
                'id' => 11,
                'name' => 'Магнит "Национальные символы"',
                'slug' => 'magnit-nacionalnye-simvoly',
                'description' => 'Керамический магнит с изображением национальных молдавских символов',
                'price' => 120,
                'stock' => 50,
                'category_id' => 7,
                'image' => 'products/sHP4I9ra1xJByGcaP2wVDrt6P8hyi7zOflBBz3va.jpg',
                'created_at' => '2024-12-04 15:37:21',
                'updated_at' => '2024-12-04 15:37:21'
            ],

            [
                'id' => 12,
                'name' => 'Брелок "Молдавский герб"',
                'slug' => 'brelok-moldavskii-gerb',
                'description' => 'Металлический брелок с гравировкой молдавского герба',
                'price' => 250,
                'stock' => 25,
                'category_id' => 7,
                'image' => 'products/Q753InBBUy3jciGH8SsZfxGO2btLxLKtp9NBhHDF.jpg',
                'created_at' => '2024-12-04 15:39:13',
                'updated_at' => '2024-12-04 15:41:32'
            ],

        ];


        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
