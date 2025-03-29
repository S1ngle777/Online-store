<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Убедимся, что у нас есть админ
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            // Если админ не найден, используем первого пользователя
            $admin = User::first();
        }

        $posts = [
            [
                'title' => [
                    'en' => 'Secrets of Moldovan Ceramics Making',
                    'ro' => 'Secretele fabricării ceramicii moldovenești',
                    'ru' => 'Секреты изготовления молдавской керамики'
                ],
                'slug' => 'secrets-of-moldavian-ceramics',
                'content' => [
                    'en' => "Moldovan ceramics is known for its unique manufacturing technique and characteristic ornaments.\n\nThe process begins with selecting the right clay, which is mined in certain regions of Moldova. It is then thoroughly cleaned and kneaded to the desired consistency.\n\nMasters use a potter's wheel, some of which are passed down from generation to generation. After forming, the product is left to dry, then fired in special kilns.\n\nPainting is a separate art. Natural dyes are used, and the ornaments are applied by hand with thin brushes. The final firing fixes the paint and gives the product its characteristic luster.",
                    
                    'ro' => "Ceramica moldovenească este cunoscută pentru tehnica sa unică de fabricație și ornamentele caracteristice.\n\nProcesul începe cu selectarea argilei potrivite, care este extrasă din anumite regiuni ale Moldovei. Aceasta este apoi curățată temeinic și frământată până la consistența dorită.\n\nMeșterii folosesc o roată de olar, unele dintre ele fiind transmise din generație în generație. După formare, produsul este lăsat să se usuce, apoi ars în cuptoare speciale.\n\nPictarea este o artă separată. Se folosesc coloranți naturali, iar ornamentele sunt aplicate manual cu pensule subțiri. Arderea finală fixează vopseaua și conferă produsului luciul caracteristic.",
                    
                    'ru' => "Молдавская керамика известна своей уникальной техникой изготовления и характерными орнаментами.\n\nПроцесс начинается с выбора правильной глины, которая добывается в определенных регионах Молдовы. Затем она тщательно очищается и замешивается до нужной консистенции.\n\nМастера используют гончарный круг, некоторые из которых передаются по наследству. После формирования изделие оставляют сохнуть, затем обжигают в специальных печах.\n\nРаскрашивание - отдельное искусство. Используются натуральные красители, а орнаменты наносятся вручную тонкими кистями. Финальный обжиг закрепляет краску и придает изделию характерный блеск."
                ],
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(10),
                'image' => 'posts/ceramics.jpg',
            ],
            [
                'title' => [
                    'en' => 'Modern Trends in National Clothing',
                    'ro' => 'Tendințe moderne în îmbrăcămintea națională',
                    'ru' => 'Современные тенденции в национальной одежде'
                ],
                'slug' => 'modern-trends-in-national-clothing',
                'content' => [
                    'en' => "The national Moldovan costume evolves, maintaining its authenticity while adapting to modern realities.\n\nDesigners incorporate traditional elements such as 'altiță' (sleeve embroidery) into everyday clothing. Blouses in national style are popular and pair well with business suits or jeans.\n\nOur collection offers pieces where traditional embroidery and ornaments harmoniously fit into modern cuts. This allows you to wear a piece of national culture every day, not just on holidays.",
                    
                    'ro' => "Costumul național moldovenesc evoluează, păstrându-și autenticitatea, dar adaptându-se la realitățile moderne.\n\nDesignerii includ elemente tradiționale, precum 'altița' (broderia de pe mâneci), în îmbrăcămintea de zi cu zi. Sunt populare bluzele în stil național, care se potrivesc bine cu un costum de afaceri sau cu blugi.\n\nColecția noastră oferă piese în care broderiile și ornamentele tradiționale se încadrează armonios în croiuri moderne. Acest lucru vă permite să purtați o părticică din cultura națională în fiecare zi, nu doar de sărbători.",
                    
                    'ru' => "Национальный костюм Молдовы эволюционирует, сохраняя свою аутентичность, но адаптируясь к современным реалиям.\n\nДизайнеры включают традиционные элементы, такие как «алтица» (вышивка на рукавах), в повседневную одежду. Популярны блузки в национальном стиле, которые хорошо сочетаются с деловым костюмом или джинсами.\n\nМы предлагаем коллекцию, где традиционные вышивки и орнаменты гармонично вписываются в современный крой. Это позволяет носить частичку национальной культуры каждый день, а не только по праздникам."
                ],
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(5),
                'image' => 'posts/clothing.jpg',
            ],
            [
                'title' => [
                    'en' => 'Innovations in Traditional Crafts',
                    'ro' => 'Inovații în meșteșugurile tradiționale',
                    'ru' => 'Инновации в традиционных ремеслах'
                ],
                'slug' => 'innovations-in-traditional-crafts',
                'content' => [
                    'en' => "How do traditional crafts adapt to the modern world? Our craftsmen find innovative approaches while preserving cultural heritage.\n\nThe use of new materials and technologies allows creating more durable products that maintain a traditional appearance. For example, waterproof coatings for wooden items or special treatment of textiles to prevent bright colors from fading.\n\nDigital design helps create complex ornaments that are then implemented by hand. This combination of technology and manual labor makes each product unique.",
                    
                    'ro' => "Cum se adaptează meșteșugurile tradiționale la lumea modernă? Meșterii noștri găsesc abordări inovatoare, păstrând în același timp patrimoniul cultural.\n\nUtilizarea de noi materiale și tehnologii permite crearea unor produse mai durabile, care își mențin aspectul tradițional. De exemplu, acoperiri impermeabile pentru obiectele din lemn sau tratamente speciale ale textilelor pentru a preveni decolorarea.\n\nDesignul digital ajută la crearea unor ornamente complexe, care sunt apoi implementate manual. Această combinație de tehnologie și muncă manuală face ca fiecare produs să fie unic.",
                    
                    'ru' => "Как традиционные ремесла адаптируются к современному миру? Наши мастера находят инновационные подходы, сохраняя культурное наследие.\n\nИспользование новых материалов и технологий позволяет создавать более долговечные изделия, сохраняющие традиционный вид. Например, водостойкие покрытия для деревянных изделий или специальная обработка текстиля для предотвращения выгорания ярких красок.\n\nЦифровое проектирование помогает создавать сложные орнаменты, которые затем воплощаются вручную. Это сочетание технологии и ручного труда делает каждое изделие уникальным."
                ],
                'is_published' => false, // Черновик
                'published_at' => null,
                'image' => 'posts/innovation.jpg',
            ],
            [
                'title' => [
                    'en' => 'How to Choose an Authentic Moldovan Souvenir',
                    'ro' => 'Cum să alegi un suvenir moldovenesc autentic',
                    'ru' => 'Как выбрать аутентичный молдавский сувенир'
                ],
                'slug' => 'how-to-choose-authentic-moldavian-souvenir',
                'content' => [
                    'en' => "Want to bring home a genuine Moldovan souvenir? Here are some tips on how to distinguish an authentic item.\n\nPay attention to the quality of materials and finishing. Real craftsmen pay attention to every detail.\n\nStudy the ornaments - traditional Moldovan patterns have characteristic features. For example, geometric shapes and floral motifs have symbolic meanings.\n\nAsk about the history of the item. Real artisans will gladly tell you about the creation process and the significance of various elements.\n\nAll our products come with a certificate of authenticity that includes information about the craftsman and the region of origin.",
                    
                    'ro' => "Doriți să aduceți acasă un suvenir moldovenesc autentic? Iată câteva sfaturi despre cum să distingeți un articol autentic.\n\nAcordați atenție calității materialelor și finisajelor. Meșterii adevărați acordă atenție fiecărui detaliu.\n\nStudiați ornamentele - modelele tradiționale moldovenești au caracteristici specifice. De exemplu, formele geometrice și motivele florale au semnificații simbolice.\n\nÎntrebați despre istoria obiectului. Meșteșugarii adevărați vă vor povesti cu plăcere despre procesul de creare și semnificația diferitelor elemente.\n\nToate produsele noastre vin cu un certificat de autenticitate care include informații despre meșter și regiunea de origine.",
                    
                    'ru' => "Хотите привезти домой настоящий молдавский сувенир? Вот несколько советов, как отличить аутентичное изделие.\n\nОбратите внимание на качество материалов и отделки. Настоящие мастера уделяют внимание каждой детали.\n\nИзучите орнаменты - у традиционных молдавских узоров есть характерные особенности. Например, геометрические формы и растительные мотивы имеют символическое значение.\n\nПоинтересуйтесь историей изделия. Настоящие ремесленники с удовольствием расскажут о процессе создания и значении различных элементов.\n\nВсе наши изделия сопровождаются сертификатом аутентичности с информацией о мастере и регионе происхождения."
                ],
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(2),
                'image' => 'posts/souvenirs.jpg',
            ],
        ];

        foreach ($posts as $postData) {
            Post::create([
                'title' => $postData['title'],
                'slug' => $postData['slug'],
                'content' => $postData['content'],
                'user_id' => $admin->id,
                'is_published' => $postData['is_published'],
                'published_at' => $postData['published_at'],
                'image' => $postData['image'],
            ]);
        }
    }
}
